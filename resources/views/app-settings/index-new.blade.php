@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/components/app-settings.css') }}">
<style>
    .alert {
        position: relative;
        z-index: 1050;
    }

    .alert-success {
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        border-left: 4px solid #dc3545;
    }

    /* Prevent duplicate alerts */
    .alert + .alert {
        margin-top: 0.5rem;
    }

    /* Smooth fade out animation */
    .alert.fade-out {
        opacity: 0;
        transition: opacity 0.5s ease-out;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cogs fa-sm fa-fw me-2"></i>
            Pengaturan Aplikasi
        </h1>
    </div>

    @include('components.alerts')

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Error!</strong> Mohon periksa form di bawah ini.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle fa-sm fa-fw me-2"></i>
                        Informasi Umum
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('app-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Nama Aplikasi -->
                            <div class="col-md-6 mb-3">
                                <label for="app_name" class="form-label">
                                    <i class="fas fa-tag fa-sm fa-fw me-2"></i>
                                    Nama Aplikasi <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('app_name') is-invalid @enderror"
                                       id="app_name" name="app_name"
                                       value="{{ old('app_name', $settings->app_name ?? 'Apotek POS') }}" required>
                                @error('app_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nama Apotek -->
                            <div class="col-md-6 mb-3">
                                <label for="pharmacy_name" class="form-label">
                                    <i class="fas fa-clinic-medical fa-sm fa-fw me-2"></i>
                                    Nama Apotek
                                </label>
                                <input type="text" class="form-control @error('pharmacy_name') is-invalid @enderror"
                                       id="pharmacy_name" name="pharmacy_name"
                                       value="{{ old('pharmacy_name', $settings->pharmacy_name ?? '') }}">
                                @error('pharmacy_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Logo dan Favicon Section -->
                        <div class="row mb-4">
                            <!-- Logo -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-image fa-sm fa-fw me-2"></i>
                                    Logo Aplikasi
                                </label>

                                <div class="upload-area" onclick="document.getElementById('logo').click()">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">Klik untuk upload logo</div>
                                    <div class="upload-hint">PNG, JPG, GIF, SVG (Max: 2MB)</div>
                                </div>

                                <input type="file" class="d-none @error('logo') is-invalid @enderror"
                                       id="logo" name="logo" accept="image/*">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if(isset($settings) && $settings->logo_path)
                                    <div class="thumbnail-container">
                                        <img src="{{ Storage::url($settings->logo_path) }}"
                                             alt="Current Logo"
                                             class="thumbnail-circle">
                                        <div class="thumbnail-info">
                                            <div class="thumbnail-label">Logo saat ini</div>
                                            <div class="thumbnail-actions">
                                                <a href="{{ Storage::url($settings->logo_path) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary btn-thumbnail">
                                                    <i class="fas fa-external-link-alt"></i> Lihat
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-thumbnail"
                                                        onclick="removeLogo()">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Favicon -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-star fa-sm fa-fw me-2"></i>
                                    Favicon (Icon Browser)
                                </label>

                                <div class="upload-area" onclick="document.getElementById('favicon').click()">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">Klik untuk upload favicon</div>
                                    <div class="upload-hint">ICO, PNG, JPG, JPEG (Max: 1MB)</div>
                                </div>

                                <input type="file" class="d-none @error('favicon') is-invalid @enderror"
                                       id="favicon" name="favicon" accept="image/*,.ico">
                                @error('favicon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if(isset($settings) && $settings->favicon_path)
                                    <div class="thumbnail-container">
                                        <img src="{{ Storage::url($settings->favicon_path) }}"
                                             alt="Current Favicon"
                                             class="thumbnail-circle">
                                        <div class="thumbnail-info">
                                            <div class="thumbnail-label">Favicon saat ini</div>
                                            <div class="thumbnail-actions">
                                                <a href="{{ Storage::url($settings->favicon_path) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary btn-thumbnail">
                                                    <i class="fas fa-external-link-alt"></i> Lihat
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-thumbnail"
                                                        onclick="removeFavicon()">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save fa-sm fa-fw me-2"></i>
                                Update Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden inputs for removal -->
<input type="hidden" id="remove_logo" name="remove_logo" value="0">
<input type="hidden" id="remove_favicon" name="remove_favicon" value="0">

@endsection

@push('scripts')
<script>
    // Drag and drop functionality
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
                input.files = files;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    // Preview logo sebelum upload
    document.getElementById('logo').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                showLogoPreview(e.target.result, file.name);
            };

            reader.readAsDataURL(file);
        }
    });

    // Preview favicon sebelum upload
    document.getElementById('favicon').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                showFaviconPreview(e.target.result, file.name);
            };

            reader.readAsDataURL(file);
        }
    });

    function showLogoPreview(dataUrl, fileName) {
        // Remove existing preview
        const oldPreview = document.getElementById('logo-preview');
        if (oldPreview) {
            oldPreview.remove();
        }

        // Create preview container
        const preview = document.createElement('div');
        preview.className = 'thumbnail-container';
        preview.id = 'logo-preview';
        preview.innerHTML = `
            <img src="${dataUrl}" alt="Logo Preview" class="thumbnail-preview">
            <div class="thumbnail-info">
                <div class="thumbnail-label">Preview: ${fileName}</div>
                <div class="thumbnail-actions">
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-thumbnail" onclick="removeLogoPreview()">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </div>
        `;

        // Insert preview
        const logoInput = document.getElementById('logo');
        logoInput.parentNode.appendChild(preview);
    }

    function showFaviconPreview(dataUrl, fileName) {
        // Remove existing preview
        const oldPreview = document.getElementById('favicon-preview');
        if (oldPreview) {
            oldPreview.remove();
        }

        // Create preview container
        const preview = document.createElement('div');
        preview.className = 'thumbnail-container';
        preview.id = 'favicon-preview';
        preview.innerHTML = `
            <img src="${dataUrl}" alt="Favicon Preview" class="thumbnail-preview">
            <div class="thumbnail-info">
                <div class="thumbnail-label">Preview: ${fileName}</div>
                <div class="thumbnail-actions">
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-thumbnail" onclick="removeFaviconPreview()">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </div>
        `;

        // Insert preview
        const faviconInput = document.getElementById('favicon');
        faviconInput.parentNode.appendChild(preview);
    }

    function removeLogoPreview() {
        const preview = document.getElementById('logo-preview');
        if (preview) {
            preview.remove();
        }
        document.getElementById('logo').value = '';
    }

    function removeFaviconPreview() {
        const preview = document.getElementById('favicon-preview');
        if (preview) {
            preview.remove();
        }
        document.getElementById('favicon').value = '';
    }

    function removeLogo() {
        if (confirm('Apakah Anda yakin ingin menghapus logo saat ini?')) {
            document.getElementById('remove_logo').value = '1';
            // Hide the logo display
            const logoContainer = document.querySelector('.col-md-6:first-child .thumbnail-container');
            if (logoContainer) {
                logoContainer.style.display = 'none';
            }
        }
    }

    function removeFavicon() {
        if (confirm('Apakah Anda yakin ingin menghapus favicon saat ini?')) {
            document.getElementById('remove_favicon').value = '1';
            // Hide the favicon display
            const faviconContainer = document.querySelector('.col-md-6:last-child .thumbnail-container');
            if (faviconContainer) {
                faviconContainer.style.display = 'none';
            }
        }
    }
</script>
@endpush
