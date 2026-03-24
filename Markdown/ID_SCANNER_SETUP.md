# ID Scanner Setup Guide

## Overview
This ID Scanner feature uses EasyOCR (a Python-based OCR library) to extract information from Philippine ID documents, driver's licenses, and passports.

## Prerequisites

### 1. Python Installation
- **Python 3.8 or higher** is required
- Download from: https://www.python.org/downloads/

#### Windows Installation
```powershell
# Check if Python is installed
python --version

# If not installed, download and install Python 3.8+
# Make sure to check "Add Python to PATH" during installation
```

### 2. Install Python Dependencies

Navigate to the project scripts directory and install required packages:

```powershell
cd c:\MyXampp\htdocs\ResidenteWebApp\scripts
pip install -r requirements.txt
```

**Note:** The first installation may take 5-10 minutes as it downloads OCR models (approximately 500MB).

### 3. Test Python Script

Test the OCR script manually:

```powershell
python scripts/id_scanner.py "path/to/test/id_image.jpg"
```

You should see JSON output with extracted text.

## Laravel Setup

### 1. Run Database Migration

```powershell
php artisan migrate
```

This creates the `scanned_documents` table.

### 2. Create Storage Symlink

Ensure the storage link exists for serving uploaded images:

```powershell
php artisan storage:link
```

### 3. Compile Assets

If you've modified the JavaScript:

```powershell
npm run build
# or for development
npm run dev
```

### 4. Set Permissions (Linux/Mac only)

```bash
chmod +x scripts/id_scanner.py
chmod -R 775 storage/app/public
```

## Usage

### Admin Interface

1. Log in as an admin
2. Navigate to: `/admin/id-scanner`
3. Upload an ID document (JPG, PNG, max 10MB)
4. View extracted information
5. Verify and approve/reject the scan

### API Endpoints

#### Scan Document
```
POST /admin/id-scanner/scan
Content-Type: multipart/form-data

Fields:
- document: (file) Image file
- document_type: (optional) id_card|passport|driver_license
- resident_id: (optional) Link to resident
```

#### Get Scanned Documents
```
GET /admin/id-scanner
Query Parameters:
- resident_id: Filter by resident
- status: Filter by verification status
```

#### Update Status
```
PATCH /admin/id-scanner/{id}/status
Content-Type: application/json

{
  "status": "verified|rejected|pending",
  "notes": "Optional notes"
}
```

#### Auto-Fill Form
```
POST /admin/id-scanner/auto-fill
Content-Type: application/json

{
  "document_id": 123
}
```

## Troubleshooting

### Issue: "Python not found"
**Solution:** Add Python to your system PATH
```powershell
# Windows: Edit environment variables and add Python installation path
# Example: C:\Python310\
```

### Issue: "Module 'easyocr' not found"
**Solution:** Install dependencies
```powershell
pip install -r scripts/requirements.txt
```

### Issue: OCR returns empty results
**Possible causes:**
- Poor image quality
- Glare or shadows on document
- Document not in focus
- Unsupported language/format

**Solutions:**
- Use better lighting
- Avoid reflections
- Ensure document fills the frame
- Use higher resolution images

### Issue: "Failed to process document"
**Check logs:**
```powershell
# View Laravel logs
tail -f storage/logs/laravel.log

# Test Python script directly
python scripts/id_scanner.py "path/to/image.jpg"
```

## Performance Optimization

### 1. GPU Support (Optional)
For faster processing, install GPU-enabled PyTorch:

```powershell
# For NVIDIA GPU
pip install torch torchvision --index-url https://download.pytorch.org/whl/cu118
```

Then update the IDScanner class to enable GPU:
```python
# In scripts/id_scanner.py, line 14
self.reader = easyocr.Reader(languages, gpu=True)  # Change to True
```

### 2. Background Processing
For production, consider using Laravel Queues:

```php
// In IDScannerController.php
dispatch(new ProcessIDScan($filePath));
```

## Security Considerations

1. **Validate File Types:** Only allow image uploads (JPG, PNG)
2. **Limit File Size:** Default is 10MB max
3. **Admin Only:** ID scanning is restricted to admin users
4. **Secure Storage:** Scanned documents are stored in `storage/app/public/scanned_documents`
5. **Data Privacy:** Consider encrypting sensitive extracted data

## Supported ID Types

### Philippine IDs
- National ID (Phil-ID)
- Driver's License
- Passport
- SSS ID
- PhilHealth ID
- Voter's ID
- Senior Citizen ID
- PWD ID

### Extracted Fields
- Name
- Date of Birth
- ID Number
- Address
- Sex/Gender
- Expiry Date (when available)

## Customization

### Adding New Document Types

1. Update migration:
```php
// In migration file
$table->string('document_type')
      ->default('id_card')
      ->comment('id_card, passport, driver_license, custom_type');
```

2. Update Python script:
```python
# In scripts/id_scanner.py
# Add custom extraction patterns in _extract_fields() method
```

### Improving OCR Accuracy

1. **Pre-process images:**
```python
# Add image enhancement before OCR
import cv2
image = cv2.imread(image_path)
gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
enhanced = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)[1]
```

2. **Add more languages:**
```python
# In scripts/id_scanner.py
self.reader = easyocr.Reader(['en', 'tl'])  # Add Tagalog
```

## Testing

### Manual Testing
1. Prepare test ID images
2. Upload through admin interface
3. Verify extracted data accuracy
4. Test status updates
5. Test auto-fill functionality

### Unit Testing
```php
php artisan test --filter IDScannerTest
```

## Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Test Python script directly
- Verify Python dependencies are installed
- Ensure proper file permissions

## License & Credits

- **EasyOCR:** Apache 2.0 License
- **Laravel:** MIT License
- Developed for ResidenteWebApp

---

**Last Updated:** March 2, 2026
