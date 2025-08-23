/**
 * Sales Management JavaScript - Fixed Version
 * Simple functionality for the sales system without errors
 */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sales management script loaded successfully');
    
    // Initialize components
    initSalesComponents();
});

// Initialize all sales-related components
function initSalesComponents() {
    // Auto-focus on search input
    const searchInput = document.getElementById('searchQuery');
    if (searchInput) {
        searchInput.focus();
        console.log('Search input focused');
    }
    
    // Initialize keyboard shortcuts
    initKeyboardShortcuts();
    
    // Initialize payment handlers
    initPaymentHandlers();
    
    // Initialize Livewire event listeners
    initLivewireListeners();
}

// Keyboard shortcuts
function initKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl + K untuk focus ke search
        if (e.ctrlKey && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.getElementById('searchQuery');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
        
        // Enter untuk menambah ke keranjang jika ada obat yang dipilih
        if (e.key === 'Enter' && document.querySelector('.search-results .list-group-item')) {
            const firstResult = document.querySelector('.search-results .list-group-item');
            if (firstResult) {
                firstResult.click();
            }
        }
        
        // Escape untuk menutup form pembayaran
        if (e.key === 'Escape' && document.querySelector('.payment-form')) {
            const closeBtn = document.querySelector('.btn-secondary');
            if (closeBtn) {
                closeBtn.click();
            }
        }
    });
}

// Payment input handlers
function initPaymentHandlers() {
    // Global payment input handler
    window.handlePaymentInput = function(input) {
        // Remove non-digit characters
        let value = input.value.replace(/[^0-9]/g, '');
        
        // Convert to number
        let numValue = parseInt(value) || 0;
        
        // Update input value
        input.value = numValue;
        
        // Trigger Livewire update if available
        if (window.Livewire && window.Livewire.find) {
            try {
                const component = document.querySelector('[wire\\:id]');
                if (component) {
                    const componentId = component.getAttribute('wire:id');
                    const livewireComponent = window.Livewire.find(componentId);
                    if (livewireComponent) {
                        livewireComponent.set('paidAmount', numValue);
                        livewireComponent.call('calculateChange');
                    }
                }
            } catch (error) {
                console.log('Livewire update failed:', error.message);
            }
        }
    };

    // Format payment input
    window.formatPaymentInput = function(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        let numValue = parseInt(value) || 0;
        
        // Format dengan separator ribuan
        if (numValue > 0) {
            input.value = numValue.toLocaleString('id-ID');
        } else {
            input.value = '';
        }
    };
}

// Livewire event listeners
function initLivewireListeners() {
    // Wait for Livewire to be available
    if (typeof Livewire !== 'undefined') {
        // Listen for Livewire load event
        document.addEventListener('livewire:load', function() {
            console.log('Livewire loaded, initializing event listeners');
            
            // Payment form shown event
            if (Livewire.on) {
                Livewire.on('paymentFormShown', function() {
                    setTimeout(function() {
                        const paymentInput = document.getElementById('paidAmountInput');
                        if (paymentInput) {
                            paymentInput.focus();
                            paymentInput.select();
                        }
                    }, 100);
                });
            }
        });
    } else {
        console.log('Livewire not available, skipping event listeners');
    }
}

// Utility functions
window.formatCurrency = function(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
};

window.validateNumber = function(input, min = 0, max = 999999) {
    const value = parseInt(input.value) || 0;
    if (value < min) {
        input.value = min;
        return false;
    }
    if (value > max) {
        input.value = max;
        return false;
    }
    return true;
};

// Print function for reports
window.printReport = function() {
    window.print();
};

// Export functions
window.exportToCSV = function(data, filename) {
    const csvContent = "data:text/csv;charset=utf-8," + data;
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

// Success message
console.log('Sales management script loaded without errors');
