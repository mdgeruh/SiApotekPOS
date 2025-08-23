@extends('layouts.app')

@section('content')
<div class="sales-detail-container">
    <div class="container-fluid">
        <!-- Page Header -->
        @component('components.page-header')
            @slot('actions')
                <div class="btn-group" role="group">
                    <a href="{{ route('sales.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span class="d-none d-md-inline"> Transaksi Baru</span>
                    </a>
                    <a href="{{ route('sales.log') }}" class="btn btn-warning">
                        <i class="fas fa-archive"></i>
                        <span class="d-none d-md-inline"> Log Penjualan</span>
                    </a>
                </div>
            @endslot
        @endcomponent

        <!-- Content Row -->
        <div class="row">
            <div class="col-12">
                <!-- Filter Section -->
                <div class="card mb-4 filter-section {{ request()->hasAny(['invoice_number', 'cashier', 'date_from', 'date_to', 'payment_method', 'payment_status']) ? 'filter-active' : '' }}">
                    <div class="card-header">
                        <h6><i class="fas fa-filter mr-2"></i>Filter Pencarian</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('sales.index') }}" id="filterForm">
                            <!-- Row 1: Invoice & Cashier -->
                            <div class="row g-3 mb-3">
                                <div class="col-lg-4 col-md-6">
                                    <label for="invoice_number" class="form-label">
                                        <i class="fas fa-receipt mr-1"></i>No. Invoice
                                    </label>
                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                           value="{{ request('invoice_number') }}" placeholder="Cari invoice..."
                                           data-toggle="tooltip" title="Masukkan nomor invoice yang ingin dicari">
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <label for="cashier" class="form-label">
                                        <i class="fas fa-user mr-1"></i>Nama Kasir
                                    </label>
                                    <input type="text" class="form-control" id="cashier" name="cashier"
                                           value="{{ request('cashier') }}" placeholder="Cari kasir..."
                                           data-toggle="tooltip" title="Masukkan nama kasir yang ingin dicari">
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <label for="payment_method" class="form-label">
                                        <i class="fas fa-credit-card mr-1"></i>Metode Pembayaran
                                    </label>
                                    <select class="form-control" id="payment_method" name="payment_method"
                                            data-toggle="tooltip" title="Pilih metode pembayaran">
                                        <option value="all">Semua Metode</option>
                                        @foreach($paymentMethods as $key => $value)
                                            <option value="{{ $key }}" {{ request('payment_method') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Row 2: Date Range & Payment Status -->
                            <div class="row g-3 mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <label for="date_from" class="form-label">
                                        <i class="fas fa-calendar-alt mr-1"></i>Tanggal Dari
                                    </label>
                                    <input type="date" class="form-control" id="date_from" name="date_from"
                                           value="{{ request('date_from') }}"
                                           data-toggle="tooltip" title="Pilih tanggal awal pencarian">
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label for="date_to" class="form-label">
                                        <i class="fas fa-calendar-alt mr-1"></i>Tanggal Sampai
                                    </label>
                                    <input type="date" class="form-control" id="date_to" name="date_to"
                                           value="{{ request('date_to') }}"
                                           data-toggle="tooltip" title="Pilih tanggal akhir pencarian">
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label for="payment_status" class="form-label">
                                        <i class="fas fa-check-circle mr-1"></i>Status Pembayaran
                                    </label>
                                    <select class="form-control" id="payment_status" name="payment_status"
                                            data-toggle="tooltip" title="Pilih status pembayaran">
                                        <option value="all">Semua Status</option>
                                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 d-flex align-items-end">
                                    <div class="w-100">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary" id="searchBtn">
                                                <i class="fas fa-search mr-2"></i>Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 3: Action Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted">
                                            <small>
                                                @if(request()->hasAny(['invoice_number', 'cashier', 'date_from', 'date_to', 'payment_method', 'payment_status']))
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    Filter aktif:
                                                    @if(request('invoice_number'))
                                                        <span class="badge badge-info mr-1">Invoice: {{ request('invoice_number') }}</span>
                                                    @endif
                                                    @if(request('cashier'))
                                                        <span class="badge badge-info mr-1">Kasir: {{ request('cashier') }}</span>
                                                    @endif
                                                    @if(request('date_from') || request('date_to'))
                                                        <span class="badge badge-info mr-1">
                                                            Tanggal: {{ request('date_from') ?: 'Semua' }} - {{ request('date_to') ?: 'Semua' }}
                                                        </span>
                                                    @endif
                                                    @if(request('payment_method') && request('payment_method') !== 'all')
                                                        <span class="badge badge-info mr-1">{{ $paymentMethods[request('payment_method')] }}</span>
                                                    @endif
                                                    @if(request('payment_status') && request('payment_status') !== 'all')
                                                        <span class="badge badge-info mr-1">
                                                            {{ request('payment_status') === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <!-- Quick Filter Buttons -->
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary quick-filter" data-filter="today">
                                                    <i class="fas fa-calendar-day mr-1"></i>Hari Ini
                                                </button>
                                                <button type="button" class="btn btn-outline-primary quick-filter" data-filter="week">
                                                    <i class="fas fa-calendar-week mr-1"></i>Minggu Ini
                                                </button>
                                                <button type="button" class="btn btn-outline-primary quick-filter" data-filter="month">
                                                    <i class="fas fa-calendar-alt mr-1"></i>Bulan Ini
                                                </button>
                                            </div>
                                            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-times mr-2"></i>Reset Filter
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="sales-detail-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6><i class="fas fa-history mr-2"></i>Riwayat Transaksi</h6>
                            <div class="text-muted">
                                <small>
                                    @if(request()->hasAny(['invoice_number', 'cashier', 'date_from', 'date_to', 'payment_method', 'payment_status']))
                                        <i class="fas fa-filter mr-1"></i>
                                        Filter aktif - {{ $sales->count() }} transaksi ditemukan
                                    @else
                                        Total {{ $sales->count() }} transaksi
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="items-table table" id="salesTable">
                                <thead>
                                    <tr>
                                        <th>No. Invoice</th>
                                        <th>Tanggal</th>
                                        <th>Kasir</th>
                                        <th>Total</th>
                                        <th>Bayar</th>
                                        <th>Kembalian</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sales as $sale)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-receipt text-primary mr-2"></i>
                                                    <strong>{{ $sale->invoice_number }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar text-muted mr-2"></i>
                                                    {{ $sale->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user text-muted mr-2"></i>
                                                    {{ $sale->user->name }}
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <span class="total-amount">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="text-right">
                                                Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                <span class="change-amount">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                @switch($sale->payment_method)
                                                    @case('cash')
                                                        <span class="status-badge success">Tunai</span>
                                                        @break
                                                    @case('debit')
                                                        <span class="status-badge info">Debit</span>
                                                        @break
                                                    @case('credit')
                                                        <span class="status-badge warning">Kartu Kredit</span>
                                                        @break
                                                    @case('transfer')
                                                        <span class="status-badge primary">Transfer</span>
                                                        @break
                                                    @default
                                                        <span class="status-badge warning">{{ $sale->payment_method }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($sale->paid_amount >= $sale->total_amount)
                                                    <span class="status-badge success">Lunas</span>
                                                @else
                                                    <span class="status-badge warning">Belum Lunas</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="sales-action-buttons">
                                                    <a href="{{ route('sales.show', $sale->id) }}"
                                                       class="btn btn-sm btn-info"
                                                       title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('sales.edit', $sale->id) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('sales.print-receipt', $sale->id) }}"
                                                       class="btn btn-sm btn-secondary"
                                                       title="Cetak PDF"
                                                       target="_blank">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                    <a href="{{ route('sales.print-view', $sale->id) }}"
                                                       class="btn btn-sm btn-dark"
                                                       title="Cetak View"
                                                       target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            title="Arsipkan"
                                                            onclick="showDeleteConfirmation('{{ route('sales.destroy', $sale->id) }}', 'Penjualan {{ $sale->invoice_number }}')">
                                                        <i class="fas fa-archive"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                                    <h5 class="text-muted">Belum ada transaksi penjualan</h5>
                                                    <p class="text-muted">Mulai dengan membuat transaksi baru</p>
                                                    <a href="{{ route('sales.create') }}" class="btn btn-primary mt-2">
                                                        <i class="fas fa-plus mr-2"></i>Buat Transaksi
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('components.confirmation-modal')

@push('styles')
<link href="{{ asset('css/components/sales-detail.css') }}" rel="stylesheet">
<style>
/* Filter Section Styles */
.filter-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.08);
    border: none;
    overflow: hidden;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}

.filter-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.75rem 2.5rem rgba(0, 0, 0, 0.1);
}

.filter-section .card-header {
    background: linear-gradient(135deg, #36b9cc 0%, #2a96a5 100%);
    color: white;
    border: none;
    padding: 1.25rem 1.5rem;
}

.filter-section .card-header h6 {
    margin: 0;
    font-weight: 600;
    font-size: 1rem;
}

.filter-section .card-body {
    padding: 1.5rem;
}

.filter-section .form-label {
    font-weight: 600;
    color: #5a5c69;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.filter-section .form-label i {
    margin-right: 0.5rem;
    color: #36b9cc;
    width: 16px;
}

.filter-section .form-control {
    border-radius: 10px;
    border: 2px solid #e3e6f0;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: #f8f9fc;
}

.filter-section .form-control:focus {
    border-color: #36b9cc;
    box-shadow: 0 0 0 0.2rem rgba(54, 185, 204, 0.15);
    background: white;
    transform: translateY(-1px);
}

.filter-section .form-control::placeholder {
    color: #b7b9cc;
    font-style: italic;
}

.filter-section select.form-control {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

.filter-section .btn {
    border-radius: 10px;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
}

.filter-section .btn-primary {
    background: linear-gradient(135deg, #36b9cc 0%, #2a96a5 100%);
    box-shadow: 0 4px 15px rgba(54, 185, 204, 0.3);
}

.filter-section .btn-primary:hover {
    background: linear-gradient(135deg, #2a96a5 0%, #1f7a87 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(54, 185, 204, 0.4);
}

.filter-section .btn-outline-secondary {
    border: 2px solid #858796;
    color: #858796;
    background: transparent;
}

.filter-section .btn-outline-secondary:hover {
    background: #858796;
    color: white;
    transform: translateY(-2px);
}

/* Quick Filter Buttons */
.quick-filter {
    border-radius: 20px;
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
    border: 2px solid #36b9cc;
    color: #36b9cc;
    background: transparent;
    transition: all 0.3s ease;
    font-weight: 500;
}

.quick-filter:hover {
    background: #36b9cc;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(54, 185, 204, 0.3);
}

.quick-filter.active {
    background: #36b9cc;
    color: white;
    box-shadow: 0 4px 12px rgba(54, 185, 204, 0.4);
}

/* Filter Status Badges */
.filter-section .badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-weight: 500;
    margin-right: 0.5rem;
    margin-bottom: 0.25rem;
    display: inline-block;
}

.filter-section .badge-info {
    background: linear-gradient(135deg, #36b9cc 0%, #2a96a5 100%);
    color: white;
}

.filter-active {
    background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
    border-left: 4px solid #e53e3e;
}

.filter-active .card-header {
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .filter-section .card-body {
        padding: 1rem;
    }

    .filter-section .form-label {
        font-size: 0.85rem;
    }

    .filter-section .form-control {
        font-size: 0.85rem;
        padding: 0.6rem 0.8rem;
    }

    .filter-section .btn {
        padding: 0.6rem 1.2rem;
        font-size: 0.8rem;
    }

    .quick-filter {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
    }

    .btn-group-sm .btn {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
    }
}

/* Additional improvements */
.filter-section .row {
    margin-left: -0.75rem;
    margin-right: -0.75rem;
}

.filter-section .col-lg-4,
.filter-section .col-lg-3,
.filter-section .col-md-6 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

.filter-section .form-control:focus {
    border-color: #36b9cc;
    box-shadow: 0 0 0 0.2rem rgba(54, 185, 204, 0.15);
    background: white;
    transform: translateY(-1px);
}

.filter-section .form-control:hover {
    border-color: #36b9cc;
    background: white;
}

/* Animation for filter section */
.filter-section {
    animation: slideInDown 0.5s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Simple table enhancement without DataTable for better performance
    $('#salesTable').on('click', 'tbody tr', function(e) {
        // Don't trigger if clicking on action buttons
        if ($(e.target).closest('.sales-action-buttons').length > 0) {
            return;
        }

        // Find the detail link and navigate to it
        const detailLink = $(this).find('a[href*="/sales/"][title="Lihat Detail"]');
        if (detailLink.length > 0) {
            window.location.href = detailLink.attr('href');
        }
    });

    // Add hover effect for clickable rows
    $('#salesTable tbody tr').css('cursor', 'pointer');

    // Filter functionality enhancements
    const filterForm = $('#filterForm');
    const dateFrom = $('#date_from');
    const dateTo = $('#date_to');

    // Auto-submit form when date changes (with delay)
    let dateTimeout;
    [dateFrom, dateTo].forEach(function(dateInput) {
        dateInput.on('change', function() {
            clearTimeout(dateTimeout);
            dateTimeout = setTimeout(function() {
                if (dateFrom.val() && dateTo.val()) {
                    if (dateFrom.val() > dateTo.val()) {
                        alert('Tanggal "Dari" tidak boleh lebih besar dari tanggal "Sampai"');
                        return;
                    }
                }
                filterForm.submit();
            }, 500);
        });
    });

    // Auto-submit form when select changes
    $('#payment_method, #payment_status').on('change', function() {
        filterForm.submit();
    });

    // Clear filter button functionality
    $('.btn-outline-secondary').on('click', function(e) {
        e.preventDefault();

        // Clear all form inputs
        filterForm.find('input[type="text"], input[type="date"]').val('');
        filterForm.find('select').val('all');

        // Submit form to refresh page
        filterForm.submit();
    });

    // Add loading state to search button
    filterForm.on('submit', function() {
        const searchBtn = $(this).find('button[type="submit"]');
        const originalText = searchBtn.html();

        searchBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...');
        searchBtn.prop('disabled', true);

        // Re-enable button after 3 seconds (fallback)
        setTimeout(function() {
            searchBtn.html(originalText);
            searchBtn.prop('disabled', false);
        }, 3000);
    });

    // Highlight active filters
    function highlightActiveFilters() {
        const hasActiveFilters = $('input[value!=""], select option:selected[value!="all"]').length > 0;
        if (hasActiveFilters) {
            $('.filter-section').addClass('filter-active');
        } else {
            $('.filter-section').removeClass('filter-active');
        }
    }

    // Initialize filter highlighting
    highlightActiveFilters();

    // Add tooltips to form controls
    $('[data-toggle="tooltip"]').tooltip();

    // Quick filter functionality
    $('.quick-filter').on('click', function() {
        const filterType = $(this).data('filter');
        const today = new Date();
        let dateFrom = new Date();
        let dateTo = new Date();

        switch(filterType) {
            case 'today':
                // Keep today's date
                break;
            case 'week':
                // Start of current week (Monday)
                const day = today.getDay();
                const diff = today.getDate() - day + (day === 0 ? -6 : 1);
                dateFrom.setDate(diff);
                break;
            case 'month':
                // Start of current month
                dateFrom.setDate(1);
                break;
        }

        // Format dates for input fields
        const formatDate = (date) => {
            return date.toISOString().split('T')[0];
        };

        // Set date values
        $('#date_from').val(formatDate(dateFrom));
        $('#date_to').val(formatDate(today));

        // Submit form
        filterForm.submit();
    });

    // Add keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + F to focus on first filter input
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            $('#invoice_number').focus();
        }

        // Enter key in text inputs to submit form
        if (e.key === 'Enter' && $(e.target).is('input[type="text"]')) {
            filterForm.submit();
        }
    });

    // Add input validation
    $('#date_from, #date_to').on('change', function() {
        const fromDate = new Date($('#date_from').val());
        const toDate = new Date($('#date_to').val());

        if ($('#date_from').val() && $('#date_to').val() && fromDate > toDate) {
            alert('Tanggal "Dari" tidak boleh lebih besar dari tanggal "Sampai"');
            $(this).val('');
            return false;
        }
    });
});
</script>
@endpush
