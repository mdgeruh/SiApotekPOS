@extends('layouts.app')

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ganti Password</h1>
        <a href="{{ route('profile') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-fw"></i> Kembali ke Profil
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Form Ganti Password</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">

                    <form action="{{ route('profile.change-password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Minimal 8 karakter dengan kombinasi huruf, angka, dan simbol.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile') }}" class="btn btn-secondary">
                                <i class="fas fa-times fa-fw"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key fa-fw"></i> Ganti Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Persyaratan Password</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Minimal 8 karakter
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Kombinasi huruf besar dan kecil
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Minimal 1 angka
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Minimal 1 simbol khusus
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Password tidak boleh sama dengan password lama
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    function setupPasswordToggle(toggleId, inputId) {
        const toggle = document.getElementById(toggleId);
        const input = document.getElementById(inputId);

        if (toggle && input) {
            toggle.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
    }

    setupPasswordToggle('toggleCurrentPassword', 'current_password');
    setupPasswordToggle('toggleNewPassword', 'password');
    setupPasswordToggle('toggleConfirmPassword', 'password_confirmation');

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                symbol: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };

            // Update requirement indicators
            Object.keys(requirements).forEach(req => {
                const icon = document.querySelector(`[data-requirement="${req}"]`);
                if (icon) {
                    if (requirements[req]) {
                        icon.classList.remove('text-muted');
                        icon.classList.add('text-success');
                    } else {
                        icon.classList.remove('text-success');
                        icon.classList.add('text-muted');
                    }
                }
            });
        });
    }
});
</script>
@endpush
