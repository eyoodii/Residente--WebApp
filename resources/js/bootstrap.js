import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Enhanced Error Handling for AJAX Requests
 * Handles common HTTP errors with user-friendly messages
 */
window.axios.interceptors.response.use(
    response => response,
    error => {
        const status = error.response?.status;
        const data = error.response?.data;

        // Define error messages for common HTTP errors
        const errorMessages = {
            419: {
                title: 'Session Expired',
                message: data?.message || 'Your session has expired. Please refresh the page to continue.',
                action: 'refresh'
            },
            401: {
                title: 'Unauthorized',
                message: 'You are not logged in. Please log in to continue.',
                action: 'login'
            },
            403: {
                title: 'Access Denied',
                message: data?.message || 'You do not have permission to perform this action.',
                action: null
            },
            404: {
                title: 'Not Found',
                message: 'The requested resource was not found.',
                action: null
            },
            422: {
                title: 'Validation Error',
                message: data?.message || 'Please check your input and try again.',
                action: null
            },
            429: {
                title: 'Too Many Requests',
                message: 'You are making too many requests. Please wait a moment and try again.',
                action: null
            },
            500: {
                title: 'Server Error',
                message: 'Something went wrong on our end. Please try again later.',
                action: null
            },
            503: {
                title: 'Service Unavailable',
                message: 'The service is temporarily unavailable. Please try again later.',
                action: null
            }
        };

        const errorConfig = errorMessages[status];

        // Dispatch custom event for error handling (can be caught by components)
        if (errorConfig) {
            const event = new CustomEvent('http-error', {
                detail: {
                    status,
                    ...errorConfig,
                    originalError: error
                }
            });
            window.dispatchEvent(event);
        }

        // For 419 errors, automatically refresh CSRF token and retry once
        if (status === 419 && !error.config._retry) {
            error.config._retry = true;
            
            return axios.post('/keep-alive').then(response => {
                // Update CSRF token in meta tag and axios defaults
                const newToken = response.data.csrf;
                document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', newToken);
                error.config.headers['X-CSRF-TOKEN'] = newToken;
                
                // Retry the original request
                return axios.request(error.config);
            }).catch(() => {
                // If refresh fails, show the error
                return Promise.reject(error);
            });
        }

        return Promise.reject(error);
    }
);

/**
 * Session Keep-Alive
 * Pings the server every 10 minutes to prevent session expiration during long form fills
 */
let keepAliveInterval = null;

window.startKeepAlive = function(intervalMinutes = 10) {
    if (keepAliveInterval) return; // Already running
    
    keepAliveInterval = setInterval(() => {
        axios.post('/keep-alive')
            .then(response => {
                // Update CSRF token if provided
                const newToken = response.data.csrf;
                if (newToken) {
                    document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', newToken);
                }
            })
            .catch(() => {
                // Session likely expired, stop keep-alive
                stopKeepAlive();
            });
    }, intervalMinutes * 60 * 1000);
};

window.stopKeepAlive = function() {
    if (keepAliveInterval) {
        clearInterval(keepAliveInterval);
        keepAliveInterval = null;
    }
};

// Auto-start keep-alive when there are forms on the page
document.addEventListener('DOMContentLoaded', () => {
    const hasForms = document.querySelectorAll('form[method="POST"], form[method="post"]').length > 0;
    if (hasForms) {
        startKeepAlive();
    }
});
