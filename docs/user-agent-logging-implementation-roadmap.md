# Roadmap: Implement `jenssegers/agent` to Populate `loguseragents` Table

## Context

The `loguseragents` table exists with the following fields: `os`, `os_version`, `browser`, `browser_version`, `device`, `language`, `item_id`, `ipv6`, `uri`, `form_submitted`, `user_id`. The `jenssegers/agent` package was previously installed in `composer.json` but was removed because it was not being used anywhere in the codebase. Previously, another package handled this functionality but is no longer supported.

The table has useful indexes for querying:
- `idx_loguseragents_ipv6` - for IP-based lookups
- `idx_loguseragents_ip_os` - for IP + OS combined queries
- `idx_loguseragents_browser` - for browser + browser_version queries
- `idx_loguseragents_item_id` - for linking to questionnaires

## Current State

### What Works
- `loguseragents` table exists and is functional
- `Loguseragent` model exists at `app/Loguseragent.php`
- Admin CRUD interface exists for viewing/managing logs
- Activity logging using Spatie ActivityLog captures user agent strings in properties

### What's Missing
- No automatic population of `loguseragents` table
- No parsing of user agent strings into structured data
- No integration with survey view/submission flows
- `jenssegers/agent` package needs to be installed

## Implementation Plan

### 0. Install Required Package

**Before starting implementation**, install the `jenssegers/agent` package:

```bash
composer require jenssegers/agent
```

This package provides robust user agent parsing capabilities and is actively maintained. It's based on the popular Mobiledetect library and will be used to parse user agent strings into structured data (OS, browser, device type, etc.).

**Package Info**:
- **Package**: `jenssegers/agent`
- **Version**: `^2.6` (or latest stable)
- **Documentation**: https://github.com/jenssegers/agent
- **Purpose**: Parse user agent strings and detect browsers, operating systems, devices, and languages

### 1. Create UserAgent Logging Service

**File**: `app/Services/UserAgentLoggingService.php`

Create a service class to encapsulate user agent logging logic:

- Use `Jenssegers\Agent\Agent` to parse user agent strings
- Extract structured data:
  - OS name and version
  - Browser name and version
  - Device type (desktop, mobile, tablet, robot)
  - Languages from Accept-Language header
- Create `Loguseragent` records with all relevant data
- Provide methods for different logging contexts:
  - `logSurveyView()` - Log when a survey is viewed
  - `logFormSubmission()` - Log when a questionnaire is submitted
  - `logFromRequest()` - Generic logging from HTTP request

**Key Methods**:
```php
public function logFromRequest(Request $request, array $additionalData = []): Loguseragent
public function logSurveyView(Request $request, int $surveyId): Loguseragent
public function logFormSubmission(Request $request, Questionnaire $questionnaire): Loguseragent
```

### 2. Create Middleware for Automatic User Agent Logging

**File**: `app/Http/Middleware/LogUserAgent.php`

Create middleware to automatically log user agent data on specific routes:

- Inject `UserAgentLoggingService`
- Log user agent data for matched routes
- Capture: IP address, URI, user agent details, user_id (if authenticated)
- Store the created `Loguseragent` ID in the request for later use
- Make logging optional/configurable via config file
- Apply selectively to frontend/public routes only (not admin routes)

**Configuration**:
- Add `config/useragent.php` to control logging behavior
- Enable/disable logging globally
- Whitelist/blacklist specific routes or patterns
- Control what gets logged (IP, user_agent, etc.)

### 3. Register Middleware

**File**: `app/Http/Kernel.php`

Register the new middleware:

- Add to `$middlewareAliases` as `'log.useragent'` for selective use
- **OR** add to `'web'` middleware group for automatic application to all web routes
- Consider route-specific application in `routes/web.php` for frontend routes only

**Recommended Approach**: Apply as route middleware to frontend routes only:
```php
Route::middleware(['log.useragent'])->group(function () {
    Route::get('/', 'Frontend\CollectController@index')->name('index');
    Route::get('/{alias}', 'Frontend\CollectController@create')->name('create');
    Route::post('/store', 'Frontend\CollectController@store')->name('store');
});
```

