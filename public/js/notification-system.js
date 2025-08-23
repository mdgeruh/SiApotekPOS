/**
 * Sistem Notifikasi Global untuk SiApotekPOS
 * Menyediakan fungsi notifikasi yang konsisten untuk semua jenis alert
 */

class NotificationSystem {
    constructor() {
        this.init();
    }

    init() {
        this.createNotificationContainer();
        this.setupGlobalFunctions();
    }

    createNotificationContainer() {
        // Hapus container yang sudah ada jika ada
        const existingContainer = document.getElementById('notification-container');
        if (existingContainer) {
            existingContainer.remove();
        }

        // Buat container untuk notifikasi
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            pointer-events: none;
        `;
        document.body.appendChild(container);
    }

    setupGlobalFunctions() {
        // Fungsi global untuk notifikasi
        window.showNotification = (message, type = 'info', title = null, duration = 5000) => {
            this.show(message, type, title, duration);
        };

        // Fungsi khusus untuk setiap jenis notifikasi
        window.showSuccess = (message, title = null, duration = 5000) => {
            this.show(message, 'success', title, duration);
        };

        window.showError = (message, title = null, duration = 7000) => {
            this.show(message, 'error', title, duration);
        };

        window.showWarning = (message, title = null, duration = 6000) => {
            this.show(message, 'warning', title, duration);
        };

        window.showInfo = (message, title = null, duration = 5000) => {
            this.show(message, 'info', title, duration);
        };

        // Fungsi untuk alert konfirmasi
        window.showConfirm = (message, onConfirm, onCancel = null, title = 'Konfirmasi') => {
            this.showConfirmDialog(message, onConfirm, onCancel, title);
        };

        // Fungsi untuk alert input
        window.showPrompt = (message, defaultValue = '', onConfirm, onCancel = null, title = 'Input') => {
            this.showPromptDialog(message, defaultValue, onConfirm, onCancel, title);
        };
    }

    show(message, type = 'info', title = null, duration = 5000) {
        const container = document.getElementById('notification-container');
        if (!container) {
            this.createNotificationContainer();
        }

        // Hapus notifikasi lama jika terlalu banyak
        const notifications = container.querySelectorAll('.notification-item');
        if (notifications.length >= 5) {
            notifications[0].remove();
        }

        // Buat elemen notifikasi
        const notification = document.createElement('div');
        notification.className = 'notification-item alert alert-dismissible fade show mb-2';
        notification.style.cssText = `
            pointer-events: auto;
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            min-width: 300px;
            max-width: 400px;
            animation: slideInRight 0.3s ease-out;
        `;

        // Set warna berdasarkan tipe
        const colors = {
            success: { bg: '#d4edda', text: '#155724', border: '#c3e6cb', icon: 'fa-check-circle' },
            error: { bg: '#f8d7da', text: '#721c24', border: '#f5c6cb', icon: 'fa-exclamation-triangle' },
            warning: { bg: '#fff3cd', text: '#856404', border: '#ffeaa7', icon: 'fa-exclamation-triangle' },
            info: { bg: '#d1ecf1', text: '#0c5460', border: '#bee5eb', icon: 'fa-info-circle' }
        };

        const color = colors[type] || colors.info;
        notification.style.backgroundColor = color.bg;
        notification.style.color = color.text;
        notification.style.borderLeft = `4px solid ${color.border}`;

        // Buat konten notifikasi
        const iconClass = color.icon;
        const alertClass = type === 'success' ? 'success' :
                          type === 'error' ? 'danger' :
                          type === 'warning' ? 'warning' : 'info';

        notification.className = `notification-item alert alert-${alertClass} alert-dismissible fade show mb-2`;

        notification.innerHTML = `
            <div class="d-flex align-items-start">
                <div class="flex-shrink-0 me-2">
                    <i class="fas ${iconClass}" style="font-size: 1.1rem; margin-top: 2px;"></i>
                </div>
                <div class="flex-grow-1">
                    ${title ? `<div class="fw-bold mb-1">${title}</div>` : ''}
                    <div class="notification-message">${message}</div>
                </div>
                <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        // Tambahkan ke container
        container.appendChild(notification);

        // Auto remove setelah durasi tertentu
        if (duration > 0) {
            setTimeout(() => {
                if (notification.parentNode) {
                    this.removeNotification(notification);
                }
            }, duration);
        }

        // Event listener untuk tombol close
        const closeBtn = notification.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.removeNotification(notification);
            });
        }

        return notification;
    }

    removeNotification(notification) {
        notification.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }

    showConfirmDialog(message, onConfirm, onCancel = null, title = 'Konfirmasi') {
        // Hapus dialog konfirmasi yang sudah ada
        const existingDialog = document.getElementById('confirm-dialog');
        if (existingDialog) {
            existingDialog.remove();
        }

        const dialog = document.createElement('div');
        dialog.id = 'confirm-dialog';
        dialog.className = 'modal fade';
        dialog.setAttribute('tabindex', '-1');
        dialog.setAttribute('aria-hidden', 'true');

        dialog.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary confirm-btn">Ya, Lanjutkan</button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(dialog);

        // Bootstrap modal
        const modal = new bootstrap.Modal(dialog);
        modal.show();

        // Event listeners
        const confirmBtn = dialog.querySelector('.confirm-btn');
        const cancelBtn = dialog.querySelector('.btn-secondary');

        confirmBtn.addEventListener('click', () => {
            modal.hide();
            if (onConfirm) onConfirm();
        });

        cancelBtn.addEventListener('click', () => {
            modal.hide();
            if (onCancel) onCancel();
        });

        // Cleanup setelah modal ditutup
        dialog.addEventListener('hidden.bs.modal', () => {
            dialog.remove();
        });
    }

    showPromptDialog(message, defaultValue = '', onConfirm, onCancel = null, title = 'Input') {
        // Hapus dialog prompt yang sudah ada
        const existingDialog = document.getElementById('prompt-dialog');
        if (existingDialog) {
            existingDialog.remove();
        }

        const dialog = document.createElement('div');
        dialog.id = 'prompt-dialog';
        dialog.className = 'modal fade';
        dialog.setAttribute('tabindex', '-1');
        dialog.setAttribute('aria-hidden', 'true');

        dialog.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">${message}</p>
                        <input type="text" class="form-control prompt-input" value="${defaultValue}" placeholder="Masukkan nilai...">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary confirm-btn">OK</button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(dialog);

        // Bootstrap modal
        const modal = new bootstrap.Modal(dialog);
        modal.show();

        // Focus pada input
        setTimeout(() => {
            const input = dialog.querySelector('.prompt-input');
            if (input) {
                input.focus();
                input.select();
            }
        }, 500);

        // Event listeners
        const confirmBtn = dialog.querySelector('.confirm-btn');
        const cancelBtn = dialog.querySelector('.btn-secondary');
        const input = dialog.querySelector('.prompt-input');

        confirmBtn.addEventListener('click', () => {
            const value = input.value.trim();
            modal.hide();
            if (onConfirm) onConfirm(value);
        });

        cancelBtn.addEventListener('click', () => {
            modal.hide();
            if (onCancel) onCancel();
        });

        // Enter key untuk konfirmasi
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const value = input.value.trim();
                modal.hide();
                if (onConfirm) onConfirm(value);
            }
        });

        // Cleanup setelah modal ditutup
        dialog.addEventListener('hidden.bs.modal', () => {
            dialog.remove();
        });
    }

    // Fungsi untuk menampilkan notifikasi dari response AJAX
    showAjaxResponse(response, defaultMessage = 'Operasi berhasil') {
        if (response.success !== undefined) {
            if (response.success) {
                this.showSuccess(response.message || defaultMessage);
            } else {
                this.showError(response.message || 'Terjadi kesalahan');
            }
        } else if (response.message) {
            this.showInfo(response.message);
        }
    }

    // Fungsi untuk menampilkan error dari exception
    showException(error, defaultMessage = 'Terjadi kesalahan sistem') {
        let message = defaultMessage;

        if (error.response && error.response.data) {
            const data = error.response.data;
            if (data.message) {
                message = data.message;
            } else if (data.errors) {
                const errorMessages = Object.values(data.errors).flat();
                message = errorMessages.join(', ');
            }
        } else if (error.message) {
            message = error.message;
        }

        this.showError(message);
    }
}

// Inisialisasi sistem notifikasi
document.addEventListener('DOMContentLoaded', function() {
    window.notificationSystem = new NotificationSystem();
});

// Tambahkan CSS untuk animasi
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .notification-item {
        transition: all 0.3s ease;
    }

    .notification-item:hover {
        transform: translateX(-5px);
    }
`;
document.head.appendChild(style);
