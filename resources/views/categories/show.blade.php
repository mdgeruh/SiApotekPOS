@extends('layouts.app')

@section('content')
@include('components.alerts')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-tag me-2"></i>
        Detail Kategori: {{ $category->name }}
    </h1>
    <div class="d-flex gap-2">
        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning shadow-sm">
            <i class="fas fa-edit me-1"></i>
            Edit Kategori
        </a>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali ke Daftar
        </a>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Category Information -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Kategori
                </h6>
            </div>
            <div class="card-body">
                <div class="category-header text-center mb-4">
                    <div class="category-icon-large mb-3">
                        <i class="fas fa-tag fa-3x text-primary"></i>
                    </div>
                    <h4 class="text-gray-900">{{ $category->name }}</h4>
                    @if($category->description)
                        <p class="text-muted mb-0">{{ $category->description }}</p>
                    @else
                        <p class="text-muted mb-0">Tidak ada deskripsi</p>
                    @endif
                </div>

                <div class="category-stats">
                    <div class="stat-item d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-gray-600">
                            <i class="fas fa-pills me-2"></i>
                            Jumlah Obat
                        </span>
                        <span class="badge bg-info fs-6">{{ $category->medicines->count() }}</span>
                    </div>
                    <div class="stat-item d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-gray-600">
                            <i class="fas fa-calendar-plus me-2"></i>
                            Dibuat
                        </span>
                                                        <span class="text-gray-800">{{ \App\Helpers\DateHelper::formatDDMMYYYY($category->created_at) }}</span>
                    </div>
                    <div class="stat-item d-flex justify-content-between align-items-center py-2">
                        <span class="text-gray-600">
                            <i class="fas fa-calendar-check me-2"></i>
                            Terakhir Update
                        </span>
                                                        <span class="text-gray-800">{{ \App\Helpers\DateHelper::formatDDMMYYYY($category->updated_at) }}</span>
                    </div>
                    <div class="stat-item d-flex justify-content-between align-items-center py-2">
                        <span class="text-gray-600">
                            <i class="fas fa-toggle-on me-2"></i>
                            Status
                        </span>
                        <span>
                            @if(($category->status ?? 'active') == 'active')
                                <span class="status-badge status-active">Aktif</span>
                            @else
                                <span class="status-badge status-inactive">Tidak Aktif</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>
                            Edit Kategori
                        </a>
                        <a href="{{ route('medicines.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>
                            Tambah Obat Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-bolt me-2"></i>
                    Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('medicines.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-list me-2"></i>
                            Lihat Semua Obat
                        </span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('categories.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-tags me-2"></i>
                            Lihat Semua Kategori
                        </span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Medicines List -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-pills me-2"></i>
                    Daftar Obat dalam Kategori
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                         aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Aksi:</div>
                        <a class="dropdown-item" href="{{ route('medicines.create') }}">
                            <i class="fas fa-plus fa-sm fa-fw me-2 text-gray-400"></i>
                            Tambah Obat
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="exportMedicines()">
                            <i class="fas fa-download fa-sm fa-fw me-2 text-gray-400"></i>
                            Export Data
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($category->medicines->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="medicinesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Obat</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->medicines as $medicine)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ $medicine->code }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="medicine-icon me-2">
                                                <i class="fas fa-pills text-primary"></i>
                                            </div>
                                            <div>
                                                <strong class="text-gray-900">{{ $medicine->name }}</strong>
                                                @if($medicine->description)
                                                    <br><small class="text-muted">{{ Str::limit($medicine->description, 30) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-gray-800">Rp {{ number_format($medicine->price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($medicine->stock > 10)
                                            <span class="status-badge status-active">{{ $medicine->stock }}</span>
                                        @elseif($medicine->stock > 0)
                                            <span class="status-badge status-warning">{{ $medicine->stock }}</span>
                                        @else
                                            <span class="status-badge status-inactive">Habis</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($medicine->expired_date->isPast())
                                            <span class="status-badge status-danger">Expired</span>
                                        @elseif($medicine->expired_date->diffInDays(now()) <= 30)
                                            <span class="status-badge status-warning">Akan Expired</span>
                                        @else
                                            <span class="status-badge status-success">Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('medicines.show', $medicine->id) }}"
                                           class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Medicines Summary -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Stok Aman
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $category->medicines->where('stock', '>', 10)->count() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Stok Menipis
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $category->medicines->whereBetween('stock', [1, 10])->count() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Stok Habis
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $category->medicines->where('stock', 0)->count() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Nilai
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($category->medicines->sum(function($m) { return $m->price * $m->stock; }), 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-pills fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-500">Belum ada obat dalam kategori ini</h5>
                            <p class="text-gray-400 mb-4">Mulai dengan menambahkan obat pertama ke kategori {{ $category->name }}</p>
                            <a href="{{ route('medicines.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Tambah Obat Pertama
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Export medicines function
function exportMedicines() {
    // Implementation for exporting medicines data
    if (window.showNotification) {
        window.showNotification('Fitur export akan segera tersedia', 'info');
    } else {
        alert('Fitur export akan segera tersedia');
    }
}

// Initialize DataTable if available
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#medicinesTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [[1, 'asc']], // Sort by name by default
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]]
        });
    }
});
</script>
@endpush
