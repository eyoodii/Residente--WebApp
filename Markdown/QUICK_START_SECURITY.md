# Quick Start Guide - Enterprise Security Features

## 1. Using Toast Notifications

### In Your Layout File
Add the toast component before the closing `</body>` tag:

```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <!-- Your head content -->
</head>
<body>
    <!-- Your page content -->
    
    {{-- Add toast notifications --}}
    <x-toast />
    
    <!-- Your scripts -->
</body>
</html>
```

### In Controllers
Use these session flash messages:

```php
// Success
return redirect()->back()
    ->with('toast_success', 'Your message here');

// Error
return redirect()->back()
    ->with('toast_error', 'Error message');

// Warning
return redirect()->back()
    ->with('toast_warning', 'Warning message');

// Info
return redirect()->back()
    ->with('toast_info', 'Info message');
```

---

## 2. Using Policy Authorization

### In Controller Methods

```php
use App\Models\ServiceRequest;

public function showRequest($requestNumber)
{
    $serviceRequest = ServiceRequest::where('request_number', $requestNumber)
        ->firstOrFail();
    
    // Automatically blocks unauthorized access
    $this->authorize('view', $serviceRequest);
    
    // Continue with your logic...
}
```

### Available Policy Methods

```php
// Check if user can view
$this->authorize('view', $serviceRequest);

// Check if user can download
$this->authorize('download', $serviceRequest);

// Check if user can create
$this->authorize('create', ServiceRequest::class);

// Check if user can update
$this->authorize('update', $serviceRequest);
```

---

## 3. Using Action Classes

### In Controller

```php
use App\Actions\ProcessServiceRequest;

public function submitRequest(Request $request, ProcessServiceRequest $action)
{
    // Validate input
    $validated = $request->validate([...]);
    
    // Let action class handle business logic
    $serviceRequest = $action->execute(
        auth()->user(),
        $validated
    );
    
    // Return with toast notification
    return redirect()->route('dashboard')
        ->with('toast_success', 'Request submitted!');
}
```

### Creating Your Own Action Class

```php
<?php

namespace App\Actions;

use App\Models\Resident;

class YourCustomAction
{
    public function execute(Resident $resident, array $data)
    {
        // Your business logic here
        // 1. Create records
        // 2. Send notifications
        // 3. Log activities
        
        return $result;
    }
}
```

---

## 4. Secure File Uploads

### Upload Method

```php
public function uploadDocument(Request $request)
{
    // Strict validation
    $validated = $request->validate([
        'document' => [
            'required',
            'file',
            'mimes:pdf,jpg,png',
            'max:5120', // 5MB
        ],
    ]);
    
    // Store in PRIVATE disk (not publicly accessible)
    $path = $request->file('document')->store(
        'service_requests',
        'private'  // ← Important!
    );
    
    return back()->with('toast_success', 'File uploaded securely');
}
```

### Download Method

```php
use Illuminate\Support\Facades\Storage;

public function downloadDocument($id)
{
    $document = Document::findOrFail($id);
    
    // Check authorization
    $this->authorize('download', $document->serviceRequest);
    
    // Serve from private storage
    return Storage::disk('private')->download(
        $document->file_path,
        $document->file_name
    );
}
```

---

## 5. Testing Your Implementation

### Test IDOR Protection

1. Create two test users
2. Login as User A
3. Note a service request ID
4. Login as User B
5. Try to access User A's request
6. Should get 403 Forbidden

### Test File Security

1. Upload a document
2. Note the storage path
3. Try to access it directly via URL
4. Should get 404 Not Found
5. Use the download route instead
6. Should download if authorized

### Test Policies

```bash
php artisan tinker
```

```php
$resident = Resident::find(1);
$request = ServiceRequest::find(1);

// Test policy
Gate::forUser($resident)->allows('view', $request);
```

---

## 6. Common Patterns

### Pattern 1: Form Submission with Validation

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'field' => 'required|string|max:255',
    ]);
    
    try {
        // Your logic
        
        return back()->with('toast_success', 'Success!');
    } catch (\Exception $e) {
        Log::error('Error', ['error' => $e->getMessage()]);
        return back()->with('toast_error', 'Failed!');
    }
}
```

### Pattern 2: Authorized Resource Access

```php
public function show($id)
{
    $resource = Resource::findOrFail($id);
    $this->authorize('view', $resource);
    
    return view('resource.show', compact('resource'));
}
```

### Pattern 3: Secure File Operations

```php
public function processFile(Request $request)
{
    // Validate
    $request->validate([
        'file' => 'required|file|mimes:pdf|max:5120',
    ]);
    
    // Store privately
    $path = $request->file('file')->store('folder', 'private');
    
    // Save reference
    Document::create(['path' => $path]);
    
    return back()->with('toast_success', 'File saved securely');
}
```

---

## 7. Troubleshooting

### Toast Not Showing?

1. Ensure `<x-toast />` is in your layout
2. Check if Alpine.js is loaded
3. Verify session flash is working:
   ```php
   dd(session()->all());
   ```

### Policy Not Working?

1. Check if policy is registered
2. Verify method name matches
3. Test with:
   ```php
   php artisan policy:show
   ```

### Files Not Uploading?

1. Check PHP upload limits: `php.ini`
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
2. Verify storage permissions
3. Check disk configuration in `config/filesystems.php`

---

## 8. Quick Reference

### Session Flash Types
- `toast_success` - Green, success icon
- `toast_error` - Red, error icon
- `toast_warning` - Yellow, warning icon
- `toast_info` - Blue, info icon

### Policy Methods
- `viewAny()` - List/index pages
- `view()` - Show single resource
- `create()` - Create new resource
- `update()` - Update existing
- `delete()` - Delete resource
- `download()` - Custom: Download files

### Storage Disks
- `public` - Web-accessible via /storage
- `private` - Not web-accessible, secure
- `local` - Alias for private

---

## Need Help?

Check the full documentation:
- [ENTERPRISE_SECURITY_GUIDE.md](ENTERPRISE_SECURITY_GUIDE.md)
- [BACKEND_ARCHITECTURE.md](BACKEND_ARCHITECTURE.md)
- [ID_SCANNER_SETUP.md](ID_SCANNER_SETUP.md)

**Happy Coding! 🚀**
