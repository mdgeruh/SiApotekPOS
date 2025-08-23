@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert" style="border-radius: 8px; border-left: 4px solid #28a745; background: white;">
        <div class="d-flex align-items-start">
            <div class="flex-shrink-0 me-3">
                <div class="bg-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="fas fa-check-circle text-white" style="font-size: 14px;"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1 text-dark fs-6">Berhasil!</h6>
                <p class="mb-0 text-dark fs-6">{{ session('success') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert" style="border-radius: 8px; border-left: 4px solid #dc3545; background: white;">
        <div class="d-flex align-items-start">
            <div class="flex-shrink-0 me-3">
                <div class="bg-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="fas fa-exclamation-triangle text-white" style="font-size: 14px;"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1 text-dark fs-6">Error!</h6>
                <p class="mb-0 text-dark fs-6">{{ session('error') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert" style="border-radius: 8px; border-left: 4px solid #ffc107; background: white;">
        <div class="d-flex align-items-start">
            <div class="flex-shrink-0 me-3">
                <div class="bg-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="fas fa-exclamation-triangle text-white" style="font-size: 14px;"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1 text-dark fs-6">Peringatan!</h6>
                <p class="mb-0 text-dark fs-6">{{ session('warning') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert" style="border-radius: 8px; border-left: 4px solid #17a2b8; background: white;">
        <div class="d-flex align-items-start">
            <div class="flex-shrink-0 me-3">
                <div class="bg-info rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="fas fa-info-circle text-white" style="font-size: 14px;"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1 text-dark fs-6">Informasi!</h6>
                <p class="mb-0 text-dark fs-6">{{ session('info') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

<script>
// Auto-dismiss alerts after 6 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 6000);
    });
});

// Prevent duplicate alerts by removing existing ones before adding new ones
// Sekarang menggunakan sistem notifikasi global yang konsisten
function showAlert(type, message) {
    // Gunakan sistem notifikasi global jika tersedia
    if (window.showSuccess && window.showError && window.showWarning && window.showInfo) {
        switch(type) {
            case 'success':
                window.showSuccess(message, 'Berhasil!');
                break;
            case 'error':
                window.showError(message, 'Error!');
                break;
            case 'warning':
                window.showWarning(message, 'Peringatan!');
                break;
            case 'info':
                window.showInfo(message, 'Informasi!');
                break;
            default:
                window.showNotification(message, type);
        }
        return;
    }

    // Fallback ke sistem lama jika sistem global belum tersedia
    // Remove existing alerts of the same type
    const existingAlerts = document.querySelectorAll(`.alert-${type}`);
    existingAlerts.forEach(alert => {
        if (alert.parentNode) {
            alert.remove();
        }
    });

    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show mb-3 border-0 shadow-sm`;
    alertDiv.style.borderRadius = '8px';
    alertDiv.style.background = 'white';

    const iconClass = type === 'success' ? 'fa-check-circle' :
                     type === 'error' ? 'fa-exclamation-triangle' :
                     type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';

    const bgClass = type === 'success' ? 'bg-success' :
                   type === 'error' ? 'bg-danger' :
                   type === 'warning' ? 'bg-warning' : 'bg-info';

    const borderColor = type === 'success' ? '#28a745' :
                       type === 'error' ? '#dc3545' :
                       type === 'warning' ? '#ffc107' : '#17a2b8';

    const title = type === 'success' ? 'Berhasil!' :
                 type === 'error' ? 'Error!' :
                 type === 'warning' ? 'Peringatan!' : 'Informasi!';

    alertDiv.innerHTML = `
        <div class="d-flex align-items-start" style="border-left: 4px solid ${borderColor};">
            <div class="flex-shrink-0 me-3">
                <div class="${bgClass} rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="fas ${iconClass} text-white" style="font-size: 14px;"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1 text-dark fs-6">${title}</h6>
                <p class="mb-0 text-dark fs-6">${message}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    // Insert at the top of the content area
    const container = document.querySelector('.container-fluid') || document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);

        // Auto-dismiss after 6 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }
        }, 6000);
    }
}
</script>
