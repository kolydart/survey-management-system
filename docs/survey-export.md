# Survey Export Mechanisms

Exports are produced by `Admin\SurveysController` via the `show` action with a `view` query parameter:

| URL parameter | Method | Output |
|---|---|---|
| `?view=json` | `exportRawData` → `exportAsJSON` | Full export: survey metadata, questions index, every questionnaire with individual responses (`survey_{id}_export.json`) |
| `?view=csv` | `exportRawData` → `exportAsCSV` | Flattened responses, one row per response (`survey_{id}_export.csv`) |
| `?view=json-results` | `exportAsJSONResults` | Aggregated counts/percentages per question, statistics for numeric questions (`survey_{id}_results.json`) |
| `?view=csv-results` | `exportAsCSVResults` | Aggregated results, one row per answer option (`survey_{id}_results.csv`) |

## Soft-delete guarantee (June 2026)

Model relations (`Survey::questionnaires()`, `items()`, `Questionnaire::responses()`, etc.) switch to `withTrashed()` when the request carries `show_deleted=1`. Exports are research data, so all three export entry points now neutralize this flag (`request()->merge(['show_deleted' => 0])`): **soft-deleted questionnaires, items and responses can never enter an export**, regardless of UI state.

Covered by `SurveysControllerTest::exports_exclude_soft_deleted_questionnaires_even_with_show_deleted_parameter`.

## Semantics worth knowing

- `responses_summary.total_questionnaires` (full export) and `survey.total_responses` (results export) both hold the **questionnaire count**, including questionnaires with zero responses.
- Per-question `total_responses` counts **response rows**. For checkbox questions this equals the number of selections, so answer percentages are shares of selections, not of respondents.
- Numeric statistics (`min`, `max`, `median`) are emitted as strings when the source values are strings; `mean` is always a float.
- The full export includes label items in `questions`; the results exports skip them.

## Known improvement candidates

- N+1 queries in the two results exports (one `Response` query per question).
- Aggregation logic duplicated between `exportAsJSONResults` and `exportAsCSVResults` (candidate for a shared service).
- A `respondents` field per question would disambiguate checkbox percentages.
