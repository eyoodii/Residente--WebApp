/**
 * HTTP Error Handler Module
 * Provides user-friendly error notifications for AJAX requests
 * Works with Alpine.js and the existing toast component
 */

export function initHttpErrorHandler() {
    window.addEventListener('http-error', (event) => {
        const { status, title, message, action } = event.detail;

        // Create and dispatch a toast notification event
        const toastEvent = new CustomEvent('notify', {
            detail: {
                type: getToastType(status),
                title: title,
                message: message,
                duration: action ? 0 : 5000, // Keep open if action needed
                action: action ? getActionButton(action) : null
            }
        });
        window.dispatchEvent(toastEvent);

        // For critical errors, also show a modal/alert
        if (status === 419 || status === 401) {
            showErrorModal(title, message, action);
        }
    });
}

function getToastType(status) {
    if (status >= 500) return 'error';
    if (status === 422) return 'warning';
    if (status === 419 || status === 401) return 'warning';
    if (status === 403) return 'error';
    return 'error';
}

function getActionButton(action) {
    switch (action) {
        case 'refresh':
            return {
                label: 'Refresh Page',
                onClick: () => window.location.reload()
            };
        case 'login':
            return {
                label: 'Log In',
                onClick: () => window.location.href = '/login'
            };
        default:
            return null;
    }
}

function showErrorModal(title, message, action) {
    // Create modal elements
    const backdrop = document.createElement('div');
    backdrop.className = 'fixed inset-0 bg-gray-900/50 z-50 flex items-center justify-center p-4';
    backdrop.id = 'http-error-modal';

    const modal = document.createElement('div');
    modal.className = 'bg-white rounded-xl shadow-2xl max-w-md w-full p-6 transform transition-all';
    modal.innerHTML = `
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 mb-4">
                <svg class="h-8 w-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">${title}</h3>
            <p class="text-gray-600 mb-6">${message}</p>
            <div class="flex gap-3 justify-center">
                ${action === 'refresh' ? `
                    <button onclick="window.location.reload()" class="px-4 py-2 bg-sea-green text-white font-medium rounded-lg hover:bg-deep-forest transition-colors">
                        Refresh Page
                    </button>
                ` : ''}
                ${action === 'login' ? `
                    <button onclick="window.location.href='/login'" class="px-4 py-2 bg-sea-green text-white font-medium rounded-lg hover:bg-deep-forest transition-colors">
                        Log In
                    </button>
                ` : ''}
                <button onclick="document.getElementById('http-error-modal').remove()" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    ${action ? 'Cancel' : 'Close'}
                </button>
            </div>
        </div>
    `;

    backdrop.appendChild(modal);
    
    // Remove existing modal if present
    document.getElementById('http-error-modal')?.remove();
    
    document.body.appendChild(backdrop);

    // Close on backdrop click
    backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) {
            backdrop.remove();
        }
    });

    // Close on Escape key
    const handleEscape = (e) => {
        if (e.key === 'Escape') {
            backdrop.remove();
            document.removeEventListener('keydown', handleEscape);
        }
    };
    document.addEventListener('keydown', handleEscape);
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHttpErrorHandler);
} else {
    initHttpErrorHandler();
}
