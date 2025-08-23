@extends('layouts.app')

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil Saya</h1>
        <div>
            <a href="{{ route('profile.activities') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-list fa-fw"></i> Lihat Aktivitas
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Profil</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body text-center">
                    <div class="mb-4">
                        <img class="img-profile rounded-circle"
                             src="{{ $user->profile_photo_url }}"
                             alt="Profile Photo"
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #4e73df;">
                    </div>
                    <h5 class="font-weight-bold text-primary">{{ $user->name }}</h5>
                    <p class="text-gray-600 mb-2">{{ $user->email }}</p>
                    <span class="badge badge-primary px-3 py-2">{{ $user->role->name ?? 'User' }}</span>

                    @if($user->phone)
                        <p class="text-gray-600 mt-3 mb-1">
                            <i class="fas fa-phone fa-fw me-2"></i>{{ $user->phone }}
                        </p>
                    @endif

                    @if($user->address)
                        <p class="text-gray-600 mb-3">
                            <i class="fas fa-map-marker-alt fa-fw me-2"></i>{{ $user->address }}
                        </p>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('profile.change-password') }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-key fa-fw"></i> Ganti Password
                        </a>
                        <a href="{{ route('settings') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-cogs fa-fw"></i> Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Informasi Profil</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="profile_photo" class="form-label">Foto Profil</label>
                                    <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                                           id="profile_photo" name="profile_photo" accept="image/*">
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                    @error('profile_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.activities') }}" class="btn btn-secondary">
                                <i class="fas fa-list fa-fw"></i> Lihat Aktivitas
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save fa-fw"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview profile photo before upload
    const profilePhotoInput = document.getElementById('profile_photo');
    const profilePhotoPreview = document.querySelector('.img-profile');

    if (profilePhotoInput && profilePhotoPreview) {
        profilePhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePhotoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
