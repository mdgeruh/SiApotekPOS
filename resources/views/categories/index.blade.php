@extends('layouts.app')

@section('content')
<style>
/* Prevent multiple alerts and ensure proper styling */
.alert {
    position: relative;
    z-index: 1050;
    margin-bottom: 1rem;
}

/* Hide all alerts initially */
.alert {
    display: none !important;
}

/* Show only the first alert of each type */
.alert.alert-success:first-of-type,
.alert.alert-danger:first-of-type {
    display: block !important;
}

/* Loading state for delete buttons */
.btn:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

/* Fade out animation */
.alert.fade-out {
    opacity: 0;
    transition: opacity 0.5s ease-out;
}

/* Ensure consistent close button styling */
.alert .close {
    float: right;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
    background: none;
    border: 0;
    padding: 0;
    margin: 0;
}

.alert .close:hover {
    opacity: .75;
}

/* Table styling improvements */
.table-responsive {
    border-radius: 0.35rem;
    overflow: hidden;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background-color: #f8f9fc;
    border-bottom: 2px solid #e3e6f0;
    color: #5a5c69;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.1em;
    padding: 1rem 0.75rem;
}

.table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #e3e6f0;
}

.table tbody tr:hover {
    background-color: #f8f9fc;
}

/* Status badge styling */
.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-active {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.status-inactive {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Category icon styling */
.category-icon {
    width: 40px;
    height: 40px;
    background-color: #e3f2fd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1976d2;
}

/* Medicine count badge */
.medicine-count-badge {
    background-color: #e3f2fd;
    color: #1976d2;
    border: 1px solid #bbdefb;
    font-weight: 600;
}

/* Action buttons styling */
.btn-group .btn {
    margin-right: 0.25rem;
    border-radius: 0.375rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Empty state styling */
.empty-state {
    padding: 3rem 1rem;
}

.empty-state i {
    color: #d1d3e2;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .category-icon {
        width: 32px;
        height: 32px;
    }
}
</style>

<!-- Page Header -->
@component('components.page-header')
    @slot('actions')
        <a href="{{ route('categories.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i>
            Tambah Kategori
        </a>
    @endslot
@endcomponent

<!-- Breadcrumb -->
@include('components.breadcrumb')

<!-- Content Row -->
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Kategori
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x text-gray-300"></i>
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
                            Kategori Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->where('medicines_count', '>', 0)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            Total Obat
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->sum('medicines_count') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pills fa-2x text-gray-300"></i>
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
                            Kategori Kosong
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->where('medicines_count', 0)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list me-2"></i>
            Daftar Kategori
        </h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                 aria-labelledby="dropdownMenuLink">
                <div class="dropdown-header">Aksi:</div>
                <a class="dropdown-item" href="{{ route('categories.create') }}">
                    <i class="fas fa-plus fa-sm fa-fw me-2 text-gray-400"></i>
                    Tambah Kategori
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" onclick="exportCategories()">
                    <i class="fas fa-download fa-sm fa-fw me-2 text-gray-400"></i>
                    Export Data
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('components.alerts')

        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="categoriesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="60" class="text-center">#</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th width="120" class="text-center">Jumlah Obat</th>
                            <th width="100" class="text-center">Status</th>
                            <th width="140">Dibuat</th>
                            <th width="180" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td class="text-center font-weight-bold text-gray-600">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="category-icon me-3">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div>
                                        <strong class="text-gray-900 d-block">{{ $category->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($category->description)
                                    <span class="text-gray-700">{{ Str::limit($category->description, 60) }}</span>
                                @else
                                    <span class="text-muted fst-italic">Tidak ada deskripsi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $medicineCount = $category->medicines_count ?? $category->medicines->count();
                                @endphp
                                <span class="badge medicine-count-badge">{{ $medicineCount }}</span>
                            </td>
                            <td class="text-center">
                                @if(($category->status ?? 'active') == 'active')
                                    <span class="status-badge status-active">Aktif</span>
                                @else
                                    <span class="status-badge status-inactive">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ \App\Helpers\DateHelper::formatDDMMYYYY($category->created_at, true) }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('categories.show', $category->id) }}"
                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('categories.edit', $category->id) }}"
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')"
                                            title="Hapus">
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
                    <i class="fas fa-tags fa-4x mb-3"></i>
                    <h5 class="text-gray-500">Belum ada kategori obat</h5>
                    <p class="text-gray-400 mb-4">Mulai dengan menambahkan kategori obat pertama untuk mengorganisir inventori Anda</p>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Kategori Pertama
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
// Delete confirmation function using modal
function confirmDelete(categoryId, categoryName) {
    const deleteUrl = `/categories/${categoryId}`;
    const customMessage = `Anda yakin ingin menghapus kategori "${categoryName}"?\n\nPerhatian: Jika kategori ini memiliki obat, obat-obat tersebut akan menjadi tidak terkategorikan.`;

    showDeleteConfirmation(deleteUrl, categoryName, customMessage);
}

// Export categories function
function exportCategories() {
    window.showInfo('Fitur export akan segera tersedia');
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Categories page loaded');

    // Handle alerts properly to prevent duplicates
    function handleAlerts() {
        // Remove duplicate alerts
        const allAlerts = document.querySelectorAll('.alert');
        const alertTypes = new Set();

        allAlerts.forEach((alert, index) => {
            const alertClass = Array.from(alert.classList).find(cls => cls.includes('alert-'));

            if (alertTypes.has(alertClass)) {
                // This is a duplicate, remove it
                alert.remove();
                console.log('Removed duplicate alert:', alertClass);
            } else {
                // First occurrence of this alert type, keep it
                alertTypes.add(alertClass);
                alert.style.display = 'block';

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    if (alert && alert.parentNode) {
                        alert.style.transition = 'opacity 0.5s ease-out';
                        alert.style.opacity = '0';
                        setTimeout(() => {
                            if (alert && alert.parentNode) {
                                alert.remove();
                            }
                        }, 500);
                    }
                }, 5000);
            }
        });

        console.log('Alert types found:', Array.from(alertTypes));
    }

    // Handle alerts when page loads
    handleAlerts();

    // Also handle alerts after a short delay to catch any late-rendered ones
    setTimeout(handleAlerts, 100);

    // Initialize DataTable if available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#categoriesTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            columnDefs: [
                { orderable: false, targets: [0, 6] }, // Disable sorting for # and Actions columns
                { className: 'text-center', targets: [0, 3, 4, 6] }
            ],
            order: [[1, 'asc']], // Sort by category name by default
            pageLength: 25,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
        });
    }

    // Test if jQuery and Bootstrap are available
    console.log('jQuery available:', typeof $ !== 'undefined');
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    if (typeof $ !== 'undefined') {
        console.log('jQuery version:', $.fn.jquery);
    }

    // Clear session messages from URL after page load
    if (window.history && window.history.replaceState) {
        const url = new URL(window.location);
        if (url.searchParams.has('success') || url.searchParams.has('error')) {
            url.searchParams.delete('success');
            url.searchParams.delete('error');
            window.history.replaceState({}, document.title, url.pathname);
        }
    }
});
</script>
@endpush
