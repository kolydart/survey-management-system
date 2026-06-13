# Plan: Correct & test `docs/survey-export.md` accuracy

**Audience:** a fresh chatbot/agent session. This is self-contained — do not assume prior context.
**Goal:** apply five approved documentation corrections to `docs/survey-export.md`, and add **feature tests** that lock the documented behaviour against the real controller so the docs can't silently drift.
**Source of truth:** [`app/Http/Controllers/Admin/SurveysController.php`](../../app/Http/Controllers/Admin/SurveysController.php) (export methods at lines ~305–776). All five corrections were verified against local production-derived data in `l_survey` (see "Verified facts" at the bottom).

---

## 0. Environment notes (read first)

- **Use `cphp`, never `php`** for all CLI/artisan/composer commands (project convention).
- The **laravel-boost MCP server is currently broken**: the `imagick@8.2` PHP extension fails to load (`libomp.dylib` missing), and the startup warning corrupts the MCP JSON-RPC stream (every `tinker` / `database-query` call returns `Invalid JSON output from tool process: Syntax error`). Until fixed (`brew install libomp`, or disable the imagick extension for CLI PHP), use `cphp artisan tinker --execute='…'` instead of MCP tools. The imagick warning is harmless noise on stderr and does **not** affect exit codes or test results.
- Tests use `DatabaseTransactions` (transactional, no DB reset needed). Auth helpers: `$this->create_user('admin')` + `actingAs`, or `$this->login_user('admin')`.
- Mark every new test method with the `#[Test]` attribute (already imported in the test file). Do **not** use `/** @test */` for new tests.

---

## 1. Documentation edits — `docs/survey-export.md`

Replace the file's body with the target content below. Five changes, all flagged with `← CHANGE n`:

1. Routing table: `json-results`/`csv-results` Method column → `exportRawData → exportAsJSONResults`/`exportAsCSVResults`, plus a note about the discarded raw build.
2. New section **"Where free-text / open-ended answers live"**.
3. Corrected numeric-statistics sentence (even-count median is a float).
4. New bullet documenting the **two distinct `total_responses`** fields.
5. New bullet noting the `sample_responses` / text-question path exists but is **dormant** (all text items are labels).

### Target content for `docs/survey-export.md`

````markdown
# Survey Export Mechanisms

Exports are produced by `Admin\SurveysController` via the `show` action with a `view` query parameter:

| URL parameter | Method | Output |
|---|---|---|
| `?view=json` | `exportRawData` → `exportAsJSON` | Full export: survey metadata, questions index, every questionnaire with individual responses (`survey_{id}_export.json`) |
| `?view=csv` | `exportRawData` → `exportAsCSV` | Flattened responses, one row per response (`survey_{id}_export.csv`) |
| `?view=json-results` | `exportRawData` → `exportAsJSONResults` | Aggregated counts/percentages per question, statistics for numeric questions (`survey_{id}_results.json`) | <!-- ← CHANGE 1 -->
| `?view=csv-results` | `exportRawData` → `exportAsCSVResults` | Aggregated results: one row per answer option (radio/checkbox) or one row per question (text/numeric) (`survey_{id}_results.csv`) | <!-- ← CHANGE 1 -->

All four modes are dispatched through `exportRawData()`. For the two `*-results` modes this is wasteful: `exportRawData` builds the entire raw structure and eager-loads every response (`questionnaires.responses.question` / `.answer`), then discards it and calls `exportAsJSONResults($id)` / `exportAsCSVResults($id)`, which reload the survey and re-query from scratch. <!-- ← CHANGE 1 -->

## Where free-text / open-ended answers live  <!-- ← CHANGE 2 -->

Free-text answers are **not** stored on text-type questions. They are stored as `response.content` on **`open` answers** (`answer.open = 1`) attached to radio/checkbox questions.

- The **full export** (`json` / `csv`) is the only export that surfaces this text, via the `content` field/column.
- The **results exports** (`json-results` / `csv-results`) fold open answers into the `count` / `percentage` of their answer option and **discard the text entirely**.

## Soft-delete guarantee (June 2026)

Model relations (`Survey::questionnaires()`, `items()`, `Questionnaire::responses()`, etc.) switch to `withTrashed()` when the request carries `show_deleted=1`. Exports are research data, so all three export entry points now neutralize this flag (`request()->merge(['show_deleted' => 0])`): **soft-deleted questionnaires, items and responses can never enter an export**, regardless of UI state.

Covered by `SurveysControllerTest::exports_exclude_soft_deleted_questionnaires_even_with_show_deleted_parameter`.

## Semantics worth knowing

