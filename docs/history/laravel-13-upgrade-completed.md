# Αναβάθμιση Laravel 12 → 13 (ολοκληρωμένη)

**Ημερομηνία:** 2026-08-07
**Από:** Laravel 12.64.0 / PHPUnit 11.5.56
**Σε:** Laravel 13.24.0 / PHPUnit 13.2.6
**PHP:** 8.4.24 (αμετάβλητη· η Laravel 13 απαιτεί `^8.3`)

## Εξαρτήσεις

| Πακέτο | Πριν | Μετά | Σχόλιο |
|---|---|---|---|
| `laravel/framework` | `^12.0` | `^13.0` | v13.24.0 |
| `laravel/tinker` | `^2.9` | `^3.0` | απαίτηση του upgrade guide |
| `phpunit/phpunit` | `^11.5.3` | `^13.2` | ο guide όριζε `^12.0`· προχωρήσαμε σε 13.2.6 (βλ. παρακάτω) |
| `brianium/paratest` | `^7.4` | `^7.23` | το 7.23.x είναι η σειρά που υποστηρίζει PHPUnit 13 (v7.23.1) |
| `laravel/boost` | `^1.0` | `^2.0` | απαίτηση του upgrade guide |
| `askedio/laravel-soft-cascade` | `^12.0` | `^13.0` | καμία έκδοση 12.x δεν υποστηρίζει L13 |
| `yajra/laravel-datatables-oracle` | `^12.0` | `^13.0` | καμία έκδοση 12.x δεν υποστηρίζει L13 |
| `spatie/laravel-activitylog` | `^4.8` | `^4.12` | **δεν** έγινε μετάβαση σε v5 (βλ. παρακάτω) |

Αμετάβλητα, επειδή οι τρέχουσες εκδόσεις υποστηρίζουν ήδη Laravel 13: `laravel/sanctum ^4.0`, `laravel/ui ^4.0`, `laravel/dusk ^8.6`, `sentry/sentry-laravel ^4.3`, `intervention/image-laravel ^1.0`, `nunomaduro/collision ^8.6`, `spatie/laravel-ignition ^2.0`, `barryvdh/*`, `beyondcode/laravel-query-detector`, `jasonmccreary/laravel-test-assertions`.

Τα in-house `kolydart/laravel` και `kolydart/common` δηλώνουν τα `illuminate/*` μόνο σε `require-dev`, οπότε δεν εμπόδισαν την επίλυση.

### Γιατί μείναμε στο activitylog v4

Το `spatie/laravel-activitylog` **4.12.0 πρόσθεσε υποστήριξη Laravel 13**. Το v5 δεν απαιτείται και θα επέβαλλε: αλλαγή σχήματος στον πίνακα `activity_log` (νέα στήλη `attribute_changes`, κατάργηση `batch_uuid`, migration δεδομένων), μετακίνηση namespace για `LogsActivity`/`LogOptions`/`CauserResolver`, κατάργηση του batch system και μετονομασίες μεθόδων. Η μετάβαση σε v5 παραμένει ξεχωριστή, προαιρετική εργασία.

## Αλλαγές κώδικα

### 1. CSRF → `PreventRequestForgery` (High impact)

Το framework μετονόμασε το `VerifyCsrfToken` σε `PreventRequestForgery` και πρόσθεσε επαλήθευση προέλευσης μέσω του header `Sec-Fetch-Site`.

- `app/Http/Middleware/VerifyCsrfToken.php` → `app/Http/Middleware/PreventRequestForgery.php` (επεκτείνει πλέον το `Illuminate\Foundation\Http\Middleware\PreventRequestForgery`)
- Ενημερώθηκε η αναφορά στο `app/Http/Kernel.php`

Προσοχή στη σημασιολογική αλλαγή: το `PreventRequestForgery::handle()` δέχεται οποιοδήποτε request φέρει `Sec-Fetch-Site: same-origin` **χωρίς να ελέγξει καθόλου το CSRF token**. Είναι σχεδιαστική επιλογή της Laravel και ο πίνακας `$except` παραμένει κενός, οπότε δεν υπάρχει regression — αλλά δεν πρόκειται απλώς για «προσθήκη» επαλήθευσης προέλευσης.

Τα `withoutMiddleware(...)` σε `tests/TestCase.php` και `tests/Feature/app/Http/Controllers/Auth/LoginTest.php` αφαιρέθηκαν: το middleware κάνει ήδη short-circuit μέσω `runningUnitTests()`, οπότε ήταν νεκρό scaffolding.

