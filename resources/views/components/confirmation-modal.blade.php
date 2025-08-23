<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmationModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                </div>
                <h6 class="fw-bold" id="confirmationModalTitle">
                    Apakah Anda yakin?
                </h6>
                <p class="text-muted" id="confirmationModalMessage">
                    Data yang dihapus tidak dapat dikembalikan.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmationModalConfirmBtn">
                    <i class="fas fa-trash-alt me-2"></i>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Simple confirmation modal
let modalInstance = null;
let currentDeleteUrl = null;

// Initialize modal
function initModal() {
    console.log('=== Initializing Modal ===');

    try {
        const modalElement = document.getElementById('confirmationModal');
        if (!modalElement) {
            console.error('Modal element not found');
            return;
        }

        // Try Bootstrap 5 first
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            modalInstance = new bootstrap.Modal(modalElement);
            console.log('Modal initialized with Bootstrap 5');
        }
        // Fallback to jQuery
        else if (typeof $ !== 'undefined' && $.fn.modal) {
            modalInstance = $(modalElement);
            console.log('Modal initialized with jQuery');
        }
        else {
            console.error('No modal library available');
            return;
        }

        // Set up confirm button
        const confirmBtn = document.getElementById('confirmationModalConfirmBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                console.log('Confirm button clicked');
                if (currentDeleteUrl) {
                    submitDeleteForm(currentDeleteUrl);
                    hideModal();
                }
            });
        }

        console.log('Modal initialized successfully');
    } catch (error) {
        console.error('Error initializing modal:', error);
        modalInstance = null;
    }
}

// Show modal
function showModal() {
    if (modalInstance) {
        if (typeof modalInstance.show === 'function') {
            modalInstance.show();
        } else if (typeof modalInstance.modal === 'function') {
            modalInstance.modal('show');
        }
    }
}

// Hide modal
function hideModal() {
    if (modalInstance) {
        if (typeof modalInstance.hide === 'function') {
            modalInstance.hide();
        } else if (typeof modalInstance.modal === 'function') {
            modalInstance.modal('hide');
        }
    }
}

// Global function to show delete confirmation
function showDeleteConfirmation(url, itemName = null, customMessage = null) {
    console.log('=== showDeleteConfirmation called ===');
    console.log('URL:', url);
    console.log('Item Name:', itemName);
    console.log('Custom Message:', customMessage);
    console.log('Modal Instance:', modalInstance);

    currentDeleteUrl = url;

    // Set modal content
    const titleElement = document.getElementById('confirmationModalTitle');
    const messageElement = document.getElementById('confirmationModalMessage');

    if (titleElement && itemName) {
        titleElement.textContent = `Hapus "${itemName}"?`;
    }

    if (messageElement && customMessage) {
        messageElement.textContent = customMessage;
    }

    // Show modal
    if (modalInstance) {
        showModal();
    } else {
        console.log('Modal not initialized, using fallback');
        // Fallback to basic confirm
        const message = customMessage || (itemName ?
            `Hapus "${itemName}"?` :
            'Apakah Anda yakin?');

        if (confirm(message)) {
            submitDeleteForm(url);
        }
    }
}

// Submit delete form
function submitDeleteForm(url) {
    console.log('=== Submitting delete form ===');
    console.log('URL:', url);

    try {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.style.display = 'none';

        // Add CSRF token
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (csrfTokenElement) {
            const csrfToken = csrfTokenElement.getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            console.log('CSRF token added');
        } else {
            console.warn('CSRF token not found');
        }

        // Add method override for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        console.log('Form submitted');
        form.submit();
    } catch (error) {
        console.error('Error submitting delete form:', error);
        // Fallback: redirect to delete URL
        if (confirm('Gagal mengirim form. Lanjutkan dengan redirect?')) {
            window.location.href = url;
        }
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded, initializing modal...');
        setTimeout(initModal, 100);
    });
} else {
    console.log('DOM already loaded, initializing modal...');
    setTimeout(initModal, 100);
}

// Debug function
function checkModalStatus() {
    console.log('=== Modal Status Check ===');
    console.log('Modal Instance:', modalInstance);
    console.log('Current Delete URL:', currentDeleteUrl);
    console.log('Bootstrap Available:', typeof bootstrap !== 'undefined');
    console.log('jQuery Available:', typeof $ !== 'undefined');
    console.log('Modal Element:', document.getElementById('confirmationModal'));
}

// Make debug function available globally
window.checkModalStatus = checkModalStatus;

console.log('=== Confirmation Modal Script Loaded ===');
console.log('Script loaded at:', new Date().toISOString());
console.log('DOM ready state:', document.readyState);
console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
console.log('jQuery available:', typeof $ !== 'undefined');
console.log('Modal element exists:', document.getElementById('confirmationModal') !== null);
</script>
