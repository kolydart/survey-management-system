# Duplicate Detection System - Technical Reference

**Version:** 1.0
**Last Updated:** October 3, 2025
**Status:** Production Ready

---

## Overview

The Duplicate Detection System provides multi-method duplicate questionnaire detection for the l_survey application using a centralized service architecture.

### Core Components

- **Service:** `app/Services/DuplicateDetectionService.php`
- **Controllers:** `CollectController`, `SurveysController`
- **View:** `resources/views/admin/surveys/show.blade.php`
- **Tests:** `tests/Unit/Services/DuplicateDetectionServiceTest.php`
- **Config:** `config/app.php` (duplicate_similarity_threshold)

---

## Architecture

### Service Pattern

The system uses a centralized service following the existing pattern (similar to `SurveyStatisticsService`, `ChartDataService`).

```php
class DuplicateDetectionService
{
    // Real-time browser-based detection
    public function checkCookieDuplicate(Request $request, Questionnaire $questionnaire): ?array

    // Post-submission IP + User Agent fingerprinting
    public function findByActivityLog(int $survey_id): array

    // Content similarity analysis (Levenshtein + Jaccard)
    public function findByContentSimilarity(int $survey_id, ?int $threshold = null): array
}
```

### Detection Methods

| Method | Type | Scope | Performance | Use Case |
|--------|------|-------|-------------|----------|
| **Cookie** | Real-time | Single browser | <1ms | Immediate duplicate prevention |
| **Activity Log** | On-demand | Cross-browser (same IP+UA) | ~50ms | Quick fingerprint check |
| **Content Similarity** | On-demand | Cross-device/IP | ~1-30s | Thorough duplicate analysis |

---

## Configuration

### Similarity Threshold

**File:** `config/app.php`

```php
'duplicate_similarity_threshold' => env('DUPLICATE_SIMILARITY_THRESHOLD', 95),
```

**Environment Variable:**
```env
DUPLICATE_SIMILARITY_THRESHOLD=95
```

**Default:** 95% similarity threshold

**Usage:** Controls the minimum similarity percentage for content-based duplicate detection. Adjustable per environment.

---

## Implementation Status

### ✅ Completed (October 2-3, 2025)

#### Phase 1: Service Creation
- [x] `DuplicateDetectionService` with 3 core methods
- [x] Cookie detection (extracted from CollectController)
- [x] Activity log fingerprinting (fixed namespace issue)
- [x] Content similarity (Levenshtein + Jaccard algorithms)

#### Phase 2: Controller Integration
- [x] `CollectController` - Cookie detection via service
- [x] `SurveysController` - Method selection and display
- [x] Removed 50+ lines of inline duplicate logic

#### Phase 3: View Updates
- [x] Method selector buttons with icons
- [x] Similarity score badges
- [x] Smart alternative method buttons
- [x] Auto-navigation to duplicates tab
- [x] Smooth scroll to results

#### Phase 4: Testing
- [x] 13 unit tests (100% passing)
- [x] Cookie detection tests
- [x] Activity log tests
- [x] Content similarity tests

### 🔄 Latest Updates (October 3, 2025)

1. **Fixed Activity Log Namespace**
   - Changed `'App\Questionnaire'` to `'App\\Questionnaire'`
   - Resolved empty table issue

2. **Configurable Similarity Threshold**
   - Added config variable `duplicate_similarity_threshold`
   - Service uses config value with fallback
   - UI displays threshold dynamically

3. **Enhanced Navigation**
   - Auto-activate duplicates tab on `?check_duplicates=1`
   - Smooth scroll to duplicates section
   - JavaScript auto-navigation implementation

4. **Smart UI Buttons**
   - Replaced "Change Method" with specific alternatives
   - Conditional button display based on current method
   - Proper styling and icons

5. **Removed Caching**
   - Removed `Cache::remember()` from similarity detection
   - Fresh results on every request
   - Simplified implementation

### ⏭️ Deferred (Future Enhancements)

- Feature tests for end-to-end flows
- Dedicated `DuplicateCookie` mailable class
- Background job processing for large surveys
- Admin configuration UI (per-survey thresholds)
- Pattern analysis (bot detection)
- Detection history/audit log
- API endpoint for duplicate detection

