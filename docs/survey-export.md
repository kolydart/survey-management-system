# Survey Export Mechanisms

Exports are produced by `Admin\SurveysController` via the `show` action with a `view` query parameter:

| URL parameter | Method | Output |
|---|---|---|
| `?view=json` | `exportRawData` → `exportAsJSON` | Full export: survey metadata, questions index, every questionnaire with individual responses (`survey_{id}_export.json`) |
| `?view=csv` | `exportRawData` → `exportAsCSV` | Flattened responses, one row per response (`survey_{id}_export.csv`) |
| `?view=json-results` | `exportRawData` → `exportAsJSONResults` | Aggregated counts/percentages per question, statistics for numeric questions (`survey_{id}_results.json`) |
| `?view=csv-results` | `exportRawData` → `exportAsCSVResults` | Aggregated results: one row per answer option (radio/checkbox) or one row per question (text/numeric) (`survey_{id}_results.csv`) |

All four modes are dispatched through `exportRawData()`. For the two `*-results` modes this is wasteful: `exportRawData` builds the entire raw structure and eager-loads every response (`questionnaires.responses.question` / `.answer`), then discards it and calls `exportAsJSONResults($id)` / `exportAsCSVResults($id)`, which reload the survey and re-query from scratch.

## Where free-text / open-ended answers live

Free-text answers are **not** stored on text-type questions. They are stored as `response.content` on **`open` answers** (`answer.open = 1`) attached to radio/checkbox questions.

- The **full export** (`json` / `csv`) is the only export that surfaces this text, via the `content` field/column.
- The **results exports** (`json-results` / `csv-results`) fold open answers into the `count` / `percentage` of their answer option and **discard the text entirely**.

## Soft-delete guarantee (June 2026)

Model relations (`Survey::questionnaires()`, `items()`, `Questionnaire::responses()`, etc.) switch to `withTrashed()` when the request carries `show_deleted=1`. Exports are research data, so all three export entry points now neutralize this flag (`request()->merge(['show_deleted' => 0])`): **soft-deleted questionnaires, items and responses can never enter an export**, regardless of UI state.

Covered by `SurveysControllerTest::exports_exclude_soft_deleted_questionnaires_even_with_show_deleted_parameter`.

## Semantics worth knowing

- `responses_summary.total_questionnaires` (full export) and `survey.total_responses` (results export) both hold the **questionnaire count**, including questionnaires with zero responses.
- Per-question `total_responses` counts **response rows**. For checkbox questions this equals the number of selections, so answer percentages are shares of selections, not of respondents.
- For text/numeric questions the JSON results carry **two different** response counts: the outer per-question `total_responses` (all response rows) and the inner `results.total_responses` (rows with non-empty `content`). They coincide unless some responses have blank content.
- Numeric statistics: `min`, `max`, and **odd-count** `median` are emitted as **strings** (the raw response value); `mean` and **even-count** `median` are **floats**.
- The full export includes label items in `questions`; the results exports skip them (`!$item->question || $item->label`).
- `exportAsJSONResults` emits `results.sample_responses` (first 10 non-empty contents) for non-numeric text questions. This path exists but is **dormant in the current data set**, where every text-type item is a label, so no text-question results (and no `sample_responses`) are ever produced.

These semantics are locked by feature tests in `SurveysControllerTest`: `json_results_export_types_numeric_statistics_correctly`, `json_results_export_exposes_distinct_total_responses_fields`, `full_export_includes_open_answer_text_but_results_export_drops_it`, `json_results_export_emits_sample_responses_for_text_questions`.

## Known improvement candidates

- The `*-results` modes route through `exportRawData`, which builds and eager-loads the full raw dataset only to throw it away (see table note). Dispatch results modes directly to their handlers.
- N+1 queries in the two results exports (one `Response` query per question).
- Aggregation logic duplicated between `exportAsJSONResults` and `exportAsCSVResults` (candidate for a shared service).
- A `respondents` field per question would disambiguate checkbox percentages.
