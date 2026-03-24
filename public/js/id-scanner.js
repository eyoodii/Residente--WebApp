/**
 * ID Scanner JavaScript Module
 * Handles ID document upload and OCR processing
 */

class IDScanner {
    constructor(options = {}) {
        this.uploadUrl = options.uploadUrl || '/admin/id-scanner/scan';
        this.autoFillUrl = options.autoFillUrl || '/admin/id-scanner/auto-fill';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        this.onSuccess = options.onSuccess || this.defaultSuccessHandler;
        this.onError = options.onError || this.defaultErrorHandler;
        this.onProgress = options.onProgress || null;
    }

    /**
     * Scan an ID document
     */
    async scan(file, options = {}) {
        const formData = new FormData();
        formData.append('document', file);
        
        if (options.documentType) {
            formData.append('document_type', options.documentType);
        }
        
        if (options.residentId) {
            formData.append('resident_id', options.residentId);
        }

        try {
            const response = await fetch(this.uploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.error || 'Failed to scan document');
            }

            this.onSuccess(result);
            return result;

        } catch (error) {
            this.onError(error);
            throw error;
        }
    }

    /**
     * Auto-fill form with scanned data
     */
    async autoFill(documentId) {
        try {
            const response = await fetch(this.autoFillUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ document_id: documentId })
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.error || 'Failed to auto-fill form');
            }

            return result.data;

        } catch (error) {
            this.onError(error);
            throw error;
        }
    }

    /**
     * Default success handler
     */
    defaultSuccessHandler(result) {
        console.log('Scan successful:', result);
    }

    /**
     * Default error handler
     */
    defaultErrorHandler(error) {
        console.error('Scan error:', error);
        alert('Error: ' + error.message);
    }

    /**
     * Validate file before upload
     */
    validateFile(file) {
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!allowedTypes.includes(file.type)) {
            throw new Error('Invalid file type. Please upload a JPEG or PNG image.');
        }

        if (file.size > maxSize) {
            throw new Error('File is too large. Maximum size is 10MB.');
        }

        return true;
    }

    /**
     * Preview image before upload
     */
    previewImage(file, targetElement) {
        const reader = new FileReader();
        
        reader.onload = (e) => {
            if (typeof targetElement === 'string') {
                targetElement = document.querySelector(targetElement);
            }
            
            if (targetElement) {
                if (targetElement.tagName === 'IMG') {
                    targetElement.src = e.target.result;
                } else {
                    targetElement.innerHTML = `<img src="${e.target.result}" class="max-w-full h-auto rounded-lg shadow-lg" alt="ID Preview">`;
                }
            }
        };
        
        reader.readAsDataURL(file);
    }
}

// Initialize on document ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize file input handlers
    const fileInputs = document.querySelectorAll('[data-id-scanner]');
    
    fileInputs.forEach(input => {
        const scanner = new IDScanner({
            onSuccess: (result) => {
                // Show success message
                showNotification('Document scanned successfully!', 'success');
                
                // Display extracted data
                displayExtractedData(result.data);
                
                // Auto-fill form if applicable
                if (result.data.extracted_fields) {
                    autoFillForm(result.data.extracted_fields);
                }
            },
            onError: (error) => {
                showNotification(error.message, 'error');
            }
        });

        input.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            try {
                // Validate file
                scanner.validateFile(file);

                // Show preview
                const previewElement = document.querySelector('[data-preview]');
                if (previewElement) {
                    scanner.previewImage(file, previewElement);
                }

                // Show loading state
                showLoading();

                // Scan document
                const residentId = this.dataset.residentId || null;
                const documentType = this.dataset.documentType || 'id_card';
                
                await scanner.scan(file, { residentId, documentType });

            } catch (error) {
                showNotification(error.message, 'error');
            } finally {
                hideLoading();
            }
        });
    });
});

/**
 * Display extracted data in the UI
 */
function displayExtractedData(data) {
    const container = document.querySelector('[data-extracted-data]');
    if (!container) return;

    let html = `
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold mb-4">Extracted Information</h3>
            <div class="space-y-2">
    `;

    if (data.extracted_fields) {
        for (const [key, value] of Object.entries(data.extracted_fields)) {
            html += `
                <div class="flex justify-between border-b pb-2">
                    <span class="font-medium capitalize">${key.replace(/_/g, ' ')}:</span>
                    <span>${value || 'N/A'}</span>
                </div>
            `;
        }
    }

    html += `
            </div>
            <div class="mt-4 pt-4 border-t">
                <p class="text-sm text-gray-600">
                    Confidence Score: <span class="font-semibold">${(data.confidence_score * 100).toFixed(0)}%</span>
                </p>
            </div>
        </div>
    `;

    container.innerHTML = html;
}

/**
 * Auto-fill form fields with extracted data
 */
function autoFillForm(fields) {
    for (const [key, value] of Object.entries(fields)) {
        const input = document.querySelector(`[name="${key}"]`);
        if (input && value) {
            input.value = value;
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }
}

/**
 * Show notification message
 */
function showNotification(message, type = 'info') {
    // You can integrate with your existing notification system
    const alertClass = type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${alertClass} px-6 py-4 rounded-lg shadow-lg z-50 transition-opacity duration-300`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/**
 * Show loading state
 */
function showLoading() {
    const loader = document.querySelector('[data-loader]');
    if (loader) {
        loader.classList.remove('hidden');
    }
}

/**
 * Hide loading state
 */
function hideLoading() {
    const loader = document.querySelector('[data-loader]');
    if (loader) {
        loader.classList.add('hidden');
    }
}

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = IDScanner;
}
