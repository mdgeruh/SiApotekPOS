@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    @component('components.page-header')
    @endcomponent

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

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt fa-sm fa-fw me-2"></i>
                                Alamat
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="2">{{ old('address', $settings->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Telepon -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone fa-sm fa-fw me-2"></i>
                                    Telepon
                                </label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone"
                                       value="{{ old('phone', $settings->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope fa-sm fa-fw me-2"></i>
                                    Email
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email"
                                       value="{{ old('email', $settings->email ?? '') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Website -->
                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label">
                                    <i class="fas fa-globe fa-sm fa-fw me-2"></i>
                                    Website
                                </label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                       id="website" name="website"
                                       value="{{ old('website', $settings->website ?? '') }}">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NPWP -->
                            <div class="col-md-6 mb-3">
                                <label for="tax_number" class="form-label">
                                    <i class="fas fa-file-invoice-dollar fa-sm fa-fw me-2"></i>
                                    NPWP
                                </label>
                                <input type="text" class="form-control @error('tax_number') is-invalid @enderror"
                                       id="tax_number" name="tax_number"
                                       value="{{ old('tax_number', $settings->tax_number ?? '') }}">
                                @error('tax_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Nomor Izin -->
                            <div class="col-md-6 mb-3">
                                <label for="license_number" class="form-label">
                                    <i class="fas fa-certificate fa-sm fa-fw me-2"></i>
                                    Nomor Izin
                                </label>
                                <input type="text" class="form-control @error('license_number') is-invalid @enderror"
                                       id="license_number" name="license_number"
                                       value="{{ old('license_number', $settings->license_number ?? '') }}">
                                @error('license_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nama Pemilik -->
                            <div class="col-md-6 mb-3">
                                <label for="owner_name" class="form-label">
                                    <i class="fas fa-user-tie fa-sm fa-fw me-2"></i>
                                    Nama Pemilik
                                </label>
                                <input type="text" class="form-control @error('owner_name') is-invalid @enderror"
                                       id="owner_name" name="owner_name"
                                       value="{{ old('owner_name', $settings->owner_name ?? '') }}">
                                @error('owner_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left fa-sm fa-fw me-2"></i>
                                Deskripsi
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $settings->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Logo dan Favicon Section -->
                        <div class="row">
                            <!-- Logo -->
                            <div class="col-md-6 mb-4">
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
                                        <img src="{{ \App\Helpers\ImageResizeHelper::getThumbnailUrl($settings->logo_path, 40, 40) }}"
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
                            <div class="col-md-6 mb-4">
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
                                        <img src="{{ \App\Helpers\ImageResizeHelper::getThumbnailUrl($settings->favicon_path, 40, 40) }}"
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

                        <div class="row">
                            <!-- Mata Uang -->
                            <div class="col-md-6 mb-3">
                                <label for="currency" class="form-label">
                                    <i class="fas fa-money-bill-wave fa-sm fa-fw me-2"></i>
                                    Mata Uang <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('currency') is-invalid @enderror"
                                        id="currency" name="currency" required>
                                    <option value="IDR" {{ (old('currency', $settings->currency ?? 'IDR') == 'IDR') ? 'selected' : '' }}>IDR (Rupiah)</option>
                                    <option value="USD" {{ (old('currency', $settings->currency ?? 'IDR') == 'USD') ? 'selected' : '' }}>USD (Dollar)</option>
                                    <option value="EUR" {{ (old('currency', $settings->currency ?? 'IDR') == 'EUR') ? 'selected' : '' }}>EUR (Euro)</option>
                                </select>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Timezone -->
                            <div class="col-md-6 mb-3">
                                <label for="timezone" class="form-label">
                                    <i class="fas fa-clock fa-sm fa-fw me-2"></i>
                                    Timezone <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('timezone') is-invalid @enderror"
                                        id="timezone" name="timezone" required>
                                    <option value="Asia/Jakarta" {{ (old('timezone', $settings->timezone ?? 'Asia/Jakarta') == 'Asia/Jakarta') ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                    <option value="Asia/Makassar" {{ (old('timezone', $settings->timezone ?? 'Asia/Jakarta') == 'Asia/Makassar') ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                    <option value="Asia/Jayapura" {{ (old('timezone', $settings->timezone ?? 'Asia/Jakarta') == 'Asia/Jayapura') ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                                    <option value="UTC" {{ (old('timezone', $settings->timezone ?? 'Asia/Jakarta') == 'UTC') ? 'selected' : '' }}>UTC</option>
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Maintenance Mode -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1"
                                       {{ (old('maintenance_mode', $settings->maintenance_mode ?? false)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="maintenance_mode">
                                    <i class="fas fa-tools fa-sm fa-fw me-2"></i>
                                    Mode Maintenance
                                </label>
                                <div class="form-text">
                                    Aktifkan mode maintenance untuk sementara menonaktifkan akses pengguna
                                </div>
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
