@extends('layouts.app')

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengaturan</h1>
        <a href="{{ route('profile') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-fw"></i> Kembali ke Profil
        </a>
    </div>

    <div class="row">
        <!-- Notification Settings -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan Notifikasi</h6>
                    <i class="fas fa-bell fa-fw text-primary"></i>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notification_email" name="notification_email" value="1"
                                       {{ ($user->settings['notification_email'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="notification_email">
                                    <i class="fas fa-envelope fa-fw me-2 text-primary"></i>
                                    Notifikasi Email
                                </label>
                                <small class="form-text text-muted d-block">
                                    Terima notifikasi melalui email untuk aktivitas penting
                                </small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notification_sms" name="notification_sms" value="1"
                                       {{ ($user->settings['notification_sms'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="notification_sms">
                                    <i class="fas fa-sms fa-fw me-2 text-success"></i>
                                    Notifikasi SMS
                                </label>
                                <small class="form-text text-muted d-block">
                                    Terima notifikasi melalui SMS untuk alert darurat
                                </small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notification_push" name="notification_push" value="1" checked>
                                <label class="form-check-label" for="notification_push">
                                    <i class="fas fa-mobile-alt fa-fw me-2 text-info"></i>
                                    Notifikasi Push Browser
                                </label>
                                <small class="form-text text-muted d-block">
                                    Terima notifikasi real-time di browser
                                </small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save fa-fw"></i> Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Language & Timezone Settings -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan Lokal</h6>
                    <i class="fas fa-globe fa-fw text-primary"></i>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="language" class="form-label">Bahasa</label>
                            <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                                <option value="id" {{ ($user->settings['language'] ?? 'id') == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                <option value="en" {{ ($user->settings['language'] ?? 'id') == 'en' ? 'selected' : '' }}>English</option>
                            </select>
                            <small class="form-text text-muted">Pilih bahasa yang digunakan dalam aplikasi</small>
                            @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="timezone" class="form-label">Zona Waktu</label>
                            <select class="form-select @error('timezone') is-invalid @enderror" id="timezone" name="timezone">
                                <option value="Asia/Jakarta" {{ ($user->settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>WIB (UTC+7)</option>
                                <option value="Asia/Makassar" {{ ($user->settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Makassar' ? 'selected' : '' }}>WITA (UTC+8)</option>
                                <option value="Asia/Jayapura" {{ ($user->settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jayapura' ? 'selected' : '' }}>WIT (UTC+9)</option>
                            </select>
                            <small class="form-text text-muted">Pilih zona waktu sesuai lokasi Anda</small>
                            @error('timezone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save fa-fw"></i> Simpan Pengaturan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Security Settings -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan Keamanan</h6>
                    <i class="fas fa-shield-alt fa-fw text-primary"></i>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="two_factor_auth" name="two_factor_auth" value="1">
                            <label class="form-check-label" for="two_factor_auth">
                                <i class="fas fa-mobile-alt fa-fw me-2 text-warning"></i>
                                Autentikasi 2 Faktor
                            </label>
                            <small class="form-text text-muted d-block">
                                Tambahkan lapisan keamanan ekstra dengan kode SMS/Email
                            </small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="session_timeout" name="session_timeout" value="1" checked>
                            <label class="form-check-label" for="session_timeout">
                                <i class="fas fa-clock fa-fw me-2 text-info"></i>
                                Timeout Session Otomatis
                            </label>
                            <small class="form-text text-muted d-block">
                                Logout otomatis setelah 30 menit tidak aktif
                            </small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="login_notification" name="login_notification" value="1" checked>
                            <label class="form-check-label" for="login_notification">
                                <i class="fas fa-sign-in-alt fa-fw me-2 text-success"></i>
                                Notifikasi Login
                            </label>
                            <small class="form-text text-muted d-block">
                                Terima notifikasi setiap kali ada login baru
                            </small>
                        </div>
                    </div>

                    <button type="button" class="btn btn-warning" onclick="saveSecuritySettings()">
                        <i class="fas fa-save fa-fw"></i> Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>

        <!-- Display Settings -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan Tampilan</h6>
                    <i class="fas fa-palette fa-fw text-primary"></i>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="mb-3">
                        <label for="theme" class="form-label">Tema</label>
                        <select class="form-select" id="theme" name="theme">
                            <option value="light">Light Mode</option>
                            <option value="dark">Dark Mode</option>
                            <option value="auto">Auto (Sesuai Sistem)</option>
                        </select>
                        <small class="form-text text-muted">Pilih tema yang nyaman untuk mata</small>
                    </div>

                    <div class="mb-3">
                        <label for="sidebar_collapsed" class="form-label">Sidebar</label>
                        <select class="form-select" id="sidebar_collapsed" name="sidebar_collapsed">
                            <option value="expanded">Selalu Terbuka</option>
                            <option value="collapsed">Selalu Tertutup</option>
                            <option value="auto">Ingat Status Terakhir</option>
                        </select>
                        <small class="form-text text-muted">Atur perilaku sidebar</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="compact_mode" name="compact_mode" value="1">
                            <label class="form-check-label" for="compact_mode">
                                <i class="fas fa-compress-alt fa-fw me-2 text-secondary"></i>
                                Mode Kompak
                            </label>
                            <small class="form-text text-muted d-block">
                                Tampilkan lebih banyak konten dalam layar yang sama
                            </small>
                        </div>
                    </div>

                    <button type="button" class="btn btn-info" onclick="saveDisplaySettings()">
                        <i class="fas fa-save fa-fw"></i> Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Export Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Ekspor Data</h6>
                    <i class="fas fa-download fa-fw text-primary"></i>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <i class="fas fa-user fa-3x text-primary mb-3"></i>
                                <h6>Data Profil</h6>
                                <p class="text-muted">Ekspor informasi profil pribadi</p>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportProfileData()">
                                    <i class="fas fa-download fa-fw"></i> Ekspor
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                                <h6>Riwayat Aktivitas</h6>
                                <p class="text-muted">Ekspor log aktivitas lengkap</p>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="exportActivityData()">
                                    <i class="fas fa-download fa-fw"></i> Ekspor
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <i class="fas fa-cog fa-3x text-info mb-3"></i>
                                <h6>Pengaturan</h6>
                                <p class="text-muted">Ekspor konfigurasi pengaturan</p>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="exportSettingsData()">
                                    <i class="fas fa-download fa-fw"></i> Ekspor
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize settings from user preferences
    initializeSettings();
});

function initializeSettings() {
    // Load saved settings from localStorage or user preferences
    const savedTheme = localStorage.getItem('theme') || 'light';
    const savedSidebar = localStorage.getItem('sidebar_collapsed') || 'auto';

    document.getElementById('theme').value = savedTheme;
    document.getElementById('sidebar_collapsed').value = savedSidebar;

    // Apply theme
    applyTheme(savedTheme);
}

function applyTheme(theme) {
    const body = document.body;
    body.className = body.className.replace(/theme-\w+/, '');
    body.classList.add(`theme-${theme}`);
    localStorage.setItem('theme', theme);
}

function saveSecuritySettings() {
    // Collect security settings
    const settings = {
        two_factor_auth: document.getElementById('two_factor_auth').checked,
        session_timeout: document.getElementById('session_timeout').checked,
        login_notification: document.getElementById('login_notification').checked
    };

    // Save to localStorage for demo
    localStorage.setItem('securitySettings', JSON.stringify(settings));

    showNotification('Pengaturan keamanan berhasil disimpan!', 'success');
}

function saveDisplaySettings() {
    // Collect display settings
    const theme = document.getElementById('theme').value;
    const sidebar = document.getElementById('sidebar_collapsed').value;
    const compactMode = document.getElementById('compact_mode').checked;

    // Apply theme immediately
    applyTheme(theme);

    // Save to localStorage
    localStorage.setItem('sidebar_collapsed', sidebar);
    localStorage.setItem('compact_mode', compactMode);

    showNotification('Pengaturan tampilan berhasil disimpan!', 'success');
}

function exportProfileData() {
    showNotification('Mengekspor data profil...', 'info');
    // Simulate export process
    setTimeout(() => {
        showNotification('Data profil berhasil diekspor!', 'success');
    }, 2000);
}

function exportActivityData() {
    showNotification('Mengekspor riwayat aktivitas...', 'info');
    // Simulate export process
    setTimeout(() => {
        showNotification('Riwayat aktivitas berhasil diekspor!', 'success');
    }, 2000);
}

function exportSettingsData() {
    showNotification('Mengekspor pengaturan...', 'info');
    // Simulate export process
    setTimeout(() => {
        showNotification('Pengaturan berhasil diekspor!', 'success');
    }, 2000);
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Theme change handler
document.getElementById('theme').addEventListener('change', function() {
    applyTheme(this.value);
});

// Sidebar setting handler
document.getElementById('sidebar_collapsed').addEventListener('change', function() {
    localStorage.setItem('sidebar_collapsed', this.value);
});
</script>
@endpush

@push('styles')
<style>
/* Theme styles */
.theme-dark {
    background-color: #1a1a1a !important;
    color: #ffffff !important;
}

.theme-dark .card {
    background-color: #2d2d2d !important;
    border-color: #404040 !important;
}

.theme-dark .card-header {
    background-color: #404040 !important;
    border-bottom-color: #505050 !important;
}

.theme-dark .form-control,
.theme-dark .form-select {
    background-color: #404040 !important;
    border-color: #505050 !important;
    color: #ffffff !important;
}

.theme-dark .form-control:focus,
.theme-dark .form-select:focus {
    background-color: #505050 !important;
    border-color: #4e73df !important;
}

/* Compact mode */
.compact-mode .card-body {
    padding: 0.75rem;
}

.compact-mode .mb-3 {
    margin-bottom: 0.75rem !important;
}

.compact-mode .form-label {
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

/* Settings card enhancements */
.card .card-header i {
    font-size: 1.2rem;
}

.form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

.form-check-input:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

/* Export section styling */
.text-center.p-3 {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    margin: 0.5rem;
    transition: all 0.3s ease;
}

.text-center.p-3:hover {
    border-color: #4e73df;
    box-shadow: 0 4px 15px rgba(78, 115, 223, 0.1);
    transform: translateY(-2px);
}

.text-center.p-3 i {
    transition: all 0.3s ease;
}

.text-center.p-3:hover i {
    transform: scale(1.1);
}
</style>
@endpush