---

## API Reference

### Dependency Injection

```php
use App\Services\DuplicateDetectionService;

class YourController extends Controller
{
    protected $duplicateService;

    public function __construct(DuplicateDetectionService $duplicateService)
    {
        $this->duplicateService = $duplicateService;
    }
}
```

### Method: checkCookieDuplicate()

**Purpose:** Real-time browser-based duplicate detection with email notification

```php
public function checkCookieDuplicate(Request $request, Questionnaire $questionnaire): ?array
```

**Parameters:**
- `$request` - HTTP request object
- `$questionnaire` - Newly created questionnaire

**Returns:**
- `array` if duplicate detected (with old/new questionnaire IDs)
- `null` if no duplicate

**Side Effects:**
- Sets cookies for future detection (48-hour expiry)
- Sends email notification if duplicate found

**Example:**
```php
$duplicate = $this->duplicateService->checkCookieDuplicate($request, $questionnaire);
if ($duplicate) {
    // Duplicate detected, email sent automatically
}
```

### Method: findByActivityLog()

**Purpose:** IP + User Agent fingerprint-based duplicate detection

```php
public function findByActivityLog(int $survey_id): array
```

**Parameters:**
- `$survey_id` - Survey ID to check

**Returns:** Array of duplicate groups

**Data Structure:**
```php
[
    [
        'type' => 'ipsw',
        'value' => [
            'ipv6' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0...'
        ],
        'count' => 3,
        'loguseragents' => Collection // Activity log records
    ]
]
```

**Example:**
```php
$duplicates = $this->duplicateService->findByActivityLog($survey_id);
```

### Method: findByContentSimilarity()

**Purpose:** Content-based similarity detection using Levenshtein + Jaccard algorithms

```php
public function findByContentSimilarity(int $survey_id, ?int $threshold = null): array
```

**Parameters:**
- `$survey_id` - Survey ID to check
- `$threshold` - Optional similarity threshold (0-100). Defaults to config value.

**Returns:** Array of duplicate pairs

**Data Structure:**
```php
[
    [
        'type' => 'similarity',
        'questionnaire_1_id' => 123,
        'questionnaire_2_id' => 456,
        'similarity_score' => 96.5,
        'loguseragents' => Collection,
        'count' => 2
    ]
]
```

**Example:**
```php
// Use config threshold
$duplicates = $this->duplicateService->findByContentSimilarity($survey_id);

// Use custom threshold
$duplicates = $this->duplicateService->findByContentSimilarity($survey_id, 85);
```

---

## Testing

### Unit Tests

**File:** `tests/Unit/Services/DuplicateDetectionServiceTest.php`

**Coverage:** 13 tests, all passing

**Test Categories:**

1. **Cookie Detection Tests**
   - No duplicate (first submission)
   - Duplicate detected
   - Cookie setting validation

2. **Activity Log Tests**
   - Empty survey handling
   - Grouping by IP + User Agent
   - Namespace validation

3. **Content Similarity Tests**
   - Text response similarity
   - Answer selection similarity (Jaccard)
   - Threshold filtering
   - Data structure validation

**Run Tests:**
```bash
php artisan test --filter=DuplicateDetectionServiceTest
```

---

## Admin Interface Usage

### Step-by-Step

1. **Navigate to Survey**
   ```
   /admin/surveys/{id}
   ```

2. **Click "Duplicates" Tab**
   - Tab opens automatically if `?check_duplicates=1` in URL

3. **Select Detection Method**
   - **IP + Browser Fingerprint** - Fast, same device detection
   - **Content Similarity (95%+)** - Thorough, cross-device detection

4. **View Results**
   - Similarity scores displayed as badges
   - Click questionnaire IDs to view details

5. **Switch Methods**
   - Use "Switch to..." button for alternative method
   - Auto-scrolls to results

---

## Troubleshooting

### Issue: False 100% Similarity (CRITICAL BUG - FIXED)

**Symptom:** Questionnaires with different answers showing 100% similarity

**Example:** Q251 vs Q259 showed 100% similarity despite 11 different answers out of 20 questions