- `responses_summary.total_questionnaires` (full export) and `survey.total_responses` (results export) both hold the **questionnaire count**, including questionnaires with zero responses.
- Per-question `total_responses` counts **response rows**. For checkbox questions this equals the number of selections, so answer percentages are shares of selections, not of respondents.
- For text/numeric questions the JSON results carry **two different** response counts: the outer per-question `total_responses` (all response rows) and the inner `results.total_responses` (rows with non-empty `content`). They coincide unless some responses have blank content. <!-- ← CHANGE 4 -->
- Numeric statistics: `min`, `max`, and **odd-count** `median` are emitted as **strings** (the raw response value); `mean` and **even-count** `median` are **floats**. <!-- ← CHANGE 3 -->
- The full export includes label items in `questions`; the results exports skip them (`!$item->question || $item->label`).
- `exportAsJSONResults` emits `results.sample_responses` (first 10 non-empty contents) for non-numeric text questions. This path exists but is **dormant in the current data set**, where every text-type item is a label, so no text-question results (and no `sample_responses`) are ever produced. <!-- ← CHANGE 5 -->

## Known improvement candidates

- The `*-results` modes route through `exportRawData`, which builds and eager-loads the full raw dataset only to throw it away (see table note). Dispatch results modes directly to their handlers.
- N+1 queries in the two results exports (one `Response` query per question).
- Aggregation logic duplicated between `exportAsJSONResults` and `exportAsCSVResults` (candidate for a shared service).
- A `respondents` field per question would disambiguate checkbox percentages.
````

> The June-2026 soft-delete section and the first two "Semantics" bullets are unchanged — keep them verbatim.

---

## 2. Tests — lock the documented behaviour

**File:** [`tests/Feature/app/Http/Controllers/Admin/SurveysControllerTest.php`](../../tests/Feature/app/Http/Controllers/Admin/SurveysControllerTest.php)
Add four `#[Test]` methods. Mirror the existing `exports_exclude_soft_deleted_questionnaires_even_with_show_deleted_parameter` (around line 471) for style.

### Imports to add at the top of the test file
```php
use App\Answer;
use App\Answerlist;
use App\Question;
use App\Response;
```
(`App\Item`, `App\Questionnaire`, `App\Survey` are already imported.)

### Key model wiring (do not guess — verified)
- `Item.label` boolean; results exports skip when `label == 1` or no question. Create test items with `'label' => 0`.
- `Question.answerlist_id` → `Answerlist`. `Answerlist.type` drives the branch: `radio`/`checkbox` → per-answer aggregation; types in `['number','range','date','time','datetime-local','week','month']` → statistics; anything else (`text`, …) → `sample_responses`.
- **Answers attach to an answerlist via the many-to-many pivot `answer_answerlist`**: `$answerlist->answers()->attach($answer->id);`. (Not a foreign key on `answers`.)
- A `Response` belongs to a `questionnaire` (which belongs to the survey), a `question`, and optionally an `answer`; free text is in `response.content`.
- Statistics come from `SurveyStatisticsService::calculateStatistics()`: values stay strings (`is_numeric` filter only), so `min`/`max` return the raw string element; `mean = round(sum/count, 2)` (float); median returns the raw element for odd counts (string) but `($low+$high)/2` for even counts (numeric).

### Test 1 — `json_results_export_types_numeric_statistics_correctly`
Proves CHANGE 3.
- **Arrange:** `Survey`; `Answerlist` with `['type' => 'number']`; `Question` on that answerlist; one `Item` (`label => 0`) linking survey+question. One `Questionnaire` on the survey. Create responses (set `answer_id => null` if the column is nullable; otherwise attach a throwaway `Answer`) with **odd-count** numeric `content`: `'10','20','30'` (sorted median = `'20'`).
- **Act:** `GET route('admin.surveys.show', ['survey'=>$survey,'view'=>'json-results'])` as admin; `$data = $response->json()`.
- **Assert** on the question's `results.statistics`:
  - `assertIsString($stats['min'])`, value `'10'`; `assertIsString($stats['max'])`, value `'30'`.
  - `assertIsString($stats['median'])`, value `'20'` (odd count).
  - `assertIsFloat($stats['mean'])` (== 20.0).
- **Add an even-count case** (second question or second survey) with `content` `'10','20','35','40'` (middle two = 20,35) → assert `is_float($stats['median'])` and value `27.5`.

