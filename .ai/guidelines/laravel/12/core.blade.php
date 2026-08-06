## Laravel 12
- Check whether this app uses the streamlined Laravel 11+ skeleton (`bootstrap/app.php` for middleware, exceptions, routing; `routes/console.php` for scheduling) or the older Laravel 10 layout (`app/Http/Kernel.php`, `app/Exceptions/Handler.php`, `app/Console/Kernel.php`). Both are supported — follow whichever the app already uses; do not migrate without a request.
- A column-modifying migration must repeat every previously defined attribute, or they are dropped.
