<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error Notification</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 5px;">
        <h2 style="color: #d32f2f; margin-bottom: 20px;">System Error Notification</h2>
        
        <div style="background-color: #ffebee; padding: 15px; border-left: 4px solid #d32f2f; margin-bottom: 20px;">
            <strong>Error Message:</strong><br>
            <pre style="white-space: pre-wrap; margin-top: 10px; font-family: monospace;">{{ $errorMessage }}</pre>
        </div>
        
        <div style="margin-bottom: 15px;">
            <strong>Timestamp:</strong> {{ $timestamp }}
        </div>
        
        <div style="margin-bottom: 15px;">
            <strong>URL:</strong> {{ $url }}
        </div>
        
        <hr style="margin: 20px 0; border: none; height: 1px; background-color: #ddd;">
        
        <p style="color: #666; font-size: 12px; margin: 0;">
            This is an automated error notification from the survey system.
        </p>
    </div>
</body>
</html>