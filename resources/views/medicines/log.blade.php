@extends('layouts.app')

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Header -->
    @component('components.page-header')
        @slot('actions')
            <div class="d-flex gap-2">
                <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Kembali ke Daftar Obat
                </a>
                <a href="{{ route('medicines.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Tambah Obat
                </a>
            </div>
        @endslot
    @endcomponent

    <!-- Breadcrumb -->
    @include('components.breadcrumb')

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Obat Diarsipkan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $archivedMedicines->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-archive fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Obat Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeMedicinesCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pills fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Kategori
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categoriesCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Obat Kadaluarsa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $expiredMedicinesCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Archived Medicines Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-archive me-2"></i>
                Log Obat (Obat Diarsipkan)
            </h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                     aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Aksi:</div>
                    <a class="dropdown-item" href="{{ route('medicines.index') }}">
                        <i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>
                        Lihat Obat Aktif
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" onclick="exportArchivedMedicines()">
                        <i class="fas fa-download fa-sm fa-fw me-2 text-gray-400"></i>
                        Export Data
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($archivedMedicines->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="archivedMedicinesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Kode Obat</th>
                                <th>Nama Obat</th>
                                <th>Kategori</th>
                                <th>Stok Terakhir</th>
                                <th>Harga Terakhir</th>
                                <th>Tanggal Diarsipkan</th>
                                <th>Alasan Pengarsipan</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedMedicines as $medicine)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-secondary fs-6">{{ $medicine->code }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong class="text-gray-900">{{ $medicine->name }}</strong>
                                        @if($medicine->description)
                                            <br><small class="text-muted">{{ Str::limit($medicine->description, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($medicine->category)
                                        <span class="badge bg-info">{{ $medicine->category->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $medicine->stock }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="text-primary fw-bold">Rp {{ number_format($medicine->price, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        @if($medicine->archived_at)
                                            {{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->archived_at, true) }}
                                        @else
                                            {{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->updated_at, true) }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-warning">Diarsipkan</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-success"
                                                onclick="confirmRestore({{ $medicine->id }}, '{{ $medicine->name }}')"
                                                title="Kembalikan Obat">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info"
                                                onclick="viewArchivedMedicine({{ $medicine->id }})"
                                                title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmPermanentDelete({{ $medicine->id }}, '{{ $medicine->name }}')"
                                                title="Hapus Permanen">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-archive fa-4x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-500">Belum ada obat yang diarsipkan</h5>
                        <p class="text-gray-400 mb-4">Semua obat saat ini aktif dan tersedia untuk penjualan</p>
                        <a href="{{ route('medicines.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-1"></i>
                            Lihat Obat Aktif
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Confirm restore medicine
function confirmRestore(medicineId, medicineName) {
    const restoreUrl = `/medicines/${medicineId}/restore`;
    const customMessage = `Anda yakin ingin mengembalikan obat "${medicineName}"?\n\nObat akan kembali aktif dan tersedia untuk penjualan.`;

    if (confirm(customMessage)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = restoreUrl;

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);

        // Add method override
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PATCH';
        form.appendChild(methodField);

        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}

// Confirm permanent delete
function confirmPermanentDelete(medicineId, medicineName) {
    const deleteUrl = `/medicines/${medicineId}`;
    const customMessage = `PERINGATAN: Anda yakin ingin menghapus obat "${medicineName}" secara permanen?\n\nTindakan ini tidak dapat dibatalkan dan akan menghapus semua data obat termasuk riwayat penjualan.`;

    showDeleteConfirmation(deleteUrl, medicineName);
}

// View archived medicine details
function viewArchivedMedicine(medicineId) {
    window.open(`/medicines/${medicineId}`, '_blank');
}

// Export archived medicines function
function exportArchivedMedicines() {
    if (window.showNotification) {
        window.showNotification('Fitur export akan segera tersedia', 'info');
    } else {
        alert('Fitur export akan segera tersedia');
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Medicines log page loaded');

    // Initialize DataTable if available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#archivedMedicinesTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [[6, 'desc']], // Sort by archived date descending
            pageLength: 25
        });
    }
});
</script>
@endpush
