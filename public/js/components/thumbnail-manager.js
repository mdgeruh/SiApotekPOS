/**
 * Thumbnail Manager for Circular Thumbnails
 * Handles upload, preview, and management of circular thumbnails
 */

class ThumbnailManager {
    constructor() {
        this.initializeEventListeners();
        this.setupDragAndDrop();
    }

    initializeEventListeners() {
        // Logo upload
        const logoInput = document.getElementById('logo');
        if (logoInput) {
            logoInput.addEventListener('change', (e) => this.handleFileUpload(e, 'logo'));
        }

        // Favicon upload
        const faviconInput = document.getElementById('favicon');
        if (faviconInput) {
            faviconInput.addEventListener('change', (e) => this.handleFileUpload(e, 'favicon'));
        }

        // Remove buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.btn-remove-logo')) {
                this.removeThumbnail('logo');
            }
            if (e.target.closest('.btn-remove-favicon')) {
                this.removeThumbnail('favicon');
            }
        });
    }

    setupDragAndDrop() {
        document.querySelectorAll('.upload-area').forEach(area => {
            area.addEventListener('dragover', (e) => {
                e.preventDefault();
                area.classList.add('dragover');
            });

            area.addEventListener('dragleave', () => {
                area.classList.remove('dragover');
            });

            area.addEventListener('drop', (e) => {
                e.preventDefault();
                area.classList.remove('dragover');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const input = area.parentElement.querySelector('input[type="file"]');
                    if (input) {
                        input.files = files;
                        input.dispatchEvent(new Event('change'));
                    }
                }
            });
        });
    }

    handleFileUpload(event, type) {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file
        if (!this.validateFile(file, type)) {
            return;
        }

        // Show loading state
        this.showLoadingState(type);

        // Preview file
        this.showPreview(file, type);
    }

    validateFile(file, type) {
        const maxSize = type === 'logo' ? 2 * 1024 * 1024 : 1 * 1024 * 1024; // 2MB for logo, 1MB for favicon
        const allowedTypes = type === 'logo'
            ? ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml']
            : ['image/x-icon', 'image/png', 'image/jpg', 'image/jpeg'];

        if (file.size > maxSize) {
            this.showError(`File terlalu besar. Maksimal ${type === 'logo' ? '2MB' : '1MB'}`);
            return false;
        }

        if (!allowedTypes.includes(file.type)) {
            this.showError(`Tipe file tidak didukung. Gunakan: ${allowedTypes.join(', ')}`);
            return false;
        }

        return true;
    }

    showPreview(file, type) {
        const reader = new FileReader();

        reader.onload = (e) => {
            this.createPreviewElement(e.target.result, file.name, type);
        };

        reader.readAsDataURL(file);
    }

    createPreviewElement(dataUrl, fileName, type) {
        // Remove existing preview
        this.removePreview(type);

        // Create preview container
        const preview = document.createElement('div');
        preview.className = 'thumbnail-container';
        preview.id = `${type}-preview`;

        preview.innerHTML = `
            <img src="${dataUrl}" alt="${type} Preview" class="thumbnail-preview">
            <div class="thumbnail-info">
                <div class="thumbnail-label">Preview: ${fileName}</div>
                <div class="thumbnail-actions">
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-thumbnail" onclick="thumbnailManager.removePreview('${type}')">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </div>
        `;

        // Insert preview
        const input = document.getElementById(type);
        if (input && input.parentNode) {
            input.parentNode.appendChild(preview);
        }

        // Add success state
        setTimeout(() => {
            const previewImg = preview.querySelector('.thumbnail-preview');
            if (previewImg) {
                previewImg.classList.add('success');
            }
        }, 100);
    }

    removePreview(type) {
        const preview = document.getElementById(`${type}-preview`);
        if (preview) {
            preview.remove();
        }

        // Clear file input
        const input = document.getElementById(type);
        if (input) {
            input.value = '';
        }
    }

    removeThumbnail(type) {
        if (confirm(`Apakah Anda yakin ingin menghapus ${type} saat ini?`)) {
            // Set hidden input for removal
            const removeInput = document.getElementById(`remove_${type}`);
            if (removeInput) {
                removeInput.value = '1';
            }

            // Hide the thumbnail display
            const container = document.querySelector(`[data-thumbnail="${type}"]`);
            if (container) {
                container.style.display = 'none';
            }

            // Show success message
            this.showSuccess(`${type} berhasil dihapus`);
        }
    }

    showLoadingState(type) {
        const container = document.querySelector(`[data-thumbnail="${type}"]`);
        if (container) {
            const img = container.querySelector('.thumbnail-circle');
            if (img) {
                img.classList.add('loading');
            }
        }
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showError(message) {
        // Gunakan sistem notifikasi global jika tersedia
        if (window.showError) {
            window.showError(message);
        } else {
            this.showNotification(message, 'error');
        }
    }

    showSuccess(message) {
        // Gunakan sistem notifikasi global jika tersedia
        if (window.showSuccess) {
            window.showSuccess(message);
        } else {
            this.showNotification(message, 'success');
        }
    }

    showNotification(message, type) {
        // Gunakan sistem notifikasi global jika tersedia
        if (window.showNotification) {
            window.showNotification(message, type);
            return;
        }

        // Fallback ke sistem lama jika sistem global belum tersedia
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';

        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Add to body
        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Public methods
    static getInstance() {
        if (!ThumbnailManager.instance) {
            ThumbnailManager.instance = new ThumbnailManager();
        }
        return ThumbnailManager.instance;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.thumbnailManager = ThumbnailManager.getInstance();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ThumbnailManager;
}