Το `config/sanctum.php` εξακολουθεί να δείχνει στο `ValidateCsrfToken::class` — αυτό είναι το shipped default του ίδιου του Sanctum v4 και αφέθηκε ως έχει.

### 2. `cache.serializable_classes` (Medium impact)

Προστέθηκε το `serializable_classes` στο `config/cache.php`. Σκληραίνει το unserialize του cache έναντι gadget-chain επιθέσεων σε περίπτωση διαρροής του `APP_KEY`.

**Το default της Laravel 13 (`false`) δεν είναι ασφαλές για αυτή την εφαρμογή.** Η πρώτη εκδοχή αυτού του κειμένου ισχυριζόταν ότι οι μόνες εγγραφές στο cache είναι απλοί πίνακες (`SurveyStatisticsService::getStatisticsWithCache`, `ChartDataService::generateChartDataWithCache`) — ο έλεγχος όμως είχε παραλείψει το in-house `vendor/kolydart/laravel`:

- `Providers/RoleServiceProvider::getRoles()` κάνει cache το `Role::all()` — δηλαδή ένα `Illuminate\Database\Eloquent\Collection` — για μία ώρα, από το `boot()`, σε κάθε request και σε κάθε environment.
- Με `false` η ανάγνωση επιστρέφει `__PHP_Incomplete_Class`. Το `foreach` παράγει τότε σκέτα strings, το `$role->title` γίνεται `ErrorException`, και το περιβάλλον `catch (\Exception $e)` το καταπίνει — με log μόνο όταν είναι ενεργό το `app.debug`.
- Μετρημένο αποτέλεσμα: με άδειο cache δηλώνονται 86 gates· με ζεστό cache τα `admin`, `moderator`, `user` εξαφανίζονται σιωπηλά.

Καμία διαδρομή ή view του repo δεν χρησιμοποιεί σήμερα αυτά τα τρία ονόματα και η αστοχία είναι fail-closed (403 / κρυμμένο UI), οπότε τίποτα δεν έσπασε — αλλά η απόκλιση εξαρτιόταν από το αν το cache ήταν ζεστό, κάτι που θα χτυπούσε τον επόμενο που θα έγραφε `can:admin`.

Η ρύθμιση δηλώνει πλέον ρητή allowlist αντί για `false`:

```php
'serializable_classes' => [
    Illuminate\Database\Eloquent\Collection::class,
    App\Role::class,
],
```

`AccessControl::getPermissionsArray()` — η άλλη εγγραφή cache του kolydart — επιστρέφει απλό πίνακα και δεν επηρεάζεται. Αν κάποτε αποθηκευτούν άλλα αντικείμενα PHP στο cache, οι κλάσεις τους πρέπει να προστεθούν σε αυτόν τον πίνακα.

### 3. PHPUnit 12/13: annotations → attributes

Η PHPUnit 12 **κατάργησε τα metadata σε doc-comments**. Τα 357 `/** @test */` σε 64 αρχεία έπαψαν να ανιχνεύονται σιωπηλά — το suite έτρεχε μόνο 91 από 346 tests χωρίς να αποτύχει.

Μετατράπηκαν σε `#[Test]` attributes με import `PHPUnit\Framework\Attributes\Test`. Τα docblocks που περιείχαν πραγματική περιγραφή διατηρήθηκαν· τα κενά αφαιρέθηκαν. Τα `use` statements ταξινομήθηκαν αλφαβητικά στα αρχεία που άγγιξε η μετατροπή.

Τελικά επιλέχθηκε PHPUnit `^13.2` (13.2.6) αντί για το `^12.0` του guide: το framework υποστηρίζει `^13.0.3` και το `brianium/paratest ^7.23` (v7.23.1) είναι η σειρά που ταιριάζει.

### 4. `array_sum()` σε μη αριθμητικά strings

Από PHP 8.3 η `array_sum()` προειδοποιεί για μη αριθμητικά strings, και ο error handler της Laravel μετατρέπει την προειδοποίηση σε `ErrorException`. Τρία tests στο `tests/Unit/Services/SurveyStatisticsServiceTest.php` βασίζονταν στην παλιά σιωπηλή συμπεριφορά (`array_sum(['a','b','c']) === 0`) και απέτυχαν.