### 4. Integrate in CollectController

**File**: `app/Http/Controllers/Frontend/CollectController.php`

Update the controller to work with user agent logging:

#### In `create()` method (survey view):
- Log user agent when survey is viewed
- Set `form_submitted = false`
- Store survey ID in a relevant field (or use `item_id` for questionnaire after submission)

#### In `store()` method (form submission):
- Log user agent with `form_submitted = true`
- Link log entry to the questionnaire via `item_id` field
- Include the questionnaire ID for later duplicate detection

**Alternative Approach**: If middleware handles logging, just retrieve the `Loguseragent` ID from request and update it:
```php
// After questionnaire is created
if ($loguseragentId = $request->get('loguseragent_id')) {
    Loguseragent::find($loguseragentId)->update([
        'form_submitted' => true,
        'item_id' => $questionnaire->id,
    ]);
}
```

### 5. Update DuplicateDetectionService

**File**: `app/Services/DuplicateDetectionService.php`

Add new duplicate detection method using `loguseragents` table:

#### New Method: `findByLoguseragents()`
- Query `loguseragents` table for duplicates based on IP + User Agent fingerprinting
- Utilize existing indexes for performance:
  - `idx_loguseragents_ipv6` for IP-based queries
  - `idx_loguseragents_ip_os` for IP + OS combinations
  - `idx_loguseragents_browser` for browser fingerprinting
- Group by `ipv6` + `browser` + `browser_version` to find potential duplicates
- Filter for `form_submitted = true` to only check actual submissions
- Return results in same format as `findByActivityLog()` for consistency

#### Benefits:
- Provides alternative to activity log-based detection
- Uses optimized database indexes for faster queries
- More structured data (parsed OS/browser) vs raw user agent strings
- Can be combined with other detection methods for higher confidence

#### Maintain Backward Compatibility:
- Keep existing `findByActivityLog()` method
- Consider adding a `findAllDuplicates()` method that combines both approaches
- Document the differences between detection methods

### 6. Create/Update Tests

Create comprehensive tests for all new functionality:

#### **File**: `tests/Unit/Services/UserAgentLoggingServiceTest.php` (new)
- Test user agent parsing accuracy
- Test `Loguseragent` record creation
- Test different device types (mobile, desktop, tablet, robot)
- Test different browsers and OS combinations
- Test language extraction
- Mock the `Agent` class for consistent test data

#### **File**: `tests/Unit/Http/Middleware/LogUserAgentTest.php` (new)
- Test middleware creates log entries
- Test middleware skips logging when disabled
- Test middleware handles authenticated vs guest users
- Test request attribute is set correctly

#### **File**: `tests/Feature/app/Http/Controllers/Frontend/CollectControllerTest.php` (update or create)
- Test survey view creates loguseragent entry
- Test form submission updates loguseragent entry with `form_submitted = true`
- Test loguseragent is linked to questionnaire via `item_id`
- Verify integration with middleware

#### **File**: `tests/Unit/Services/DuplicateDetectionServiceTest.php` (update existing)
- Test new `findByLoguseragents()` method
- Test duplicate detection with different scenarios:
  - Same IP + same browser = duplicate
  - Same IP + different browser = not duplicate
  - Different IP + same browser = not duplicate
- Test performance with large datasets
- Compare results with `findByActivityLog()` method

### 7. Update Factory

**File**: `database/factories/LoguseragentFactory.php`

Review and update the factory to generate realistic test data:

- Use realistic user agent strings (not just random text)
- Generate proper OS names and versions (Windows 10, macOS 14, iOS 17, Android 13, etc.)
- Generate proper browser names and versions (Chrome 120, Firefox 121, Safari 17, etc.)
- Generate realistic device types (desktop, mobile, tablet)
- Use proper IP address formats (IPv4 and IPv6)
- Generate realistic URIs based on survey aliases
- Vary `form_submitted` boolean appropriately

