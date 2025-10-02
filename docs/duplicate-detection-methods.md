# Duplicate Questionnaire Detection Methods

**Document Version:** 1.0
**Date:** 2025-09-30
**Application:** l_survey
**Author:** Technical Analysis

---

## Executive Summary

This document provides a comprehensive analysis of duplicate questionnaire detection methods in the l_survey application. It examines existing implementations, proposes content-based detection approaches, and provides recommendations for unified duplicate detection across email notifications and admin interface.

---

## Table of Contents

1. [Current Implementation Analysis](#1-current-implementation-analysis)
2. [Historical Implementation (Tag 21.5+)](#2-historical-implementation-tag-215)
3. [Detection Method Comparison](#3-detection-method-comparison)
4. [Content-Based Duplicate Detection](#4-content-based-duplicate-detection)
5. [Implementation Recommendations](#5-implementation-recommendations)
6. [Technical Specifications](#6-technical-specifications)
7. [Migration Path](#7-migration-path)

---

## 1. Current Implementation Analysis

### 1.1 Cookie-Based Detection (Email Notifications)

**Location:** `app/Http/Controllers/Frontend/CollectController.php:166-188`

**Implementation:**
```php
/** use cookies to check if user has filled the same survey questionnaire */
try {
    /** cookie exists */
    if (\Cookie::get('survey_'.$request->survey_id)) {
        $adminUrl = url('/admin/questionnaires/');
        // send message with ip & survey_id's
        Mail::to(User::getAdminEmail())
            ->send(new ErrorNotification(
                'Survey '.$request->survey_id." questionnaire filled twice in the same browser.\n"
                .'Old questionnaire: '.$adminUrl.\Cookie::get('questionnaire')."\n"
                .'New questionnaire: '.$adminUrl.$questionnaire->id."\n",
                'Duplicate Survey Submission'
            ));
    }
    /** set new cookie */
    \Cookie::queue(\Cookie::make('survey_'.$request->survey_id, true, 2880));
    \Cookie::queue(\Cookie::make('questionnaire', $questionnaire->id, 2880));
}
```

**Characteristics:**
- **Detection Trigger:** Real-time during questionnaire submission
- **Scope:** Browser-specific (same browser/device only)
- **Duration:** 2880 minutes (48 hours) cookie lifetime
- **Action:** Sends email notification to admin
- **Limitations:**
  - Easily bypassed (clear cookies, different browser, incognito mode)
  - No cross-browser detection
  - No historical analysis capability
  - Cannot detect duplicates after submission

**Git History:** Introduced in commit `34aed69` (Dec 17, 2020)

---

### 1.2 Activity Log-Based Detection (Current Code)

**Location:** `app/Http/Controllers/Frontend/CollectController.php:140-149`

**Implementation:**
```php
// Non-authorized user - use Spatie ActivityLog
activity()
    ->performedOn($questionnaire)
    ->withProperties([
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'survey_id' => $questionnaire->survey_id,
        'responses_count' => count($request_array),
    ])
    ->log('questionnaire_submit');
```

**Characteristics:**
- **Data Captured:** IP address, user agent, survey ID, response count
- **Storage:** `activity_log` table (via Spatie ActivityLog package)
- **Current Usage:** Logging only (not used for duplicate detection in production)
- **Potential:** Can be queried for duplicate analysis based on IP + user agent fingerprinting

**Database Table:** `activity_log`
```
- id
- log_name
- description (e.g., 'questionnaire_submit')
- subject_type ('App\Questionnaire')
- subject_id (questionnaire_id)
- causer_type
- causer_id
- properties (JSON: ip, user_agent, survey_id, responses_count)
- created_at
- updated_at
```

---

### 1.3 Loguseragent Table (Legacy - Currently Unused)

**Location:** Database table `loguseragents`

**Status:** Table exists but **not populated** in production. No code creates loguseragent records during questionnaire submission.

**Database Schema:**
```sql
CREATE TABLE `loguseragents` (
    `id` int PRIMARY KEY,
    `os` varchar(255),
    `os_version` varchar(255),
    `browser` varchar(255),
    `browser_version` varchar(255),
    `device` varchar(255),
    `language` varchar(255),
    `item_id` int (references questionnaires.id),
    `ipv6` varchar(255),
    `uri` varchar(255),
    `form_submitted` tinyint(1),
    `user_id` int,
    `created_at` timestamp,
    `updated_at` timestamp
);
```

**Indexes:**
- `idx_loguseragents_ipv6` on `ipv6`
- `idx_loguseragents_ip_os` on `ipv6, os`
- `idx_loguseragents_browser` on `browser, browser_version`
- `idx_loguseragents_item_id` on `item_id`

---

## 2. Historical Implementation (Tag 21.5+)

### 2.1 SurveysController::show() Method

**Git Tag:** `21.5+`
**Location:** `app/Http/Controllers/Admin/SurveysController.php`

In tag 21.5+, the `show()` method **always** called `get_duplicates()`:

```php
public function show($id)
{
    if (! Gate::allows('survey_view')) {
        return abort(401);
    }

    $survey = Survey::findOrFail($id);

    $questionnaires = \App\Questionnaire::with(['responses'])
        ->where('survey_id', $id)
        ->latest()
        ->get();

    $items = \App\Item::with(['question.answerlist.answers', 'question.responses'])
        ->where('survey_id', $id)
        ->orderBy('order')
        ->get();

    $duplicates = $this->get_duplicates($id);

    return view('admin.surveys.show', compact('survey', 'questionnaires', 'items', 'duplicates'));
}
```

**Current Version:** Modified to use optional `check_duplicates` query parameter (performance optimization):
```php
// Make duplicate detection optional or async
$duplicates = [];
if (request()->has('check_duplicates')) {
    $duplicates = $this->get_duplicates($id);
}
```

**Reason for Change:** Performance optimization (commit `c455144` - "optimize Questionnaire model and SurveysController for performance")

---

### 2.2 get_duplicates() Method (Tag 21.5+)

**Original Implementation:**

```php
protected function get_duplicates($survey_id)
{
    /** get duplicates */
    $loguseragent = new \App\Loguseragent();
    $duplicates = [];

    /** get $survey->questionnaires */
    $questionnaires_arr = \App\Questionnaire::where('survey_id', $survey_id)
        ->latest()
        ->get()
        ->pluck('id');

    /** select by ip and sw */
    $duplicate_ipsw = $loguseragent::selectRaw('`ipv6`, `os`, `os_version`, `browser`, `browser_version`, COUNT(*) as `count` ')
        ->whereIn('item_id', $questionnaires_arr)
        ->groupBy('ipv6', 'os', 'os_version', 'browser', 'browser_version')
        ->having('count', '>', 1)
        ->get();

    /** select by ip */
    $duplicate_ip = $loguseragent::selectRaw('`ipv6`, COUNT(*) as `count` ')
        ->whereIn('item_id', $questionnaires_arr)
        ->groupBy('ipv6')
        ->having('count', '>', 1)
        ->get();

    /** select by sw */
    $duplicate_sw = $loguseragent::selectRaw('`os`, `os_version`, `browser`, `browser_version`, COUNT(*) as `count` ')
        ->whereIn('item_id', $questionnaires_arr)
        ->groupBy('os', 'os_version', 'browser', 'browser_version')
        ->having('count', '>', 1)
        ->get();

    foreach ($duplicate_ipsw as $obj) {
        $row = [];
        $row['type'] = 'ipsw';
        $row['value'] = [
            'ipv6' => $obj->ipv6,
            'os' => $obj->os,
            'os_version' => $obj->os_version,
            'browser' => $obj->browser,
            'browser_version' => $obj->browser_version
        ];
        $row['count'] = $obj->count;
        $row['loguseragents'] = $loguseragent
            ->whereIn('item_id', $questionnaires_arr)
            ->where([
                ['ipv6', $obj->ipv6],
                ['os', $obj->os],
                ['os_version', $obj->os_version],
                ['browser', $obj->browser],
                ['browser_version', $obj->browser_version]
            ])
            ->get();
        $duplicates[] = $row;
    }

    foreach ($duplicate_ip as $obj) {
        $row = [];
        $row['type'] = 'ip';
        $row['value'] = $obj->ipv6;
        $row['count'] = $obj->count;
        $row['loguseragents'] = $loguseragent
            ->whereIn('item_id', $questionnaires_arr)
            ->where('ipv6', $obj->ipv6)
            ->get();
        $duplicates[] = $row;
    }

    foreach ($duplicate_sw as $obj) {
        $row = [];
        $row['type'] = 'sw';
        $row['value'] = [
            'os' => $obj->os,
            'os_version' => $obj->os_version,
            'browser' => $obj->browser,
            'browser_version' => $obj->browser_version
        ];
        $row['count'] = $obj->count;
        $row['loguseragents'] = $loguseragent
            ->whereIn('item_id', $questionnaires_arr)
            ->where([
                ['os', $obj->os],
                ['os_version', $obj->os_version],
                ['browser', $obj->browser],
                ['browser_version', $obj->browser_version]
            ])
            ->get();
        $duplicates[] = $row;
    }

    return $duplicates;
}
```

**Detection Granularity (Tag 21.5+):**
1. **Type 'ipsw':** IP + OS + OS Version + Browser + Browser Version (strictest)
2. **Type 'ip':** IP address only
3. **Type 'sw':** OS + OS Version + Browser + Browser Version (software fingerprint only)

**Why It Doesn't Work Now:**
- The `loguseragents` table is **empty** in production
- No code populates this table during questionnaire submission
- The original design expected loguseragent records to be created, but this was never implemented or was removed

---

## 3. Detection Method Comparison

| Feature | Cookie-Based | Activity Log | Loguseragent (21.5+) | Content-Based (Proposed) |
|---------|-------------|--------------|---------------------|------------------------|
| **Detection Trigger** | Real-time | Post-submission | Post-submission | Post-submission |
| **Cross-Browser** | ❌ No | ✅ Yes | ✅ Yes | ✅ Yes |
| **Cross-Device** | ❌ No | ⚠️ Partial | ⚠️ Partial | ✅ Yes |
| **Bypass Difficulty** | 🟢 Easy | 🟡 Moderate | 🟡 Moderate | 🔴 Hard |
| **False Positives** | 🟢 Low | 🟡 Moderate | 🟡 Moderate | 🟡 Moderate |
| **Historical Analysis** | ❌ No | ✅ Yes | ✅ Yes | ✅ Yes |
| **Performance Impact** | 🟢 Minimal | 🟢 Minimal | 🟡 Moderate | 🔴 High |
| **Data Source** | Browser cookies | activity_log table | loguseragents table | responses table |
| **Current Status** | ✅ Working | ✅ Logging only | ❌ Not populated | ❌ Not implemented |
| **Detection Accuracy** | 🟢 Exact (same browser) | 🟡 IP+UA fingerprint | 🟡 IP+SW fingerprint | 🔴 High (with tuning) |

**Legend:**
- ✅ Yes / Supported
- ❌ No / Not Supported
- ⚠️ Partial / Limited Support
- 🟢 Good / Low
- 🟡 Moderate / Medium
- 🔴 Poor / High

---

## 4. Content-Based Duplicate Detection

Content-based detection analyzes the **actual responses** to questions, rather than just IP/browser fingerprints. This is the most robust method for detecting sophisticated duplicate submissions.

### 4.1 Exact Match Detection

**Approach:** Hash-based comparison of response patterns

**Algorithm:**
```php
// Generate fingerprint for a questionnaire
function generateResponseFingerprint($questionnaire_id)
{
    $responses = Response::where('questionnaire_id', $questionnaire_id)
        ->orderBy('question_id')
        ->orderBy('answer_id')
        ->get(['question_id', 'answer_id', 'content']);

    // Create normalized representation
    $data = $responses->map(function($response) {
        return [
            'q' => $response->question_id,
            'a' => $response->answer_id,
            'c' => trim(strtolower($response->content ?? ''))
        ];
    })->toArray();

    // Generate hash
    return hash('sha256', json_encode($data));
}
```

**Implementation Steps:**
1. Add `response_fingerprint` column to `questionnaires` table
2. Calculate fingerprint after questionnaire submission
3. Check for existing questionnaires with same fingerprint + survey_id
4. Trigger duplicate notification if match found

**Migration:**
```php
Schema::table('questionnaires', function (Blueprint $table) {
    $table->string('response_fingerprint', 64)->nullable()->after('survey_id');
    $table->index(['survey_id', 'response_fingerprint'], 'idx_questionnaires_fingerprint');
});
```

**Advantages:**
- ✅ Exact duplicate detection regardless of IP/browser
- ✅ Fast comparison (hash-based)
- ✅ Minimal storage overhead

**Limitations:**
- ❌ No detection of near-duplicates
- ❌ Single character change = different fingerprint
- ❌ Cannot detect systematic patterns

---

### 4.2 Similarity-Based Detection

**Approach:** Calculate similarity scores between questionnaires

#### 4.2.1 Levenshtein Distance

Measures the minimum number of single-character edits needed to change one string into another.

**Use Case:** Detect questionnaires with minor variations in text responses

**Implementation:**
```php
function calculateQuestionnaireLevenshteinSimilarity($q1_id, $q2_id)
{
    $q1_responses = Response::where('questionnaire_id', $q1_id)
        ->orderBy('question_id')
        ->pluck('content', 'question_id');

    $q2_responses = Response::where('questionnaire_id', $q2_id)
        ->orderBy('question_id')
        ->pluck('content', 'question_id');

    $totalDistance = 0;
    $totalLength = 0;

    foreach ($q1_responses as $question_id => $content1) {
        $content2 = $q2_responses[$question_id] ?? '';
        $distance = levenshtein($content1, $content2);
        $maxLength = max(strlen($content1), strlen($content2));

        $totalDistance += $distance;
        $totalLength += $maxLength;
    }

    // Similarity percentage (0-100)
    return $totalLength > 0 ? (1 - ($totalDistance / $totalLength)) * 100 : 0;
}
```

**Threshold Recommendation:** Consider duplicates if similarity > 85%

**Advantages:**
- ✅ Detects near-duplicates with typos
- ✅ Built-in PHP function (no external dependencies)

**Limitations:**
- ❌ O(n²) complexity - expensive for large strings
- ❌ Not suitable for very long text responses
- ❌ Max string length: 255 characters in standard PHP

---

#### 4.2.2 Jaccard Similarity

Measures similarity between two sets based on the size of their intersection divided by the size of their union.

**Use Case:** Detect questionnaires with similar answer selections (multiple choice questions)

**Implementation:**
```php
function calculateJaccardSimilarity($q1_id, $q2_id)
{
    $q1_answers = Response::where('questionnaire_id', $q1_id)
        ->pluck('answer_id')
        ->filter()
        ->unique()
        ->toArray();

    $q2_answers = Response::where('questionnaire_id', $q2_id)
        ->pluck('answer_id')
        ->filter()
        ->unique()
        ->toArray();

    $intersection = count(array_intersect($q1_answers, $q2_answers));
    $union = count(array_unique(array_merge($q1_answers, $q2_answers)));

    return $union > 0 ? ($intersection / $union) * 100 : 0;
}
```

**Threshold Recommendation:** Consider duplicates if similarity > 80%

**Advantages:**
- ✅ Fast computation
- ✅ Works well for multiple-choice questionnaires
- ✅ Ignores order of answers

**Limitations:**
- ❌ Doesn't consider text responses
- ❌ High false positives for short questionnaires
- ❌ Doesn't weight importance of questions

---

#### 4.2.3 Weighted Composite Score

**Approach:** Combine multiple similarity metrics with configurable weights

**Implementation:**
```php
function calculateCompositeSimilarity($q1_id, $q2_id, $weights = [])
{
    $defaultWeights = [
        'jaccard' => 0.5,      // Answer selection similarity
        'content' => 0.3,       // Text response similarity
        'timing' => 0.2,        // Submission time proximity
    ];

    $weights = array_merge($defaultWeights, $weights);

    // 1. Jaccard similarity for answer choices
    $jaccardScore = $this->calculateJaccardSimilarity($q1_id, $q2_id);

    // 2. Content similarity for text responses
    $contentScore = $this->calculateTextResponseSimilarity($q1_id, $q2_id);

    // 3. Temporal proximity (submissions close in time are more suspicious)
    $timingScore = $this->calculateTemporalProximity($q1_id, $q2_id);

    // Calculate weighted composite score
    $compositeScore = (
        $jaccardScore * $weights['jaccard'] +
        $contentScore * $weights['content'] +
        $timingScore * $weights['timing']
    );

    return [
        'composite' => $compositeScore,
        'jaccard' => $jaccardScore,
        'content' => $contentScore,
        'timing' => $timingScore,
    ];
}

private function calculateTextResponseSimilarity($q1_id, $q2_id)
{
    $q1_texts = Response::where('questionnaire_id', $q1_id)
        ->whereNotNull('content')
        ->where('content', '!=', '')
        ->pluck('content');

    $q2_texts = Response::where('questionnaire_id', $q2_id)
        ->whereNotNull('content')
        ->where('content', '!=', '')
        ->pluck('content');

    if ($q1_texts->isEmpty() || $q2_texts->isEmpty()) {
        return 0;
    }

    // Calculate average similarity across all text responses
    $similarities = [];
    foreach ($q1_texts as $index => $text1) {
        if (isset($q2_texts[$index])) {
            similar_text($text1, $q2_texts[$index], $percent);
            $similarities[] = $percent;
        }
    }

    return count($similarities) > 0 ? array_sum($similarities) / count($similarities) : 0;
}

private function calculateTemporalProximity($q1_id, $q2_id)
{
    $q1_time = Questionnaire::find($q1_id)->created_at;
    $q2_time = Questionnaire::find($q2_id)->created_at;

    $diffMinutes = abs($q1_time->diffInMinutes($q2_time));

    // Score decreases as time difference increases
    // 100% if submitted within 1 hour, 0% if more than 24 hours apart
    if ($diffMinutes <= 60) {
        return 100;
    } elseif ($diffMinutes >= 1440) { // 24 hours
        return 0;
    } else {
        return 100 - (($diffMinutes - 60) / 1380 * 100);
    }
}
```

**Threshold Recommendation:** Consider duplicates if composite score > 75%

**Advantages:**
- ✅ Most comprehensive approach
- ✅ Configurable weights per survey type
- ✅ Balances multiple detection signals
- ✅ Lower false positive rate

**Limitations:**
- ❌ Most computationally expensive
- ❌ Requires tuning for optimal performance
- ❌ Complex to maintain

---

### 4.3 Response Pattern Analysis

**Approach:** Detect systematic patterns indicating bot submissions or copy-paste behavior

**Detection Signals:**
1. **Identical timing patterns:** Response times between questions are suspiciously identical
2. **Sequential answer patterns:** Always selecting first/last option
3. **Missing variation:** No variation in optional text fields
4. **Copy-paste signatures:** Identical spelling errors or unusual formatting

**Implementation:**
```php
class DuplicatePatternDetector
{
    public function detectPatterns($survey_id)
    {
        $questionnaires = Questionnaire::where('survey_id', $survey_id)
            ->with('responses')
            ->get();

        $patterns = [
            'sequential_answers' => $this->detectSequentialAnswers($questionnaires),
            'timing_similarity' => $this->detectTimingSimilarity($questionnaires),
            'text_duplication' => $this->detectTextDuplication($questionnaires),
        ];

        return $patterns;
    }

    private function detectSequentialAnswers($questionnaires)
    {
        // Detect if multiple questionnaires always select answers in order
        // (e.g., always selecting answer 1, then 2, then 3, etc.)
        $suspicious = [];

        foreach ($questionnaires as $q) {
            $answers = $q->responses->pluck('answer_id')->toArray();
            $isSequential = $this->isSequentialArray($answers);

            if ($isSequential) {
                $suspicious[] = $q->id;
            }
        }

        return $suspicious;
    }

    private function detectTimingSimilarity($questionnaires)
    {
        // Use activity log to detect submissions with identical timing patterns
        // This would require storing timestamp for each response
        // Placeholder for future implementation
        return [];
    }

    private function detectTextDuplication($questionnaires)
    {
        $textResponses = [];
        $duplicates = [];

        foreach ($questionnaires as $q) {
            foreach ($q->responses as $response) {
                if ($response->content) {
                    $normalized = $this->normalizeText($response->content);

                    if (isset($textResponses[$normalized])) {
                        $duplicates[$q->id][] = $textResponses[$normalized];
                    }

                    $textResponses[$normalized] = [
                        'questionnaire_id' => $q->id,
                        'question_id' => $response->question_id,
                        'original_text' => $response->content
                    ];
                }
            }
        }

        return $duplicates;
    }

    private function normalizeText($text)
    {
        // Remove whitespace variations, normalize case
        return preg_replace('/\s+/', ' ', strtolower(trim($text)));
    }

    private function isSequentialArray($array)
    {
        if (count($array) < 3) {
            return false;
        }

        for ($i = 1; $i < count($array); $i++) {
            if ($array[$i] != $array[$i-1] + 1) {
                return false;
            }
        }

        return true;
    }
}
```

---

### 4.4 Performance Considerations

Content-based detection is **computationally expensive**. Strategies to mitigate:

#### 4.4.1 Lazy/On-Demand Detection
```php
// Only check for duplicates when explicitly requested
if (request()->has('check_duplicates')) {
    $duplicates = $this->getContentBasedDuplicates($survey_id);
}
```

#### 4.4.2 Background Job Processing
```php
// Queue duplicate detection job after submission
dispatch(new DetectDuplicatesJob($questionnaire))->afterResponse();
```

#### 4.4.3 Cached Results
```php
// Cache duplicate detection results for 1 hour
$duplicates = Cache::remember(
    "duplicates_survey_{$survey_id}",
    3600,
    fn() => $this->getContentBasedDuplicates($survey_id)
);
```

#### 4.4.4 Incremental Detection
```php
// Only compare new questionnaire against existing ones
public function detectDuplicatesForNew($new_questionnaire_id)
{
    $survey_id = Questionnaire::find($new_questionnaire_id)->survey_id;

    $existing = Questionnaire::where('survey_id', $survey_id)
        ->where('id', '!=', $new_questionnaire_id)
        ->pluck('id');

    $duplicates = [];
    foreach ($existing as $existing_id) {
        $similarity = $this->calculateSimilarity($new_questionnaire_id, $existing_id);

        if ($similarity['composite'] > 75) {
            $duplicates[] = [
                'questionnaire_id' => $existing_id,
                'similarity' => $similarity
            ];
        }
    }

    return $duplicates;
}
```

---

## 5. Implementation Recommendations

### 5.1 Recommended Architecture

**Two-Tier Detection System:**

#### Tier 1: Real-Time Detection (Email Notifications)
**Method:** Cookie-based + Activity Log fingerprinting

**Logic:**
```php
public function store(StoreQuestionnaire $request)
{
    // ... questionnaire creation code ...

    // Tier 1A: Cookie-based immediate detection (keep existing)
    $this->checkCookieDuplicate($request, $questionnaire);

    // Tier 1B: IP+UserAgent fingerprint check (NEW)
    $this->checkFingerprintDuplicate($questionnaire);

    // ... rest of the code ...
}

private function checkFingerprintDuplicate($questionnaire)
{
    $recentDuplicate = Activity::query()
        ->where('subject_type', 'App\Questionnaire')
        ->where('description', 'questionnaire_submit')
        ->where('subject_id', '!=', $questionnaire->id)
        ->where('created_at', '>', now()->subHours(24))
        ->get()
        ->first(function($activity) use ($questionnaire) {
            return $activity->properties['ip'] === request()->ip() &&
                   $activity->properties['user_agent'] === request()->userAgent() &&
                   $activity->properties['survey_id'] === $questionnaire->survey_id;
        });

    if ($recentDuplicate) {
        Mail::to(User::getAdminEmail())
            ->send(new DuplicateFingerprint($questionnaire, $recentDuplicate));
    }
}
```

**Advantages:**
- ✅ Immediate notification
- ✅ Low false positive rate
- ✅ Minimal performance impact

---

#### Tier 2: Batch Detection (Admin Interface)
**Method:** Content-based similarity analysis

**Logic:**
```php
// app/Http/Controllers/Admin/SurveysController.php
public function show($id)
{
    // ... existing code ...

    $duplicates = [];
    if (request()->has('check_duplicates')) {
        $detectionMethod = request()->get('method', 'fingerprint');

        switch ($detectionMethod) {
            case 'fingerprint':
                $duplicates = $this->getDuplicatesByFingerprint($id);
                break;
            case 'content_exact':
                $duplicates = $this->getDuplicatesByExactContent($id);
                break;
            case 'content_similarity':
                $duplicates = $this->getDuplicatesByContentSimilarity($id);
                break;
            case 'pattern':
                $duplicates = $this->getDuplicatesByPattern($id);
                break;
            case 'all':
                $duplicates = $this->getAllDuplicates($id);
                break;
        }
    }

    return view('admin.surveys.show', compact('survey', 'questionnaires', 'items', 'duplicates'));
}
```

**UI Enhancement:**
```blade
<div class="alert alert-info">
    <p>Select duplicate detection method:</p>
    <div class="btn-group">
        <a href="{{ request()->fullUrlWithQuery(['check_duplicates' => 1, 'method' => 'fingerprint']) }}"
           class="btn btn-sm btn-primary">IP + Browser</a>
        <a href="{{ request()->fullUrlWithQuery(['check_duplicates' => 1, 'method' => 'content_exact']) }}"
           class="btn btn-sm btn-info">Exact Match</a>
        <a href="{{ request()->fullUrlWithQuery(['check_duplicates' => 1, 'method' => 'content_similarity']) }}"
           class="btn btn-sm btn-warning">Similarity (75%+)</a>
        <a href="{{ request()->fullUrlWithQuery(['check_duplicates' => 1, 'method' => 'pattern']) }}"
           class="btn btn-sm btn-danger">Pattern Analysis</a>
        <a href="{{ request()->fullUrlWithQuery(['check_duplicates' => 1, 'method' => 'all']) }}"
           class="btn btn-sm btn-dark">All Methods</a>
    </div>
</div>
```

---

### 5.2 Detection Method Selection Matrix

| Survey Type | Tier 1 (Email) | Tier 2 (Admin UI) | Reason |
|-------------|----------------|-------------------|--------|
| **Public anonymous surveys** | Cookie + Fingerprint | Content Similarity | High duplicate risk, need robust detection |
| **Authenticated user surveys** | Fingerprint only | Pattern Analysis | Users less likely to duplicate, focus on bots |
| **Short questionnaires (<10 questions)** | Cookie + Fingerprint | Exact Match | Fast comparison, low false positive |
| **Long questionnaires (>20 questions)** | Cookie + Fingerprint | Content Similarity | More data points for accurate similarity |
| **Research studies (critical data)** | Fingerprint | All Methods | Maximum rigor required |

---

### 5.3 Configuration-Driven Approach

**Implementation:** Store detection settings in survey model

**Migration:**
```php
Schema::table('surveys', function (Blueprint $table) {
    $table->json('duplicate_detection_config')->nullable()->after('completed');
});
```

**Configuration Structure:**
```php
// Survey duplicate detection configuration
$survey->duplicate_detection_config = [
    'email_notification' => [
        'enabled' => true,
        'methods' => ['cookie', 'fingerprint'],
        'threshold' => null, // Not applicable for exact matching
    ],
    'admin_detection' => [
        'enabled' => true,
        'methods' => ['content_similarity', 'pattern'],
        'similarity_threshold' => 75,
        'cache_duration' => 3600, // seconds
    ],
    'weights' => [
        'jaccard' => 0.5,
        'content' => 0.3,
        'timing' => 0.2,
    ],
];
```

**Usage:**
```php
public function store(StoreQuestionnaire $request)
{
    // ... questionnaire creation ...

    $config = $questionnaire->survey->duplicate_detection_config ?? [];

    if ($config['email_notification']['enabled'] ?? true) {
        $methods = $config['email_notification']['methods'] ?? ['cookie'];

        if (in_array('cookie', $methods)) {
            $this->checkCookieDuplicate($request, $questionnaire);
        }

        if (in_array('fingerprint', $methods)) {
            $this->checkFingerprintDuplicate($questionnaire);
        }

        if (in_array('content', $methods)) {
            dispatch(new CheckContentDuplicate($questionnaire, $config));
        }
    }
}
```

---

## 6. Technical Specifications

### 6.1 Required Database Changes

#### Option 1: Minimal (Use Activity Log)
**No changes required** - use existing `activity_log` table

**Pros:**
- ✅ No migration needed
- ✅ Leverages existing data

**Cons:**
- ❌ Slower queries (JSON properties column)
- ❌ Less optimized indexes

---

#### Option 2: Enhanced (Add Fingerprinting Support)
```php
// Migration: Add fingerprint column to questionnaires
Schema::table('questionnaires', function (Blueprint $table) {
    $table->string('response_fingerprint', 64)->nullable()->after('survey_id');
    $table->string('ip_address', 45)->nullable()->after('response_fingerprint');
    $table->text('user_agent')->nullable()->after('ip_address');

    $table->index(['survey_id', 'response_fingerprint'], 'idx_questionnaires_fingerprint');
    $table->index(['survey_id', 'ip_address'], 'idx_questionnaires_ip');
});
```

**Pros:**
- ✅ Fast duplicate detection
- ✅ Optimized indexes
- ✅ Direct querying without JSON parsing

**Cons:**
- ❌ Data duplication (IP/UA stored in both activity_log and questionnaires)
- ❌ Requires migration and backfill

---

#### Option 3: Full Implementation (Populate Loguseragent)
```php
// Use existing loguseragents table, add population logic

// In CollectController::store()
public function store(StoreQuestionnaire $request)
{
    // ... existing code ...

    // Create loguseragent record
    $parser = new \Jenssegers\Agent\Agent();

    Loguseragent::create([
        'item_id' => $questionnaire->id,
        'ipv6' => request()->ip(),
        'os' => $parser->platform(),
        'os_version' => $parser->version($parser->platform()),
        'browser' => $parser->browser(),
        'browser_version' => $parser->version($parser->browser()),
        'device' => $parser->device(),
        'language' => request()->getPreferredLanguage(),
        'uri' => request()->path(),
        'form_submitted' => 1,
        'user_id' => auth()->id(),
    ]);

    // ... rest of code ...
}
```

**Pros:**
- ✅ Restores tag 21.5+ functionality
- ✅ Structured data (easier queries)
- ✅ Uses existing table/indexes

**Cons:**
- ❌ Requires jenssegers/agent package (not currently installed)
- ❌ Additional database write per submission
- ❌ Duplication with activity_log

---

### 6.2 Package Requirements

#### Required (Already Installed)
- `spatie/laravel-activitylog` ✅ Installed (^4.0.0)
- `laravelcollective/html` ✅ Installed (^6.2)

#### Optional (for Enhanced Detection)
- `jenssegers/agent` ❌ Not installed - for user agent parsing
  ```bash
  composer require jenssegers/agent
  ```

#### For Advanced Similarity
- Built-in PHP functions:
  - `similar_text()` ✅ Available
  - `levenshtein()` ✅ Available
  - `metaphone()` ✅ Available (for phonetic matching)

---

### 6.3 Performance Benchmarks (Estimated)

Based on 1000 questionnaires in a survey:

| Method | Detection Time | Memory Usage | Recommended Max Questionnaires |
|--------|---------------|--------------|-------------------------------|
| Cookie-based | <1ms | ~1KB | Unlimited |
| Activity Log Fingerprint | ~50ms | ~10MB | 10,000 |
| Exact Hash Match | ~100ms | ~20MB | 50,000 |
| Jaccard Similarity | ~2s | ~50MB | 5,000 |
| Levenshtein Distance | ~30s | ~200MB | 500 |
| Composite Score | ~45s | ~300MB | 500 |

**Recommendations:**
- Use background jobs for similarity calculations above 1000 questionnaires
- Implement pagination/chunking for large surveys
- Cache results aggressively
- Consider time-boxed detection (e.g., only check last 30 days)

---

## 7. Migration Path

### Phase 1: Immediate (Week 1)
**Goal:** Restore basic duplicate detection functionality

**Tasks:**
1. ✅ Fix IpConverter component (already completed)
2. Implement Activity Log-based `get_duplicates()` method
3. Update view to support both `loguseragents` (legacy) and activity log data
4. Add email notification for IP+UserAgent fingerprint duplicates
5. Test with real data

**Code Changes:**
- `app/Http/Controllers/Admin/SurveysController.php` - update `get_duplicates()`
- `app/Http/Controllers/Frontend/CollectController.php` - add fingerprint check
- `app/Mail/DuplicateFingerprint.php` - create new mailable

---

### Phase 2: Enhancement (Week 2-3)
**Goal:** Add content-based detection

**Tasks:**
1. Implement exact hash matching (response fingerprint)
2. Add migration for `response_fingerprint` column
3. Create service class: `app/Services/DuplicateDetectionService.php`
4. Update UI to select detection method
5. Add background job for content similarity detection
6. Implement caching strategy

**Code Changes:**
- Create `DuplicateDetectionService`
- Create `DetectDuplicatesJob`
- Add detection method selector to UI
- Update SurveysController to use service

---

### Phase 3: Advanced (Week 4)
**Goal:** Full-featured detection system

**Tasks:**
1. Implement composite similarity scoring
2. Add pattern detection
3. Create admin configuration interface
4. Add per-survey detection settings
5. Performance optimization and caching
6. Documentation and admin guide

**Code Changes:**
- Extend `DuplicateDetectionService` with all methods
- Create admin UI for configuring detection per survey
- Add report generation for duplicate analysis
- Implement detection history/audit log

---

### Phase 4: Optional - Populate Loguseragent (If Decided)
**Goal:** Restore tag 21.5+ functionality with loguseragent table

**Tasks:**
1. Install `jenssegers/agent` package
2. Add loguseragent creation to CollectController
3. Backfill loguseragent data from activity logs (historical)
4. Restore original `get_duplicates()` logic from tag 21.5+
5. Deprecate or remove activity log method

**Decision Point:**
- **Recommended:** Do NOT implement Phase 4
- **Reason:** Duplicates data already in activity_log, adds complexity
- **Alternative:** Use Option 2 (Enhanced) from Section 6.1 instead

---

## 8. Appendix

### A. Example Mailable

**File:** `app/Mail/DuplicateFingerprint.php`

```php
<?php

namespace App\Mail;

use App\Questionnaire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DuplicateFingerprint extends Mailable
{
    use Queueable, SerializesModels;

    public Questionnaire $newQuestionnaire;
    public $duplicateActivity;

    public function __construct(Questionnaire $newQuestionnaire, $duplicateActivity)
    {
        $this->newQuestionnaire = $newQuestionnaire;
        $this->duplicateActivity = $duplicateActivity;
    }

    public function build(): self
    {
        $adminUrl = url('/admin/questionnaires/');

        return $this->subject('Duplicate Survey Submission Detected (Fingerprint)')
            ->markdown('emails.duplicate-fingerprint')
            ->with([
                'survey_id' => $this->newQuestionnaire->survey_id,
                'new_questionnaire_id' => $this->newQuestionnaire->id,
                'new_questionnaire_url' => $adminUrl . $this->newQuestionnaire->id,
                'duplicate_questionnaire_id' => $this->duplicateActivity->subject_id,
                'duplicate_questionnaire_url' => $adminUrl . $this->duplicateActivity->subject_id,
                'ip' => $this->duplicateActivity->properties['ip'] ?? 'Unknown',
                'user_agent' => $this->duplicateActivity->properties['user_agent'] ?? 'Unknown',
                'time_difference' => $this->newQuestionnaire->created_at
                    ->diffForHumans($this->duplicateActivity->created_at),
            ]);
    }
}
```

**View:** `resources/views/emails/duplicate-fingerprint.blade.php`

```blade
@component('mail::message')
# Duplicate Survey Submission Detected

A questionnaire with matching IP address and browser fingerprint has been submitted.

**Survey ID:** {{ $survey_id }}

## Submissions

**Original Submission:**
- ID: {{ $duplicate_questionnaire_id }}
- [View Questionnaire]({{ $duplicate_questionnaire_url }})

**New Submission:**
- ID: {{ $new_questionnaire_id }}
- [View Questionnaire]({{ $new_questionnaire_url }})

## Detection Details

- **IP Address:** {{ $ip }}
- **User Agent:** {{ $user_agent }}
- **Time Between Submissions:** {{ $time_difference }}

@component('mail::button', ['url' => route('admin.surveys.show', $survey_id) . '?check_duplicates=1'])
View All Duplicates
@endcomponent

This is an automated notification from the duplicate detection system.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
```

---

### B. Example Service Class

**File:** `app/Services/DuplicateDetectionService.php`

```php
<?php

namespace App\Services;

use App\Questionnaire;
use App\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Activity;

class DuplicateDetectionService
{
    public function findDuplicates(int $survey_id, string $method = 'fingerprint', array $options = []): array
    {
        return match($method) {
            'fingerprint' => $this->findByFingerprint($survey_id),
            'content_exact' => $this->findByExactContent($survey_id),
            'content_similarity' => $this->findByContentSimilarity($survey_id, $options['threshold'] ?? 75),
            'pattern' => $this->findByPattern($survey_id),
            'all' => $this->findByAllMethods($survey_id),
            default => throw new \InvalidArgumentException("Unknown detection method: {$method}"),
        };
    }

    public function findByFingerprint(int $survey_id): array
    {
        $questionnaires_arr = Questionnaire::where('survey_id', $survey_id)->pluck('id')->toArray();

        if (empty($questionnaires_arr)) {
            return [];
        }

        $activityLogs = Activity::query()
            ->where('subject_type', 'App\Questionnaire')
            ->whereIn('subject_id', $questionnaires_arr)
            ->where('description', 'questionnaire_submit')
            ->get()
            ->groupBy(function($activity) {
                $props = $activity->properties;
                return ($props['ip'] ?? '') . '|' . ($props['user_agent'] ?? '');
            });

        $duplicates = [];
        foreach ($activityLogs as $key => $logs) {
            if ($logs->count() > 1) {
                $duplicates[] = $this->formatDuplicateGroup('fingerprint', $logs);
            }
        }

        return $duplicates;
    }

    public function findByExactContent(int $survey_id): array
    {
        $questionnaires = Questionnaire::where('survey_id', $survey_id)
            ->with('responses:id,questionnaire_id,question_id,answer_id,content')
            ->get();

        $fingerprints = [];
        $duplicates = [];

        foreach ($questionnaires as $questionnaire) {
            $fingerprint = $this->generateResponseFingerprint($questionnaire);

            if (isset($fingerprints[$fingerprint])) {
                $fingerprints[$fingerprint][] = $questionnaire;
            } else {
                $fingerprints[$fingerprint] = [$questionnaire];
            }
        }

        foreach ($fingerprints as $fingerprint => $group) {
            if (count($group) > 1) {
                $duplicates[] = [
                    'type' => 'exact_content',
                    'count' => count($group),
                    'fingerprint' => $fingerprint,
                    'questionnaires' => $group,
                ];
            }
        }

        return $duplicates;
    }

    public function findByContentSimilarity(int $survey_id, float $threshold = 75): array
    {
        // Cache key for expensive operation
        $cacheKey = "duplicates_similarity_{$survey_id}_{$threshold}";

        return Cache::remember($cacheKey, 3600, function() use ($survey_id, $threshold) {
            $questionnaires = Questionnaire::where('survey_id', $survey_id)
                ->with('responses')
                ->get();

            $duplicates = [];
            $checked = [];

            foreach ($questionnaires as $i => $q1) {
                foreach ($questionnaires as $j => $q2) {
                    if ($i >= $j) continue; // Skip self and already checked pairs

                    $pairKey = min($q1->id, $q2->id) . '_' . max($q1->id, $q2->id);
                    if (isset($checked[$pairKey])) continue;

                    $similarity = $this->calculateCompositeSimilarity($q1, $q2);

                    if ($similarity['composite'] >= $threshold) {
                        $duplicates[] = [
                            'type' => 'content_similarity',
                            'questionnaire_1' => $q1,
                            'questionnaire_2' => $q2,
                            'similarity' => $similarity,
                        ];
                    }

                    $checked[$pairKey] = true;
                }
            }

            return $duplicates;
        });
    }

    public function findByPattern(int $survey_id): array
    {
        // Implement pattern detection
        // For now, return empty array (placeholder)
        return [];
    }

    public function findByAllMethods(int $survey_id): array
    {
        return [
            'fingerprint' => $this->findByFingerprint($survey_id),
            'content_exact' => $this->findByExactContent($survey_id),
            'content_similarity' => $this->findByContentSimilarity($survey_id),
            'pattern' => $this->findByPattern($survey_id),
        ];
    }

    private function generateResponseFingerprint(Questionnaire $questionnaire): string
    {
        $data = $questionnaire->responses
            ->sortBy('question_id')
            ->map(fn($r) => [
                'q' => $r->question_id,
                'a' => $r->answer_id,
                'c' => trim(strtolower($r->content ?? ''))
            ])
            ->toArray();

        return hash('sha256', json_encode($data));
    }

    private function calculateCompositeSimilarity(Questionnaire $q1, Questionnaire $q2): array
    {
        // Simplified implementation
        $jaccard = $this->calculateJaccardSimilarity($q1, $q2);
        $content = $this->calculateContentSimilarity($q1, $q2);

        return [
            'composite' => ($jaccard * 0.6) + ($content * 0.4),
            'jaccard' => $jaccard,
            'content' => $content,
        ];
    }

    private function calculateJaccardSimilarity(Questionnaire $q1, Questionnaire $q2): float
    {
        $answers1 = $q1->responses->pluck('answer_id')->filter()->unique()->toArray();
        $answers2 = $q2->responses->pluck('answer_id')->filter()->unique()->toArray();

        $intersection = count(array_intersect($answers1, $answers2));
        $union = count(array_unique(array_merge($answers1, $answers2)));

        return $union > 0 ? ($intersection / $union) * 100 : 0;
    }

    private function calculateContentSimilarity(Questionnaire $q1, Questionnaire $q2): float
    {
        $texts1 = $q1->responses->whereNotNull('content')->where('content', '!=', '')->pluck('content');
        $texts2 = $q2->responses->whereNotNull('content')->where('content', '!=', '')->pluck('content');

        if ($texts1->isEmpty() || $texts2->isEmpty()) {
            return 0;
        }

        $similarities = [];
        foreach ($texts1 as $index => $text1) {
            if (isset($texts2[$index])) {
                similar_text($text1, $texts2[$index], $percent);
                $similarities[] = $percent;
            }
        }

        return count($similarities) > 0 ? array_sum($similarities) / count($similarities) : 0;
    }

    private function formatDuplicateGroup(string $type, Collection $items): array
    {
        return [
            'type' => $type,
            'count' => $items->count(),
            'items' => $items,
        ];
    }
}
```

---

### C. References

1. **Spatie Activity Log Documentation:** https://spatie.be/docs/laravel-activitylog/
2. **PHP String Comparison Functions:**
   - `levenshtein()`: https://www.php.net/manual/en/function.levenshtein.php
   - `similar_text()`: https://www.php.net/manual/en/function.similar-text.php
3. **Jenssegers Agent Package:** https://github.com/jenssegers/agent
4. **Jaccard Similarity:** https://en.wikipedia.org/wiki/Jaccard_index
5. **Laravel Queues:** https://laravel.com/docs/10.x/queues

---

## Document Revision History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0 | 2025-09-30 | Initial document creation | Technical Analysis |

---

**End of Document**