Ξαναγράφτηκαν ώστε να ελέγχουν τον κώδικα της εφαρμογής αντί για builtins της PHP: το `SurveyStatisticsService::calculateStatistics()` φιλτράρει μέσω `filterNumericData()` πριν αθροίσει, οπότε τα tests επαληθεύουν πλέον ότι επιστρέφει `null` για δεδομένα χωρίς αριθμούς και ότι σε μεικτά δεδομένα το `count` αγνοεί τα μη αριθμητικά ενώ το `raw_count` τα μετράει. Η νέα συμπεριφορά της `array_sum()` τεκμηριώνεται ξεχωριστά με `expectException(ErrorException::class)`.

## Σημεία του guide που ελέγχθηκαν και δεν απαιτούσαν αλλαγή

- **`upsert` με MySQL/MariaDB**: `RoleSeed` και `UserSeed` περνούν μη κενό `uniqueBy` — δεν πυροδοτείται το νέο `InvalidArgumentException`.
- **Cache prefixes / session cookie names**: `config/cache.php`, `config/session.php` και `config/database.php` ορίζουν ήδη ρητά τα prefixes σε επίπεδο εφαρμογής, οπότε τα νέα framework-level defaults με παύλες δεν εφαρμόζονται.
- **Symfony PHP 8.5 polyfill**: δεν υπάρχουν custom `array_first()` / `array_last()` global helpers που να συγκρούονται.
- **Domain route precedence**: δεν χρησιμοποιούνται domain routes.
- **Model booting / nested instantiation**: δεν υπάρχουν `boot()` ή `boot*()` μέθοδοι σε models.
- **`JobAttempted` / `QueueBusy` events**, **pagination view names**, **`Js::from`**, **`Manager::extend`**, **Str factories σε tests**, **`Container::call`**: καμία χρήση.

## Επαλήθευση

| Έλεγχος | Αποτέλεσμα |
|---|---|
| `cphp artisan test` πριν | 343 passed, 3 failed |
| `cphp artisan test` μετά | **349 passed, 0 failed** (990 assertions) |
| Gates με άδειο vs. ζεστό cache | 86 και στις δύο περιπτώσεις — βλ. §2 |
| `cphp artisan dusk` | **5 passed** (67 assertions) — smoke σε όλα τα admin index/show/edit/create routes και στο frontend |
| `composer audit` | καθαρό |
| Playwright: `/login` POST | σφάλμα διαπιστευτηρίων, **όχι 419** → το `PreventRequestForgery` δέχεται κανονικά browser POST |
| Playwright: `/` και `/digital-score-final` | αποδίδονται πλήρως, μηδέν console errors |

Τα 343 → 349 δεν είναι απλή αύξηση: πριν τη μετατροπή σε attributes το suite εκτελούσε μόνο 91 από 346 tests. Πλέον τρέχουν όλα.

## Νέα tests

- `ItemsControllerTest::index_ajax_returns_datatables_payload` — χτυπά το πραγματικό XHR endpoint των DataTables και επαληθεύει τη δομή του payload, το `recordsFiltered == recordsTotal` και το rendering της στήλης `actions`, καλύπτοντας την αναβάθμιση yajra v12 → v13.
- `SurveyStatisticsServiceTest::test_service_returns_null_for_non_numeric_data` και `::test_array_sum_rejects_non_numeric_strings` — βλ. §4.

## Εκκρεμότητες / προαιρετικά

- **`spatie/laravel-activitylog` v5**: ξεχωριστή εργασία, απαιτεί migration σχήματος και δεδομένων.
- **`RoleServiceProvider::getRoles()`**: το `serializable_classes` λύθηκε από την πλευρά της εφαρμογής με allowlist. Η καθαρότερη λύση είναι το ίδιο το `vendor/kolydart/laravel` να μη βάζει Eloquent Collection στο cache (π.χ. `->toArray()`), αλλά αυτό αφορά χωριστό repo και επηρεάζει και άλλα projects.
- **Frontend assets**: δεν ξαναχτίστηκαν — καμία πηγή JS/CSS δεν άλλαξε από την αναβάθμιση (το project χρησιμοποιεί Laravel Mix).
- **Deploy**: το `serializable_classes` επηρεάζει το unserialize του cache. Καθαρίστε το cache μετά το deploy (`cphp artisan optimize:clear`) — οι παλιές εγγραφές γράφτηκαν με άλλους κανόνες.
