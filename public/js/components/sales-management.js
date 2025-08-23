/**
 * Sales Management JavaScript
 * Simple functionality for the sales system
 */

document.addEventListener('livewire:load', function () {
    // Auto-focus on search input
    const searchInput = document.getElementById('searchQuery');
    if (searchInput) {
        searchInput.focus();
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl + K untuk focus ke search
        if (e.ctrlKey && e.key === 'k') {
            e.preventDefault();
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
});

// Payment input handler
function handlePaymentInput(input) {
    // Remove non-digit characters
    let value = input.value.replace(/[^0-9]/g, '');
    
    // Convert to number
    let numValue = parseInt(value) || 0;
    
    // Update input value
    input.value = numValue;
    
    // Trigger Livewire update
    if (window.Livewire) {
        const component = document.querySelector('[wire\\:id]');
        if (component) {
            const componentId = component.getAttribute('wire:id');
            window.Livewire.find(componentId).set('paidAmount', numValue);
            window.Livewire.find(componentId).call('calculateChange');
        }
    }
}

// Format payment input when user finishes typing
function formatPaymentInput(input) {
    let value = input.value.replace(/[^0-9]/g, '');
    let numValue = parseInt(value) || 0;
    
    // Format dengan separator ribuan
    if (numValue > 0) {
        input.value = numValue.toLocaleString('id-ID');
    } else {
        input.value = '';
    }
}

// Auto-focus on payment input when form appears
document.addEventListener('livewire:load', function() {
    Livewire.on('paymentFormShown', function() {
        setTimeout(function() {
            const paymentInput = document.getElementById('paidAmountInput');
            if (paymentInput) {
                paymentInput.focus();
                paymentInput.select();
            }
        }, 100);
    });
});

// Utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function validateNumber(input, min = 0, max = 999999) {
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
}