**Root Cause:** Algorithm compared ONLY text content when ANY text existed, completely ignoring answer_id differences

**How the bug worked:**
1. Both questionnaires had text: `["1", "."]`
2. Algorithm detected text and entered text-only comparison mode
3. Compared "1" vs "1" = 100%, "." vs "." = 100%
4. Returned 100% similarity, ignoring all answer_id mismatches

**Solution:** ✅ Fixed in October 3, 2025 update
- Rewrote `calculateSimilarity()` to use per-question comparison
- Always compares answer_ids (primary indicator, 70% weight)
- Includes text similarity when present (secondary indicator, 30% weight)
- Uses weighted combination: `(answerSimilarity * 0.7) + (textSimilarity * 0.3)`

**After fix:** Q251 vs Q259 now correctly shows ~45% similarity (9 matches / 20 questions)

**Tests added:**
- `test_does_not_give_false_100_percent_for_same_text_different_answers()`
- `test_compares_answers_by_question_id_not_position()`
- `test_uses_weighted_combination_70_30()`
- `test_requires_both_answers_and_text_to_match_for_100_percent()`

### Issue: Empty Activity Log Table

**Symptom:** No duplicates shown with Activity Log method

**Cause:** Namespace mismatch in query

**Solution:** ✅ Fixed in October 3, 2025 update
- Changed `'App\Questionnaire'` to `'App\\Questionnaire'`

### Issue: Similarity Detection Slow

**Symptom:** Long load times with Content Similarity method

**Cause:** O(n²) comparison complexity

**Solutions:**
1. Use for surveys with <1000 questionnaires
2. Consider background job processing (future enhancement)
3. Adjust threshold to reduce matches

### Issue: Tab Doesn't Open Automatically

**Symptom:** Duplicates tab not activated on button click

**Cause:** JavaScript not loaded

**Solution:** ✅ Fixed in October 3, 2025 update
- JavaScript added to `@section('javascript')`
- Checks for `?check_duplicates=1` parameter
- Auto-activates tab and scrolls to position

### Issue: Hardcoded Threshold

**Symptom:** Cannot change similarity threshold without code changes

**Solution:** ✅ Fixed in October 3, 2025 update
- Use `DUPLICATE_SIMILARITY_THRESHOLD` in `.env`
- Default: 95%

---

## Database Schema

### Activity Log Table

**Table:** `activity_log` (Spatie ActivityLog package)

**Relevant Columns:**
```sql
- id (bigint)
- subject_type (varchar) -- 'App\\Questionnaire'
- subject_id (bigint) -- questionnaire_id
- description (varchar) -- 'questionnaire_submit'
- properties (json) -- {ip, user_agent, survey_id, responses_count}
- created_at (timestamp)
```

**Indexes:**
- Primary key on `id`
- Index on `subject_type, subject_id`

### Questionnaire Table

**No schema changes required** - Uses existing structure

---

## Performance Notes

### Benchmarks (Estimated)

| Questionnaires | Cookie | Activity Log | Similarity |
|----------------|--------|--------------|------------|
| 10 | <1ms | <10ms | <100ms |
| 100 | <1ms | ~50ms | ~1s |
| 500 | <1ms | ~100ms | ~10s |
| 1000 | <1ms | ~200ms | ~30s |

### Optimization Strategies

1. **Activity Log Method**
   - Already optimized with groupBy
   - Scales well to 10,000+ questionnaires

2. **Content Similarity Method**
   - Threshold filtering reduces comparisons
   - Future: Implement background jobs for large surveys
   - Future: Add pagination for results

3. **Cookie Method**
   - No optimization needed (constant time)

---

## Design Decisions

### Why No Hash-Based Exact Matching?

Levenshtein similarity handles both exact and near-duplicates with a single algorithm. Simpler to maintain and more useful in practice (handles typos).

### Why No Database Migration?

Uses existing `activity_log` table. No new columns needed. Keeps implementation simple and non-invasive.

### Why Remove Caching?

Provides fresh results on every request. Simpler implementation. Performance is acceptable without caching for typical survey sizes.

### Why Only 2 Admin Detection Methods?

