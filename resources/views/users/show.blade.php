@extends('layouts.app')

@section('title', 'Detail User: ' . $user->name)

@section('content')
    <!-- Alert Messages -->
    @include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail User: {{ $user->name }}</h1>
        <div>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning shadow-sm me-2">
                <i class="fas fa-edit fa-sm text-white-50 me-1"></i>
                Edit User
            </a>
            <a href="{{ route('users.index') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i>
                Kembali ke Daftar User
            </a>
        </div>
    </div>

    <!-- User Information -->
    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Foto Profil</h6>
                </div>
                <div class="card-body text-center">
                    @if($user->profile_photo_path)
                        <div class="profile-photo-container mb-3">
                            <img src="{{ Storage::url($user->profile_photo_path) }}"
                                 alt="Profile Photo" class="profile-photo">
                        </div>
                    @else
                        <div class="profile-photo-container mb-3">
                            <img src="{{ asset('images/default-avatar.svg') }}"
                                 alt="Default Avatar" class="profile-photo">
                        </div>
                    @endif

                    <h5 class="text-gray-800 fw-bold mb-2">{{ $user->name }}</h5>
                    <div class="mb-3">
                        <span class="badge bg-{{ $user->role->name === 'Admin' ? 'danger' : ($user->role->name === 'Kasir' ? 'success' : 'info') }} fs-6">
                            {{ $user->role->name }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }} fs-6">
                            {{ $user->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit User
                        </a>

                        @if($user->status === 'active')
                            <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary"
                                        onclick="return confirm('Apakah Anda yakin ingin menonaktifkan user ini?')">
                                    <i class="fas fa-ban me-2"></i>Nonaktifkan User
                                </button>
                            </form>
                        @else
                            <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Apakah Anda yakin ingin mengaktifkan user ini?')">
                                    <i class="fas fa-check me-2"></i>Aktifkan User
                                </button>
                            </form>
                        @endif

                        <button type="button" class="btn btn-outline-danger d-grid"
                                onclick="showDeleteConfirmation('{{ route('users.destroy', $user->id) }}', '{{ $user->name }}', 'Apakah Anda yakin ingin menghapus user "{{ $user->name }}"?\n\nTindakan ini tidak dapat dibatalkan.')">
                            <i class="fas fa-trash me-2"></i>Hapus User
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Informasi Lengkap User</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Nama Lengkap</label>
                                <p class="text-gray-800 mb-0">{{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Username</label>
                                <p class="text-gray-800 mb-0">
                                    <code class="text-primary">{{ $user->username }}</code>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Email</label>
                                <p class="text-gray-800 mb-0">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Nomor Telepon</label>
                                <p class="text-gray-800 mb-0">
                                    @if($user->phone)
                                        {{ $user->phone }}
                                    @else
                                        <span class="text-muted">Tidak diisi</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Tanggal Lahir</label>
                                <p class="text-gray-800 mb-0">
                                    @if($user->birth_date)
                                        {{ \App\Helpers\DateHelper::formatDDMMYYYY($user->birth_date) }}
                                    @else
                                        <span class="text-muted">Tidak diisi</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Jenis Kelamin</label>
                                <p class="text-gray-800 mb-0">
                                    @if($user->gender)
                                        {{ $user->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                    @else
                                        <span class="text-muted">Tidak diisi</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Role</label>
                                <p class="text-gray-800 mb-0">
                                    <span class="badge bg-{{ $user->role->name === 'Admin' ? 'danger' : ($user->role->name === 'Kasir' ? 'success' : 'info') }}">
                                        {{ $user->role->name }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Status</label>
                                <p class="text-gray-800 mb-0">
                                    <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ $user->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Alamat</label>
                                <p class="text-gray-800 mb-0">
                                    @if($user->address)
                                        {{ $user->address }}
                                    @else
                                        <span class="text-muted">Tidak diisi</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Tanggal Dibuat</label>
                                <p class="text-gray-800 mb-0">{{ \App\Helpers\DateHelper::formatDDMMYYYY($user->created_at, true) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Terakhir Diupdate</label>
                                <p class="text-gray-800 mb-0">{{ \App\Helpers\DateHelper::formatDDMMYYYY($user->updated_at, true) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Total Login</label>
                                <p class="text-gray-800 mb-0">{{ $user->login_count ?? 0 }} kali</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold text-gray-600">Login Terakhir</label>
                                <p class="text-gray-800 mb-0">
                                    @if($user->last_login_at)
                                        {{ \App\Helpers\DateHelper::formatDDMMYYYY($user->last_login_at, true) }}
                                    @else
                                        <span class="text-muted">Belum pernah login</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Aktivitas User</h6>
                </div>
                <div class="card-body">
                    @if($user->sales && $user->sales->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="userActivityTable" width="100%" cellspacing="0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="8%">No</th>
                                        <th width="25%">Invoice</th>
                                        <th class="text-center" width="20%">Total</th>
                                        <th class="text-center" width="15%">Status</th>
                                        <th class="text-center" width="20%">Tanggal</th>
                                        <th class="text-center" width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->sales->take(5) as $index => $sale)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <code class="text-primary">{{ $sale->invoice_number }}</code>
                                            </td>
                                            <td class="text-center fw-bold text-success">
                                                Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">Selesai</span>
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">{{ \App\Helpers\DateHelper::formatDDMMYYYY($sale->created_at, true) }}</small>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <p class="text-muted mb-0">
                                Menampilkan 5 transaksi terakhir dari total
                                <strong>{{ $user->sales->count() }}</strong> transaksi
                            </p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-500">Belum ada aktivitas</h5>
                            <p class="text-gray-400">User ini belum melakukan transaksi apapun</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-photo-container {
    width: 150px;
    height: 150px;
    margin: 0 auto;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.profile-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
</style>

@endsection
