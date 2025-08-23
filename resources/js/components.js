// SB Admin 2 Enhanced Components
// This file contains JavaScript for enhanced UI components



class SBAdminComponents {
    constructor() {
        this.init();
    }

    init() {
        this.initNotifications();
        this.initSearchComponents();
        this.initLoadingStates();
        this.initChartComponents();
        this.initAdvancedFilters();

    }

    // Notification System
    initNotifications() {
        // Toast notifications
        this.createToastContainer();

        // Global notification function
        window.showNotification = (message, type = 'info', title = null, duration = 5000) => {
            this.showToast(message, type, title, duration);
        };
    }

    createToastContainer() {
        if (!document.querySelector('.toast-container')) {
            const container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
    }

    showToast(message, type = 'info', title = null, duration = 5000) {
        const container = document.querySelector('.toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast toast-${type} show`;

        const iconMap = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle',
            primary: 'fas fa-bell'
        };

        const defaultTitles = {
            success: 'Berhasil',
            error: 'Error',
            warning: 'Peringatan',
            info: 'Informasi',
            primary: 'Notifikasi'
        };

        const toastTitle = title || defaultTitles[type] || 'Notifikasi';
        const icon = iconMap[type] || iconMap.info;

        toast.innerHTML = `
            <div class="toast-header">
                <div class="toast-title">
                    <i class="${icon}"></i>
                    ${toastTitle}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;

        container.appendChild(toast);

        // Auto remove after duration
        setTimeout(() => {
            this.hideToast(toast);
        }, duration);

        // Add click to dismiss
        toast.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-close')) {
                this.hideToast(toast);
            }
        });
    }

    hideToast(toast) {
        toast.classList.remove('show');
        toast.classList.add('hide');

        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    // Search Components
    initSearchComponents() {
        // Advanced search toggle
        document.querySelectorAll('.advanced-toggle').forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const advancedFields = toggle.closest('.advanced-search').querySelector('.advanced-fields');
                const icon = toggle.querySelector('i');

                if (advancedFields.style.display === 'none' || !advancedFields.style.display) {
                    advancedFields.style.display = 'grid';
                    icon.classList.add('rotated');
                } else {
                    advancedFields.style.display = 'none';
                    icon.classList.remove('rotated');
                }
            });
        });

        // Quick search with suggestions
        document.querySelectorAll('.quick-search').forEach(search => {
            const input = search.querySelector('.search-input');
            const suggestions = search.querySelector('.search-suggestions');

            if (input && suggestions) {
                input.addEventListener('input', (e) => {
                    this.handleQuickSearch(e.target.value, suggestions);
                });

                input.addEventListener('focus', () => {
                    if (input.value.trim()) {
                        suggestions.style.display = 'block';
                    }
                });

                // Hide suggestions when clicking outside
                document.addEventListener('click', (e) => {
                    if (!search.contains(e.target)) {
                        suggestions.style.display = 'none';
                    }
                });
            }
        });

        // Filter tags
        document.querySelectorAll('.filter-tag .tag-remove').forEach(removeBtn => {
            removeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const tag = removeBtn.closest('.filter-tag');
                tag.remove();
                this.updateFilterCount();
            });
        });
    }

    handleQuickSearch(query, suggestions) {
        if (query.trim().length < 2) {
            suggestions.style.display = 'none';
            return;
        }

        // Simulate search suggestions (replace with actual API call)
        const mockSuggestions = [
            { title: 'Obat Paracetamol', subtitle: 'Kategori: Analgesik' },
            { title: 'Obat Amoxicillin', subtitle: 'Kategori: Antibiotik' },
            { title: 'Obat Vitamin C', subtitle: 'Kategori: Vitamin' }
        ].filter(item =>
            item.title.toLowerCase().includes(query.toLowerCase()) ||
            item.subtitle.toLowerCase().includes(query.toLowerCase())
        );

        this.renderSearchSuggestions(mockSuggestions, suggestions);
        suggestions.style.display = 'block';
    }

    renderSearchSuggestions(suggestions, container) {
        container.innerHTML = suggestions.map(item => `
            <div class="suggestion-item">
                <div class="suggestion-title">${item.title}</div>
                <div class="suggestion-subtitle">${item.subtitle}</div>
            </div>
        `).join('');

        // Add click handlers
        container.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('click', () => {
                const title = item.querySelector('.suggestion-title').textContent;
                const searchInput = container.parentNode.querySelector('.search-input');
                searchInput.value = title;
                container.style.display = 'none';
                // Trigger search
                this.performSearch(title);
            });
        });
    }

    performSearch(query) {
        // Implement actual search logic here
        console.log('Searching for:', query);
        // You can trigger a form submission or AJAX request here
    }

    updateFilterCount() {
        const tags = document.querySelectorAll('.filter-tag');
        const countElement = document.querySelector('.filter-count');
        if (countElement) {
            countElement.textContent = tags.length;
        }
    }

    // Loading States
    initLoadingStates() {
        // Button loading states
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.classList.contains('btn-loading')) {
                    this.setButtonLoading(submitBtn, true);
                }
            });
        });

        // Form loading states
        document.querySelectorAll('.form-loading').forEach(form => {
            this.setFormLoading(form, true);
        });
    }

    setButtonLoading(button, loading) {
        if (loading) {
            button.classList.add('btn-loading');
            button.disabled = true;
            button.dataset.originalText = button.textContent;
            button.textContent = 'Loading...';
        } else {
            button.classList.remove('btn-loading');
            button.disabled = false;
            if (button.dataset.originalText) {
                button.textContent = button.dataset.originalText;
            }
        }
    }

    setFormLoading(form, loading) {
        if (loading) {
            form.classList.add('form-loading');
        } else {
            form.classList.remove('form-loading');
        }
    }

    // Chart Components
    initChartComponents() {
        // Initialize chart containers
        document.querySelectorAll('.chart-container').forEach(container => {
            this.initChartContainer(container);
        });

        // Chart controls
        document.querySelectorAll('.chart-controls select, .chart-controls input').forEach(control => {
            control.addEventListener('change', (e) => {
                this.handleChartControlChange(e.target);
            });
        });
    }

    initChartContainer(container) {
        // Add loading state
        const loading = document.createElement('div');
        loading.className = 'chart-loading';
        loading.innerHTML = `
            <div class="spinner"></div>
            <span>Loading chart...</span>
        `;

        container.appendChild(loading);

        // Simulate chart loading (replace with actual chart initialization)
        setTimeout(() => {
            loading.remove();
            this.renderMockChart(container);
        }, 1500);
    }

    renderMockChart(container) {
        // This is a placeholder for actual chart rendering
        // Replace with Chart.js, ApexCharts, or other chart library
        const canvas = document.createElement('canvas');
        canvas.width = container.offsetWidth;
        canvas.height = container.offsetHeight;
        canvas.style.border = '1px solid #e3e6f0';
        canvas.style.borderRadius = '0.35rem';

        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#f8f9fc';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = '#4e73df';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Chart Placeholder', canvas.width / 2, canvas.height / 2);

        container.appendChild(canvas);
    }

    handleChartControlChange(control) {
        const chartContainer = control.closest('.chart-wrapper').querySelector('.chart-container');
        if (chartContainer) {
            // Clear existing chart
            chartContainer.innerHTML = '';
            // Reinitialize with new parameters
            this.initChartContainer(chartContainer);
        }
    }

    // Advanced Filters
    initAdvancedFilters() {
        // Date range pickers
        document.querySelectorAll('.date-range input[type="date"]').forEach(input => {
            input.addEventListener('change', (e) => {
                this.handleDateRangeChange(e.target);
            });
        });

        // Filter persistence
        this.loadSavedFilters();
        this.saveFiltersOnChange();
    }

    handleDateRangeChange(input) {
        const dateRange = input.closest('.date-range');
        const startDate = dateRange.querySelector('input[type="date"]:first-child');
        const endDate = dateRange.querySelector('input[type="date"]:last-child');

        if (startDate.value && endDate.value) {
            if (new Date(startDate.value) > new Date(endDate.value)) {
                this.showNotification('Tanggal mulai tidak boleh lebih besar dari tanggal akhir', 'error');
                input.value = '';
            }
        }
    }

    loadSavedFilters() {
        const savedFilters = localStorage.getItem('savedFilters');
        if (savedFilters) {
            try {
                const filters = JSON.parse(savedFilters);
                Object.keys(filters).forEach(key => {
                    const element = document.querySelector(`[name="${key}"]`);
                    if (element) {
                        element.value = filters[key];
                    }
                });
            } catch (e) {
                console.error('Error loading saved filters:', e);
            }
        }
    }

    saveFiltersOnChange() {
        document.querySelectorAll('.search-container input, .search-container select').forEach(element => {
            element.addEventListener('change', () => {
                this.saveCurrentFilters();
            });
        });
    }

    saveCurrentFilters() {
        const filters = {};
        document.querySelectorAll('.search-container input, .search-container select').forEach(element => {
            if (element.name && element.value) {
                filters[element.name] = element.value;
            }
        });

        localStorage.setItem('savedFilters', JSON.stringify(filters));
    }



    // Utility methods
    debounce(func, wait) {
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

    // Export functionality
    exportData(format = 'csv') {
        // Implement export functionality
        console.log(`Exporting data as ${format}`);
        this.showNotification(`Data berhasil diekspor dalam format ${format.toUpperCase()}`, 'success');
    }
}

// Initialize components when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.sbAdminComponents = new SBAdminComponents();

    // Initialize any additional components here
});

// Export for use in other modules
export default SBAdminComponents;