- **Activity Log** - Quick fingerprint check
- **Content Similarity** - Thorough analysis

More methods would add complexity without significant benefit. These two cover the majority of use cases.

---

## Future Roadmap

### Phase 5: Enhanced Features (Planned)

1. **Background Job Processing**
   - Queue similarity detection for large surveys
   - Email notification when complete
   - Progress tracking

2. **Admin Configuration UI**
   - Per-survey threshold settings
   - Enable/disable detection methods
   - Custom email templates

3. **Pattern Analysis**
   - Bot detection (sequential answers)
   - Timing analysis
   - Suspicious pattern alerts

4. **Detection History**
   - Log all duplicate detections
   - Timeline visualization
   - Export functionality

5. **API Endpoint**
   - RESTful duplicate detection API
   - Webhook notifications
   - External tool integration

### Phase 6: Advanced Analytics (Future)

1. **Machine Learning Integration**
   - Train model on confirmed duplicates
   - Predictive duplicate scoring
   - Anomaly detection

2. **Multi-Survey Analysis**
   - Cross-survey duplicate detection
   - Respondent profiling
   - Fraud prevention patterns

---

## Code References

### Service Location
`app/Services/DuplicateDetectionService.php:78-172`

### Controller Integration
- `app/Http/Controllers/Frontend/CollectController.php:166` (Cookie check)
- `app/Http/Controllers/Admin/SurveysController.php:165-175` (Method selection)

### View Implementation
`resources/views/admin/surveys/show.blade.php:260-437`

### Configuration
`config/app.php:270`

---

## Related Documentation

- **Comprehensive Analysis:** `docs/duplicate-detection-methods.md`
- **Unit Tests:** `tests/Unit/Services/DuplicateDetectionServiceTest.php`
- **Laravel Activity Log:** https://spatie.be/docs/laravel-activitylog/

---

## Changelog

### Version 1.2 (October 3, 2025) - CRITICAL BUG FIX (Updated)

**Fixed:** False 100% similarity bug in content comparison - COMPLETE FIX

**Changes:**
- Completely rewrote `calculateSimilarity()` method with per-question comparison logic
- Split into 3 helper methods: `calculateAnswerSimilarity()`, `calculateTextSimilarity()`
- Implemented weighted combination (70% answer, 30% text)
- Added 4 comprehensive regression tests (total: 17 tests, all passing)
- Updated troubleshooting documentation

**Algorithm Details:**
- Groups responses by question_id for accurate per-question comparison
- Compares answer_ids with 70% weight (primary indicator)
- Compares text content with 30% weight (secondary indicator)
- Handles multiple responses per question (checkbox questions)
- Returns 0% for mismatched questions

**Impact:** Q251 vs Q259 now correctly shows ~45% similarity instead of false 100%

**Test Coverage:**
- `test_does_not_give_false_100_percent_for_same_text_different_answers()` - Prevents exact bug scenario
- `test_compares_answers_by_question_id_not_position()` - Verifies per-question logic
- `test_uses_weighted_combination_70_30()` - Validates weighting
- `test_requires_both_answers_and_text_to_match_for_100_percent()` - Ensures 100% requires full match

**Files Modified:**
- `app/Services/DuplicateDetectionService.php` (lines 177-291, +114 lines)
- `tests/Unit/Services/DuplicateDetectionServiceTest.php` (+280 lines, 4 new tests)
- `docs/duplicate-detection-technical-reference.md` (this file)

### Version 1.1 (October 3, 2025) - Initial Bug Discovery

**Discovered:** False 100% similarity bug in content comparison

**Analysis:**
- Old algorithm compared ONLY text when present
- Ignored answer_id differences completely
- Example: Q251 vs Q259 showed 100% despite 11/20 different answers

### Version 1.0 (October 3, 2025)

**Created:** Initial technical reference document

**Includes:**
- Complete implementation summary (Oct 2-3, 2025)
- Latest updates (namespace fix, config threshold, auto-navigation)
- API reference for all 3 methods
- Testing documentation
- Troubleshooting guide
- Future roadmap

**Author:** Technical Implementation Team

---

**End of Technical Reference**