**Consider using**: Pre-defined arrays of common user agent strings for realistic data:
```php
protected static $userAgents = [
    'Chrome on Windows' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)...',
    'Safari on iPhone' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0...)...',
    // etc.
];
```

### 8. Documentation

**File**: `docs/user-agent-logging.md` (new)

Create comprehensive documentation covering:

#### Overview
- Purpose of user agent logging
- How `jenssegers/agent` package is used
- Data collected and stored

#### Architecture
- Service layer (`UserAgentLoggingService`)
- Middleware layer (`LogUserAgent`)
- Database schema (`loguseragents` table)
- Indexes and performance considerations

#### Configuration
- How to enable/disable logging
- Route-specific vs global logging
- Configuration options in `config/useragent.php`

#### Duplicate Detection
- How loguseragents are used for duplicate detection
- Comparison with activity log method
- When to use which method
- Performance considerations

#### Privacy & Security
- What data is collected
- Data retention policies
- GDPR/privacy compliance considerations
- IP address storage and anonymization options

#### Developer Guide
- How to log user agents programmatically
- How to query loguseragents for analytics
- How to extend the service with custom logic
- Examples and code snippets

### 9. Optional: Admin Dashboard Enhancement

**File**: `app/Http/Controllers/Admin/LoguseragentsController.php`

Enhance the existing admin interface:

#### Filtering Capabilities
- Filter by browser (Chrome, Firefox, Safari, etc.)
- Filter by OS (Windows, macOS, iOS, Android, Linux)
- Filter by device type (desktop, mobile, tablet, robot)
- Filter by date range
- Filter by form submission status
- Filter by specific survey/questionnaire

#### Statistics & Analytics
- Add summary statistics (top browsers, top OS, device breakdown)
- Add charts using Chart.js or similar:
  - Browser distribution pie chart
  - OS distribution bar chart
  - Device type breakdown
  - Submissions over time
- Add export functionality (CSV, Excel) for analytics

#### DataTables Enhancements
- Better formatting for IP addresses
- Add flags or icons for OS/browser types
- Color coding for device types
- Link to related questionnaire
- Show parsed vs raw user agent string

#### Dashboard Widget
- Add a widget to `admin.home` showing recent user agent activity
- Show summary stats (total views, submissions, unique IPs)

### 10. Configuration File

**File**: `config/useragent.php` (new)

Create configuration file for flexible control:

```php
return [
    // Enable/disable user agent logging globally
    'enabled' => env('USERAGENT_LOGGING_ENABLED', true),

    // Log only on specific routes (array of route names or patterns)
    'routes' => [
        'create',
        'store',
        'index',
    ],

    // Exclude specific routes from logging
    'exclude_routes' => [
        'admin.*',
    ],

    // What data to capture
    'capture' => [
        'ip' => true,
        'uri' => true,
        'user_agent' => true,
        'language' => true,
    ],

    // IP anonymization (for GDPR compliance)
    'anonymize_ip' => env('USERAGENT_ANONYMIZE_IP', false),

    // Data retention in days (0 = keep forever)
    'retention_days' => env('USERAGENT_RETENTION_DAYS', 0),
];
```

## Implementation Order (Recommended)

1. **Install Package**: Run `composer require jenssegers/agent` to install the required dependency
2. **Start with Service Layer**: Create `UserAgentLoggingService` first, with tests
3. **Add Configuration**: Create `config/useragent.php` for flexibility
4. **Create Middleware**: Implement `LogUserAgent` middleware
5. **Register & Test Middleware**: Register in Kernel, test with routes
6. **Integrate Controller**: Update `CollectController` to use the service
7. **Update Duplicate Detection**: Add `findByLoguseragents()` method
8. **Update Factory**: Ensure factory generates realistic test data
9. **Write Comprehensive Tests**: Cover all new functionality
10. **Run All Tests**: Ensure nothing breaks
11. **Documentation**: Write user and developer documentation
12. **Optional Enhancements**: Dashboard improvements, analytics

## Testing Strategy

### Unit Tests
- Test `UserAgentLoggingService` methods in isolation
- Mock the `Agent` class for predictable results
- Test edge cases (empty user agent, bot detection, etc.)

