@extends('layouts.auth')

@section('content')
<div class="verify-container">
    <!-- Left Side - Welcome Cover -->
    <div class="verify-cover">
        <div class="cover-content">
            <div class="brand-section">
                <div class="brand-logo">
                    <i class="fas fa-clinic-medical"></i>
                </div>
                <h1 class="brand-title">{{ \App\Helpers\AppSettingHelper::pharmacyName() ?: 'Apotek' }}</h1>
                <p class="brand-subtitle">Sistem Manajemen Apotek Terpadu</p>
            </div>

            <div class="welcome-message">
                <h2>Verifikasi Email Anda</h2>
                <p>Langkah terakhir untuk mengaktifkan akun dan mengakses sistem manajemen apotek</p>
            </div>

            <div class="feature-highlights">
                <div class="feature-item">
                    <i class="fas fa-shield-check"></i>
                    <span>Keamanan Terjamin</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-envelope-open"></i>
                    <span>Verifikasi Cepat</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-user-check"></i>
                    <span>Akses Penuh</span>
                </div>
            </div>

            <div class="cover-footer">
                <p>&copy; {{ date('Y') }} {{ \App\Helpers\AppSettingHelper::pharmacyName() ?: 'Apotek' }}. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Right Side - Verify Form -->
    <div class="verify-form-container">
        <div class="form-wrapper">
            <div class="form-header">
                <h2>Verifikasi Email</h2>
                <p>Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi</p>
            </div>

            <div class="verify-content">
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        Link verifikasi baru telah dikirim ke email Anda.
                    </div>
                @endif

                <div class="verify-info">
                    <div class="info-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h3>Periksa Email Anda</h3>
                    <p>Kami telah mengirimkan link verifikasi ke email yang Anda daftarkan. Silakan buka email dan klik link verifikasi untuk mengaktifkan akun.</p>
                </div>

                <div class="verify-actions">
                    <form method="POST" action="{{ route('verification.resend') }}" class="resend-form">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-resend">
                            <i class="fas fa-paper-plane me-2"></i>
                            Kirim Ulang Link Verifikasi
                        </button>
                    </form>

                    <div class="logout-section">
                        <a class="btn btn-outline-secondary btn-logout" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>

                <div class="help-section">
                    <h4>Butuh Bantuan?</h4>
                    <p>Jika Anda tidak menerima email verifikasi atau mengalami masalah, silakan:</p>
                    <ul>
                        <li>Periksa folder spam/junk mail</li>
                        <li>Pastikan email yang dimasukkan sudah benar</li>
                        <li>Hubungi administrator sistem</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Reset dan base styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    overflow: hidden;
}

.verify-container {
    display: flex;
    width: 100vw;
    height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Left Side - Welcome Cover */
.verify-cover {
    flex: 1;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    min-height: 100vh;
}

.verify-cover::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.cover-content {
    text-align: center;
    color: white;
    z-index: 2;
    position: relative;
    padding: 2rem;
    max-width: 600px;
    width: 100%;
}

.brand-section {
    margin-bottom: 4rem;
}

.brand-logo {
    font-size: 5rem;
    margin-bottom: 1.5rem;
    opacity: 0.9;
}

.brand-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.brand-subtitle {
    font-size: 1.3rem;
    opacity: 0.9;
    font-weight: 300;
}

.welcome-message {
    margin-bottom: 4rem;
}

.welcome-message h2 {
    font-size: 2.2rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.welcome-message p {
    font-size: 1.2rem;
    opacity: 0.9;
    line-height: 1.6;
}

.feature-highlights {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin-bottom: 4rem;
    flex-wrap: wrap;
}

.feature-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    opacity: 0.9;
}

.feature-item i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.feature-item span {
    font-size: 1.1rem;
    font-weight: 500;
}

.cover-footer {
    opacity: 0.7;
    font-size: 1rem;
}

/* Right Side - Verify Form */
.verify-form-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    padding: 3rem;
    min-height: 100vh;
    overflow-y: auto;
}

.form-wrapper {
    width: 100%;
    max-width: 500px;
}

.form-header {
    text-align: center;
    margin-bottom: 3rem;
}

.form-header h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.form-header p {
    color: #718096;
    font-size: 1.1rem;
}

.verify-content {
    text-align: center;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    margin-bottom: 2rem;
    font-size: 1rem;
}

.alert-success {
    background-color: #d1fae5;
    border: 1px solid #10b981;
    color: #065f46;
}

.verify-info {
    margin-bottom: 3rem;
}

.info-icon {
    font-size: 4rem;
    color: #667eea;
    margin-bottom: 1.5rem;
}

.verify-info h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
}

.verify-info p {
    color: #718096;
    font-size: 1.1rem;
    line-height: 1.6;
}

.verify-actions {
    margin-bottom: 3rem;
}

.resend-form {
    margin-bottom: 2rem;
}

.btn-resend {
    width: 100%;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
}

.btn-resend:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-resend:active {
    transform: translateY(-1px);
}

.logout-section {
    margin-bottom: 2rem;
}

.btn-logout {
    width: 100%;
    padding: 0.875rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 0.75rem;
    border: 2px solid #e2e8f0;
    background: white;
    color: #718096;
    transition: all 0.3s ease;
}

.btn-logout:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
    color: #4a5568;
}

.help-section {
    text-align: left;
    padding: 2rem;
    background: #f7fafc;
    border-radius: 0.75rem;
    border: 1px solid #e2e8f0;
}

.help-section h4 {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
}

.help-section p {
    color: #718096;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.help-section ul {
    color: #718096;
    font-size: 1rem;
    padding-left: 1.5rem;
}

.help-section li {
    margin-bottom: 0.5rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .brand-title {
        font-size: 2.5rem;
    }

    .welcome-message h2 {
        font-size: 2rem;
    }

    .feature-highlights {
        gap: 2rem;
    }
}

@media (max-width: 768px) {
    .verify-container {
        flex-direction: column;
    }

    .verify-cover {
        min-height: 35vh;
        padding: 2rem 1rem;
    }

    .brand-logo {
        font-size: 3.5rem;
    }

    .brand-title {
        font-size: 2rem;
    }

    .welcome-message h2 {
        font-size: 1.8rem;
    }

    .feature-highlights {
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .verify-form-container {
        padding: 2rem 1.5rem;
        min-height: 65vh;
    }

    .form-wrapper {
        max-width: 100%;
    }
}

@media (max-width: 480px) {
    .cover-content {
        padding: 1.5rem;
    }

    .brand-logo {
        font-size: 3rem;
    }

    .brand-title {
        font-size: 1.8rem;
    }

    .welcome-message h2 {
        font-size: 1.6rem;
    }

    .feature-highlights {
        flex-direction: column;
        gap: 1rem;
    }

    .verify-form-container {
        padding: 1.5rem 1rem;
    }

    .help-section {
        padding: 1.5rem;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-wrapper {
    animation: fadeInUp 0.6s ease-out;
}

.cover-content {
    animation: fadeInUp 0.8s ease-out;
}

/* Loading state for button */
.btn-resend:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.btn-resend.loading {
    position: relative;
    color: transparent;
}

.btn-resend.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 1.2rem;
    height: 1.2rem;
    margin: -0.6rem 0 0 -0.6rem;
    border: 2px solid transparent;
    border-top-color: white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resendForm = document.querySelector('.resend-form');
    const submitBtn = document.querySelector('.btn-resend');

    // Form submission with loading state
    if (resendForm && submitBtn) {
        resendForm.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';

            // Form will submit naturally
            // Loading state will be cleared on page reload/redirect
        });
    }

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
});
</script>
@endsection