### Test 2 — `json_results_export_exposes_distinct_total_responses_fields`
Proves CHANGE 4.
- **Arrange:** survey + `number` question + item(`label=0`) + questionnaire. Create **4** responses: three with non-empty `content` (`'5','6','7'`) and one with `content => ''` (or `null`).
- **Act:** `view=json-results`, decode JSON, grab the question entry.
- **Assert:** outer `$question['total_responses'] === 4` **and** inner `$question['results']['total_responses'] === 3`. (Demonstrates the two fields differ when blanks exist.)

### Test 3 — `full_export_includes_open_answer_text_but_results_export_drops_it`
Proves CHANGE 2.
- **Arrange:** survey; `Answerlist` `['type'=>'radio']`; `Question`; item(`label=0`); `Answer` with `['open'=>1]` attached via `$answerlist->answers()->attach($answer->id)`; one `Questionnaire`; one `Response` with `question_id`, `answer_id => $answer->id`, `content => 'OPEN_TEXT_MARKER'`.
- **Act + Assert:**
  - `view=json` (full export): `assertStringContainsString('OPEN_TEXT_MARKER', $response->getContent())` (it appears under `questionnaires[].responses[].content`).
  - `view=json-results`: `assertStringNotContainsString('OPEN_TEXT_MARKER', $resultsResponse->getContent())` — results only carry the option's `count`/`percentage`.

### Test 4 — `json_results_export_emits_sample_responses_for_text_questions`
Proves CHANGE 5 (documents the path exists even though production data never triggers it).
- **Arrange:** survey; `Answerlist` `['type'=>'text']`; `Question`; item(`label=0`); questionnaire; ≥2 responses with non-empty `content` (e.g. `'alpha'`, `'beta'`).
- **Act:** `view=json-results`, decode, grab the question entry.
- **Assert:** `assertArrayHasKey('sample_responses', $question['results'])` and it contains `'alpha'` and `'beta'`. (Optional: a sibling assertion that a `label=1` text item is absent from `data['questions']`, confirming why production never hits this branch.)

---

## 3. Run & verify

```bash
cphp artisan test --compact --filter=SurveysControllerTest
```
All existing + 4 new tests must pass. (Ignore the imagick startup warning on stderr.)

Optional spot-check against live data (text items really are all labels):
```bash
cphp artisan tinker --execute='
$n=["number","range","date","time","datetime-local","week","month"];
$t=\App\Item::whereHas("question.answerlist",fn($q)=>$q->whereNotIn("type",array_merge(["radio","checkbox"],$n)))->get();
echo "text-type items=".$t->count()." label=0=".$t->where("label",0)->count()."\n";'
```
Expected: `text-type items=201 label=0=0`.

---

## 4. Update relevant documentation (mandatory post-step)

- Primary doc is `docs/survey-export.md` itself (edited in step 1).
- Add a one-line cross-reference in the doc's test note if useful, pointing at the four new test methods.
- After this plan is executed, **move/delete this roadmap file** (`docs/roadmaps/survey-export-doc-accuracy-plan.md`) since the work is done — or archive it under `docs/history/` if a record is wanted.
- In the final report, state explicitly which files changed (`docs/survey-export.md`, the test file) and the test result.

---

## Acceptance criteria (definition of done)

- [ ] `docs/survey-export.md` reflects all five changes exactly as in §1.
- [ ] Four new `#[Test]` methods added; full `SurveysControllerTest` suite green via `cphp artisan test --filter=SurveysControllerTest`.
- [ ] No `php` (only `cphp`) used; no `.env`/vendor edits.
- [ ] Roadmap file removed/archived and changed files listed in the final report.

---

## Verified facts (reference — already confirmed against local data, no need to re-derive)

| Claim | Evidence |
|---|---|
| Results modes route through `exportRawData` then discard its build | `show()` line ~147 dispatches all four modes to `exportRawData`; lines ~410–416 call the results handlers with `$id`, ignoring the built `$data`. |
| `min`/`max`/odd-median are strings; `mean`/even-median are floats | Survey 2023 `json-results`: `min='1965'`, `max='2007'`, odd-count `median='1990'` (count 41); another question even-count `median=14.5` float (count 40); `mean=1989.49` float. |
| Two `total_responses` fields | `exportAsJSONResults`: inner `results.total_responses = count($contents)` (non-empty) at line ~589; outer `questionData['total_responses'] = $totalResponses` (all rows) at line ~613. |
| Free-text lives on `open` answers | 4 `open` answers, **368** responses with non-empty `content` on them; **752** total non-empty-content responses. |
| All text-type items are labels (sample_responses dormant) | 201 text-type items, **all** `label=1`, **0** non-label → text-question branch never runs in current data. |
| Soft-delete guarantee already tested | `exports_exclude_soft_deleted_questionnaires_even_with_show_deleted_parameter` (test line ~471). |