### Feature Tests
- Test full request lifecycle (view survey → submit form)
- Verify `loguseragents` records are created correctly
- Test middleware integration
- Test duplicate detection end-to-end

### Manual Testing Checklist
- [ ] View a survey as guest - check loguseragent is created
- [ ] Submit a survey - check `form_submitted` is set to true
- [ ] Check with different browsers (Chrome, Firefox, Safari)
- [ ] Check with mobile devices (iOS, Android)
- [ ] Check admin interface shows parsed data correctly
- [ ] Test duplicate detection using loguseragents table
- [ ] Test with VPN/different IPs
- [ ] Test with bots/crawlers

## Key Benefits

✅ **Uses Existing Infrastructure**: Leverages existing table structure and indexes
✅ **Maintained Package**: `jenssegers/agent` is actively maintained
✅ **Non-Breaking Changes**: Adds functionality without removing existing features
✅ **Improved Analytics**: Provides structured data about survey respondents
✅ **Better Duplicate Detection**: More reliable than raw user agent strings
✅ **Performance**: Uses database indexes for fast queries
✅ **Flexibility**: Configuration-driven, easy to enable/disable
✅ **Privacy-Conscious**: Can anonymize IPs, configure data retention
✅ **Well-Tested**: Comprehensive test coverage ensures reliability

## Potential Challenges & Solutions

### Challenge 1: Performance Impact
**Issue**: Logging on every request might slow down survey submissions
**Solutions**:
- Make logging asynchronous using queued jobs
- Add database indexes (already exist)
- Cache parsed user agent data
- Make logging optional via configuration

### Challenge 2: Bot Traffic
**Issue**: Bots/crawlers might pollute analytics data
**Solutions**:
- Use `jenssegers/agent` robot detection
- Add `is_robot` boolean field to table (future enhancement)
- Filter bots from analytics views
- Option to skip logging for detected bots

### Challenge 3: Privacy Compliance (GDPR)
**Issue**: Storing IP addresses may require compliance measures
**Solutions**:
- Add IP anonymization option (mask last octet)
- Implement data retention policy (auto-delete old records)
- Document what data is collected and why
- Add consent mechanisms if required
- Consider hashing IPs instead of storing plain text

### Challenge 4: Data Volume
**Issue**: High-traffic surveys generate many log entries
**Solutions**:
- Implement data retention policy (delete old logs)
- Add pagination to admin interface
- Use efficient indexes for queries
- Consider summary/aggregation tables for analytics
- Add archiving functionality for old data

## Future Enhancements (Out of Scope)

- Real-time analytics dashboard using WebSockets
- Machine learning for advanced duplicate detection
- Geolocation from IP addresses (using MaxMind GeoIP)
- User journey tracking (view → submit timeline)
- A/B testing based on device/browser
- Heatmaps and interaction tracking
- Integration with Google Analytics
- Alert system for suspicious patterns

## Decision Points

Before implementing, decide on:

1. **Middleware vs Manual Logging**: Should logging be automatic (middleware) or explicit (controller)?
   - **Recommendation**: Use middleware for automatic logging, more maintainable

2. **Route Scope**: Log all web routes or only frontend routes?
   - **Recommendation**: Only frontend routes (surveys/submissions), exclude admin

3. **IP Storage**: Store raw IPs or anonymized?
   - **Recommendation**: Configurable, default to raw for duplicate detection

4. **Data Retention**: Keep forever or auto-delete?
   - **Recommendation**: Configurable, default to keep for analytics

5. **Package Justification**: Keep `jenssegers/agent` or find alternative?
   - **Recommendation**: Keep it, it's well-maintained and purpose-built

## Conclusion

This implementation provides a robust, maintainable solution for user agent logging that integrates seamlessly with the existing application architecture. By following this roadmap, you'll gain valuable analytics data, improve duplicate detection, and justify keeping the `jenssegers/agent` package in your dependencies.

The phased approach ensures each component can be developed, tested, and verified independently, reducing risk and making the implementation more manageable.
