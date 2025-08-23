@extends('layouts.auth')

@section('content')
<div class="register-container">
    <!-- Left Side - Welcome Cover -->
    <div class="register-cover">
        <div class="cover-content">
            <div class="brand-section">
                <div class="brand-logo">
                    <i class="fas fa-clinic-medical"></i>
                </div>
                <h1 class="brand-title">{{ \App\Helpers\AppSettingHelper::pharmacyName() ?: 'Apotek' }}</h1>
                <p class="brand-subtitle">Sistem Manajemen Apotek Terpadu</p>
            </div>

            <div class="welcome-message">
                <h2>Bergabung Bersama Kami!</h2>
                <p>Buat akun baru untuk mengakses sistem manajemen apotek yang lengkap dan terintegrasi</p>
            </div>

            <div class="feature-highlights">
                <div class="feature-item">
                    <i class="fas fa-user-shield"></i>
                    <span>Keamanan Terjamin</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-rocket"></i>
                    <span>Setup Cepat</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-headset"></i>
                    <span>Support 24/7</span>
                </div>
            </div>

            <div class="cover-footer">
                <p>&copy; {{ date('Y') }} {{ \App\Helpers\AppSettingHelper::pharmacyName() ?: 'Apotek' }}. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Right Side - Register Form -->
    <div class="register-form-container">
        <div class="form-wrapper">
            <div class="form-header">
                <h2>Buat Akun Baru</h2>
                <p>Silakan isi data diri Anda untuk membuat akun baru</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="register-form">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-2"></i>
                            Nama Lengkap
                        </label>
                        <input id="name" type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}"
                               required autocomplete="name" autofocus
                               placeholder="Masukkan nama lengkap">
                        @error('name')
                            <div class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-at me-2"></i>
                            Username
                        </label>
                        <input id="username" type="text"
                               class="form-control @error('username') is-invalid @enderror"
                               name="username" value="{{ old('username') }}"
                               required autocomplete="username"
                               placeholder="Masukkan username">
                        @error('username')
                            <div class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>
                            Email Address
                        </label>
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}"
                               required autocomplete="email"
                               placeholder="Masukkan email">
                        @error('email')
                            <div class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone me-2"></i>
                            Nomor Telepon
                        </label>
                        <input id="phone" type="text"
                               class="form-control @error('phone') is-invalid @enderror"
                               name="phone" value="{{ old('phone') }}"
                               required autocomplete="tel"
                               placeholder="Masukkan nomor telepon">
                        @error('phone')
                            <div class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>
                            Password
                        </label>
                        <div class="password-input-group">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="new-password"
                                   placeholder="Masukkan password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-2"></i>
                            Konfirmasi Password
                        </label>
                        <div class="password-input-group">
                            <input id="password_confirmation" type="password"
                                   class="form-control" name="password_confirmation"
                                   required autocomplete="new-password"
                                   placeholder="Konfirmasi password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="passwordConfirmationIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-register">
                        <i class="fas fa-user-plus me-2"></i>
                        Buat Akun
                    </button>
                </div>

                <div class="login-section">
                    <p class="text-center">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="login-link">
                            Masuk sekarang
                        </a>
                    </p>
                </div>
            </form>
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

.register-container {
    display: flex;
    width: 100vw;
    height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Left Side - Welcome Cover */
.register-cover {
    flex: 1;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    min-height: 100vh;
}

.register-cover::before {
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

/* Right Side - Register Form */
.register-form-container {
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

.form-row {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    flex: 1;
}

.form-label {
    display: block;
    margin-bottom: 0.75rem;
    font-weight: 600;
    color: #2d3748;
    font-size: 1rem;
}

.form-control {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e2e8f0;
    border-radius: 0.75rem;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    background: #f7fafc;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-control.is-invalid {
    border-color: #e53e3e;
    background: #fed7d7;
}

.password-input-group {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #718096;
    cursor: pointer;
    padding: 0.5rem;
    transition: color 0.3s ease;
    font-size: 1.1rem;
}

.password-toggle:hover {
    color: #667eea;
}

.form-actions {
    margin-bottom: 2rem;
}

.btn-register {
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

.btn-register:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-register:active {
    transform: translateY(-1px);
}

.login-section {
    text-align: center;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}

.login-section p {
    color: #718096;
    margin: 0;
    font-size: 1rem;
}

.login-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.login-link:hover {
    color: #5a67d8;
    text-decoration: underline;
}

.invalid-feedback {
    display: block;
    margin-top: 0.75rem;
    color: #e53e3e;
    font-size: 0.9rem;
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
    .register-container {
        flex-direction: column;
    }

    .register-cover {
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

    .register-form-container {
        padding: 2rem 1.5rem;
        min-height: 65vh;
    }

    .form-wrapper {
        max-width: 100%;
    }

    .form-row {
        flex-direction: column;
        gap: 1rem;
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

    .register-form-container {
        padding: 1.5rem 1rem;
    }

    .form-row {
        flex-direction: column;
        gap: 1rem;
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
.btn-register:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.btn-register.loading {
    position: relative;
    color: transparent;
}

.btn-register.loading::after {
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
    const form = document.querySelector('.register-form');
    const submitBtn = document.querySelector('.btn-register');

    // Password toggle functionality
    window.togglePassword = function(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const iconId = fieldId === 'password' ? 'passwordIcon' : 'passwordConfirmationIcon';
        const passwordIcon = document.getElementById(iconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    };

    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

        // Form will submit naturally
        // Loading state will be cleared on page reload/redirect
    });

    // Auto-focus on name field
    document.getElementById('name').focus();

    // Smooth transitions for form elements
    const inputs = form.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
});
</script>
@endsection
