/**
 * Vildan Portal - Ana JavaScript Dosyası
 * Okul Yönetim Sistemi
 */

(function() {
    'use strict';
    
    // ============================================
    // GLOBAL APP OBJECT
    // ============================================
    window.VildanApp = {
        version: '1.0.0',
        debug: true,
        
        // Configuration
        config: {
            apiUrl: '/api',
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.content,
            debounceDelay: 300,
            toastDuration: 5000
        },
        
        // Initialize
        init: function() {
            this.initSidebar();
            this.initModals();
            this.initTooltips();
            this.initForms();
            this.initTables();
            this.initAlerts();
            this.log('VildanApp initialized');
        },
        
        // Logger
        log: function(message, type = 'info') {
            if (this.debug) {
                console[type](` [Vildan] ${message}`);
            }
        }
    };
    
    // ============================================
    // SIDEBAR MANAGEMENT
    // ============================================
    VildanApp.initSidebar = function() {
        const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
        const sidebar = document.querySelector('.sidebar');
        const sidebarOverlay = document.querySelector('.sidebar-overlay');
        
        if (!sidebarToggle || !sidebar) return;
        
        // Toggle sidebar
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle('active');
            }
        });
        
        // Close sidebar when clicking overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('active');
            });
        }
        
        // Close sidebar on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('active');
                }
            }
        });
        
        this.log('Sidebar initialized');
    };
    
    // ============================================
    // MODAL MANAGEMENT
    // ============================================
    VildanApp.initModals = function() {
        // Modal open
        document.querySelectorAll('[data-modal-toggle]').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const modalId = trigger.dataset.modalToggle;
                this.openModal(modalId);
            });
        });
        
        // Modal close
        document.querySelectorAll('[data-modal-close]').forEach(closeBtn => {
            closeBtn.addEventListener('click', () => {
                const modal = closeBtn.closest('.modal');
                if (modal) {
                    this.closeModal(modal.id);
                }
            });
        });
        
        // Close modal on backdrop click
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.addEventListener('click', (e) => {
                if (e.target === backdrop) {
                    const modal = backdrop.nextElementSibling;
                    if (modal) {
                        this.closeModal(modal.id);
                    }
                }
            });
        });
        
        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal:not(.hidden)');
                if (openModal) {
                    this.closeModal(openModal.id);
                }
            }
        });
        
        this.log('Modals initialized');
    };
    
    VildanApp.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            this.log(`Modal opened: ${modalId}`);
        }
    };
    
    VildanApp.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            this.log(`Modal closed: ${modalId}`);
        }
    };
    
    // ============================================
    // TOOLTIP MANAGEMENT
    // ============================================
    VildanApp.initTooltips = function() {
        document.querySelectorAll('[data-tooltip]').forEach(el => {
            el.addEventListener('mouseenter', (e) => {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                tooltip.textContent = el.dataset.tooltip;
                tooltip.style.position = 'absolute';
                tooltip.style.zIndex = '9999';
                document.body.appendChild(tooltip);
                
                const rect = el.getBoundingClientRect();
                tooltip.style.top = `${rect.top - tooltip.offsetHeight - 8}px`;
                tooltip.style.left = `${rect.left + (rect.width - tooltip.offsetWidth) / 2}px`;
                
                el.addEventListener('mouseleave', () => {
                    tooltip.remove();
                }, { once: true });
            });
        });
        
        this.log('Tooltips initialized');
    };
    
    // ============================================
    // FORM MANAGEMENT
    // ============================================
    VildanApp.initForms = function() {
        // Auto-submit on change for filters
        document.querySelectorAll('[data-auto-submit]').forEach(form => {
            form.querySelectorAll('select, input[type="checkbox"]').forEach(input => {
                input.addEventListener('change', () => {
                    form.submit();
                });
            });
        });
        
        // Confirm before submit
        document.querySelectorAll('[data-confirm]').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!confirm(form.dataset.confirm)) {
                    e.preventDefault();
                }
            });
        });
        
        // Disable submit button after submit
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                const submitBtn = form.querySelector('[type="submit"]');
                if (submitBtn && !form.hasAttribute('data-no-disable')) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> İşleniyor...';
                }
            });
        });
        
        // Phone number formatting
        document.querySelectorAll('input[type="tel"]').forEach(input => {
            input.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) {
                    value = value.slice(0, 11);
                }
                e.target.value = value;
            });
        });
        
        // TC Kimlik No validation
        document.querySelectorAll('input[name="tc_no"]').forEach(input => {
            input.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) {
                    value = value.slice(0, 11);
                }
                e.target.value = value;
            });
        });
        
        this.log('Forms initialized');
    };
    
    // ============================================
    // TABLE MANAGEMENT
    // ============================================
    VildanApp.initTables = function() {
        // Table row click
        document.querySelectorAll('[data-table-clickable]').forEach(table => {
            table.querySelectorAll('tbody tr').forEach(row => {
                if (row.dataset.href) {
                    row.style.cursor = 'pointer';
                    row.addEventListener('click', (e) => {
                        // Don't navigate if clicking on a button or link
                        if (!e.target.closest('a, button')) {
                            window.location.href = row.dataset.href;
                        }
                    });
                }
            });
        });
        
        // Sortable tables
        document.querySelectorAll('[data-sortable]').forEach(table => {
            const headers = table.querySelectorAll('th[data-sort]');
            headers.forEach(header => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', () => {
                    const column = header.dataset.sort;
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('sort', column);
                    
                    // Toggle direction
                    const currentDirection = currentUrl.searchParams.get('direction');
                    const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
                    currentUrl.searchParams.set('direction', newDirection);
                    
                    window.location.href = currentUrl.toString();
                });
            });
        });
        
        this.log('Tables initialized');
    };
    
    // ============================================
    // ALERTS & TOASTS
    // ============================================
    VildanApp.initAlerts = function() {
        // Auto-dismiss alerts
        document.querySelectorAll('[data-auto-dismiss]').forEach(alert => {
            const delay = parseInt(alert.dataset.autoDismiss) || 5000;
            setTimeout(() => {
                this.dismissAlert(alert);
            }, delay);
        });
        
        // Manual dismiss
        document.querySelectorAll('[data-dismiss-alert]').forEach(btn => {
            btn.addEventListener('click', () => {
                const alert = btn.closest('.alert');
                if (alert) {
                    this.dismissAlert(alert);
                }
            });
        });
        
        this.log('Alerts initialized');
    };
    
    VildanApp.dismissAlert = function(alert) {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            alert.remove();
        }, 300);
    };
    
    VildanApp.showToast = function(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} fixed top-20 right-4 z-50 min-w-[300px] shadow-lg`;
        toast.style.animation = 'slideInRight 0.3s ease-out';
        toast.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-${this.getIconForType(type)} mr-2"></i>
                    <span>${message}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, this.config.toastDuration);
    };
    
    VildanApp.getIconForType = function(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    };
    
    // ============================================
    // AJAX HELPERS
    // ============================================
    VildanApp.ajax = function(url, options = {}) {
        const defaults = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.config.csrfToken
            }
        };
        
        const config = { ...defaults, ...options };
        
        return fetch(url, config)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                this.log(`AJAX error: ${error.message}`, 'error');
                throw error;
            });
    };
    
    // ============================================
    // UTILITY FUNCTIONS
    // ============================================
    
    // Format currency
    VildanApp.formatCurrency = function(amount) {
        return new Intl.NumberFormat('tr-TR', {
            style: 'currency',
            currency: 'TRY'
        }).format(amount);
    };
    
    // Format date
    VildanApp.formatDate = function(date, format = 'short') {
        const options = {
            short: { day: '2-digit', month: '2-digit', year: 'numeric' },
            long: { day: 'numeric', month: 'long', year: 'numeric' },
            full: { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }
        };
        
        return new Intl.DateTimeFormat('tr-TR', options[format]).format(new Date(date));
    };
    
    // Copy to clipboard
    VildanApp.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            this.showToast('Panoya kopyalandı!', 'success');
        }).catch(err => {
            this.log(`Copy failed: ${err}`, 'error');
        });
    };
    
    // Scroll to element
    VildanApp.scrollTo = function(selector, offset = 0) {
        const element = document.querySelector(selector);
        if (element) {
            const top = element.getBoundingClientRect().top + window.pageYOffset - offset;
            window.scrollTo({ top, behavior: 'smooth' });
        }
    };
    
    // ============================================
    // INITIALIZATION
    // ============================================
    
    // DOM Ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            VildanApp.init();
        });
    } else {
        VildanApp.init();
    }
    
    // Expose to window
    window.VildanApp = VildanApp;
    
})();

// ============================================
// GLOBAL HELPER FUNCTIONS
// ============================================

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Generate random ID
function generateId(prefix = 'id') {
    return `${prefix}-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
}

// Check if element is in viewport
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}