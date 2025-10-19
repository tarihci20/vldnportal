/**
 * Vildan Portal - AJAX Handler
 * Simplified AJAX requests with error handling
 */

(function() {
    'use strict';
    
    const AjaxHandler = {
        // Configuration
        config: {
            baseUrl: '/api',
            timeout: 30000,
            retryAttempts: 3,
            retryDelay: 1000
        },
        
        // Get CSRF token
        getCSRFToken: function() {
            return document.querySelector('meta[name="csrf-token"]')?.content || '';
        },
        
        // Build headers
        buildHeaders: function(customHeaders = {}) {
            return {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.getCSRFToken(),
                'X-Requested-With': 'XMLHttpRequest',
                ...customHeaders
            };
        },
        
        // Build URL with query params
        buildUrl: function(endpoint, params = {}) {
            const url = new URL(endpoint, window.location.origin);
            Object.keys(params).forEach(key => {
                if (params[key] !== null && params[key] !== undefined) {
                    url.searchParams.append(key, params[key]);
                }
            });
            return url.toString();
        },
        
        // Main request function
        request: function(url, options = {}) {
            const defaults = {
                method: 'GET',
                headers: this.buildHeaders(options.headers || {}),
                signal: options.signal
            };
            
            // Add body if not GET request
            if (options.method !== 'GET' && options.body) {
                if (options.body instanceof FormData) {
                    delete defaults.headers['Content-Type']; // Let browser set it
                    defaults.body = options.body;
                } else {
                    defaults.body = JSON.stringify(options.body);
                }
            }
            
            const config = { ...defaults, ...options };
            
            return this.fetchWithRetry(url, config)
                .then(response => this.handleResponse(response))
                .catch(error => this.handleError(error));
        },
        
        // Fetch with retry logic
        fetchWithRetry: async function(url, options, attempt = 1) {
            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), this.config.timeout);
                
                const response = await fetch(url, {
                    ...options,
                    signal: options.signal || controller.signal
                });
                
                clearTimeout(timeoutId);
                return response;
                
            } catch (error) {
                if (attempt < this.config.retryAttempts && error.name === 'AbortError') {
                    await this.delay(this.config.retryDelay * attempt);
                    return this.fetchWithRetry(url, options, attempt + 1);
                }
                throw error;
            }
        },
        
        // Handle response
        handleResponse: async function(response) {
            const contentType = response.headers.get('content-type');
            
            // Parse response based on content type
            let data;
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else if (contentType && contentType.includes('text')) {
                data = await response.text();
            } else {
                data = await response.blob();
            }
            
            // Check if response is ok
            if (!response.ok) {
                throw {
                    status: response.status,
                    statusText: response.statusText,
                    data: data
                };
            }
            
            return {
                success: true,
                status: response.status,
                data: data
            };
        },
        
        // Handle errors
        handleError: function(error) {
            console.error('[AJAX Error]', error);
            
            let message = 'Bir hata oluştu. Lütfen tekrar deneyin.';
            
            if (error.status) {
                switch (error.status) {
                    case 400:
                        message = 'Geçersiz istek.';
                        break;
                    case 401:
                        message = 'Oturum süreniz doldu. Lütfen tekrar giriş yapın.';
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 2000);
                        break;
                    case 403:
                        message = 'Bu işlem için yetkiniz yok.';
                        break;
                    case 404:
                        message = 'İstenen kaynak bulunamadı.';
                        break;
                    case 422:
                        message = error.data?.message || 'Form doğrulama hatası.';
                        break;
                    case 429:
                        message = 'Çok fazla istek gönderdiniz. Lütfen bekleyin.';
                        break;
                    case 500:
                        message = 'Sunucu hatası oluştu.';
                        break;
                    case 503:
                        message = 'Servis şu an kullanılamıyor.';
                        break;
                }
            } else if (error.name === 'AbortError') {
                message = 'İstek zaman aşımına uğradı.';
            } else if (!navigator.onLine) {
                message = 'İnternet bağlantınızı kontrol edin.';
            }
            
            return {
                success: false,
                error: true,
                message: message,
                details: error
            };
        },
        
        // Delay helper
        delay: function(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },
        
        // ============================================
        // HTTP METHOD SHORTCUTS
        // ============================================
        
        get: function(url, params = {}, options = {}) {
            const fullUrl = this.buildUrl(url, params);
            return this.request(fullUrl, { ...options, method: 'GET' });
        },
        
        post: function(url, body = {}, options = {}) {
            return this.request(url, { ...options, method: 'POST', body });
        },
        
        put: function(url, body = {}, options = {}) {
            return this.request(url, { ...options, method: 'PUT', body });
        },
        
        patch: function(url, body = {}, options = {}) {
            return this.request(url, { ...options, method: 'PATCH', body });
        },
        
        delete: function(url, options = {}) {
            return this.request(url, { ...options, method: 'DELETE' });
        },
        
        // ============================================
        // SPECIALIZED REQUESTS
        // ============================================
        
        // Upload file
        upload: function(url, file, additionalData = {}) {
            const formData = new FormData();
            formData.append('file', file);
            
            // Add additional data
            Object.keys(additionalData).forEach(key => {
                formData.append(key, additionalData[key]);
            });
            
            return this.request(url, {
                method: 'POST',
                body: formData
            });
        },
        
        // Download file
        download: async function(url, filename) {
            try {
                const response = await fetch(url, {
                    headers: this.buildHeaders()
                });
                
                if (!response.ok) throw new Error('Download failed');
                
                const blob = await response.blob();
                const downloadUrl = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = downloadUrl;
                link.download = filename || 'download';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(downloadUrl);
                
                return { success: true };
            } catch (error) {
                return this.handleError(error);
            }
        },
        
        // Submit form via AJAX
        submitForm: async function(formElement, options = {}) {
            const formData = new FormData(formElement);
            const url = formElement.action || window.location.href;
            const method = formElement.method?.toUpperCase() || 'POST';
            
            // Convert FormData to JSON if needed
            let body = formData;
            if (options.json) {
                body = {};
                formData.forEach((value, key) => {
                    body[key] = value;
                });
            }
            
            return this.request(url, { method, body });
        }
    };
    
    // ============================================
    // HELPER FUNCTIONS
    // ============================================
    
    // Show loading state
    AjaxHandler.showLoading = function(element) {
        if (element) {
            element.disabled = true;
            element.dataset.originalContent = element.innerHTML;
            element.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Yükleniyor...';
        }
    };
    
    // Hide loading state
    AjaxHandler.hideLoading = function(element) {
        if (element && element.dataset.originalContent) {
            element.disabled = false;
            element.innerHTML = element.dataset.originalContent;
            delete element.dataset.originalContent;
        }
    };
    
    // Show toast notification
    AjaxHandler.showNotification = function(message, type = 'info') {
        if (window.VildanApp && window.VildanApp.showToast) {
            window.VildanApp.showToast(message, type);
        } else {
            console.log(`[${type.toUpperCase()}] ${message}`);
        }
    };
    
    // ============================================
    // DEBOUNCED REQUESTS
    // ============================================
    
    const debouncedRequests = new Map();
    
    AjaxHandler.debounced = function(key, url, options = {}, delay = 300) {
        // Cancel previous request
        if (debouncedRequests.has(key)) {
            clearTimeout(debouncedRequests.get(key).timeout);
            if (debouncedRequests.get(key).controller) {
                debouncedRequests.get(key).controller.abort();
            }
        }
        
        return new Promise((resolve, reject) => {
            const controller = new AbortController();
            
            const timeout = setTimeout(() => {
                this.request(url, { ...options, signal: controller.signal })
                    .then(resolve)
                    .catch(reject)
                    .finally(() => {
                        debouncedRequests.delete(key);
                    });
            }, delay);
            
            debouncedRequests.set(key, { timeout, controller });
        });
    };
    
    // ============================================
    // BATCH REQUESTS
    // ============================================
    
    AjaxHandler.batch = function(requests) {
        return Promise.allSettled(
            requests.map(({ url, options }) => this.request(url, options))
        ).then(results => {
            return results.map(result => {
                if (result.status === 'fulfilled') {
                    return result.value;
                } else {
                    return {
                        success: false,
                        error: true,
                        message: result.reason
                    };
                }
            });
        });
    };
    
    // ============================================
    // POLLING
    // ============================================
    
    AjaxHandler.poll = function(url, options = {}, interval = 5000, maxAttempts = 60) {
        let attempts = 0;
        let intervalId;
        
        return new Promise((resolve, reject) => {
            const check = async () => {
                attempts++;
                
                try {
                    const result = await this.request(url, options);
                    
                    if (result.success && options.condition) {
                        if (options.condition(result.data)) {
                            clearInterval(intervalId);
                            resolve(result);
                        }
                    } else if (result.success) {
                        clearInterval(intervalId);
                        resolve(result);
                    }
                    
                    if (attempts >= maxAttempts) {
                        clearInterval(intervalId);
                        reject(new Error('Max polling attempts reached'));
                    }
                } catch (error) {
                    clearInterval(intervalId);
                    reject(error);
                }
            };
            
            intervalId = setInterval(check, interval);
            check(); // First check immediately
        });
    };
    
    // Expose to window
    window.AjaxHandler = AjaxHandler;
    window.ajax = AjaxHandler; // Short alias
    
})();

// ============================================
// GLOBAL AJAX EVENT LISTENERS
// ============================================

// Show global loading indicator
let activeRequests = 0;

document.addEventListener('ajaxStart', () => {
    activeRequests++;
    if (activeRequests === 1) {
        // Show global loading indicator
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.classList.remove('hidden');
        }
    }
});

document.addEventListener('ajaxComplete', () => {
    activeRequests--;
    if (activeRequests === 0) {
        // Hide global loading indicator
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.classList.add('hidden');
        }
    }
});
