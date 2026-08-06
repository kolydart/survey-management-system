## Laravel Boost MCP tools
- `search-docs` — **use before any Laravel-ecosystem code change.** Version-specific docs for the installed packages. Pass several broad topic queries at once (`['rate limiting', 'routing']`); never put package names in the query.
- `list-artisan-commands` (verify parameters), `database-query` (reads), `tinker` (execute PHP), `get-absolute-url` (before sharing any project URL)@if (config('boost.browser_logs', true) !== false || config('boost.browser_logs_watcher', true) !== false), `browser-logs` (recent browser errors)@endif.
