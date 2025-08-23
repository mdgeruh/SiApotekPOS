@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="sales-detail-container">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="sales-detail-header mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-receipt text-primary me-3" style="font-size: 2rem;"></i>
                    <div>
                        <h1 class="h2 mb-0 text-gray-800">Detail Penjualan</h1>
                        <p class="text-muted mb-0">Invoice: {{ $sale->invoice_number }}</p>
                    </div>
                </div>
                <div class="sales-detail-actions">
                    <a href="{{ route('sales.print-receipt', $sale->id) }}"
                       class="btn btn-outline-secondary me-2"
                       target="_blank"
                       title="Cetak PDF">
                        <i class="fas fa-file-pdf me-1"></i>
                        <span class="d-none d-md-inline">PDF</span>
                    </a>
                    <a href="{{ route('sales.print-view', $sale->id) }}"
                       class="btn btn-outline-dark me-2"
                       target="_blank"
                       title="Cetak View">
                        <i class="fas fa-print me-1"></i>
                        <span class="d-none d-md-inline">Print</span>
                    </a>
                    <a href="{{ route('sales.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-1"></i>
                        <span class="d-none d-md-inline">Kembali</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Informasi Transaksi -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informasi Transaksi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-hashtag text-primary me-2"></i>
                                <span class="fw-bold">No. Invoice:</span>
                            </div>
                            <div class="ms-4">
                                <span class="badge bg-light text-dark fs-6">{{ $sale->invoice_number }}</span>
                            </div>
                        </div>

                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                <span class="fw-bold">Tanggal:</span>
                            </div>
                            <div class="ms-4">
                                <span class="text-gray-700">{{ $sale->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-user text-primary me-2"></i>
                                <span class="fw-bold">Kasir:</span>
                            </div>
                            <div class="ms-4">
                                <span class="text-gray-700">{{ $sale->user->name }}</span>
                            </div>
                        </div>

                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-credit-card text-primary me-2"></i>
                                <span class="fw-bold">Metode Pembayaran:</span>
                            </div>
                            <div class="ms-4">
                                @switch($sale->payment_method)
                                    @case('cash')
                                        <span class="badge bg-success">Tunai</span>
                                        @break
                                    @case('debit')
                                        <span class="badge bg-info">Debit</span>
                                        @break
                                    @case('credit')
                                        <span class="badge bg-warning">Kartu Kredit</span>
                                        @break
                                    @case('transfer')
                                        <span class="badge bg-primary">Transfer</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $sale->payment_method }}</span>
                                @endswitch
                            </div>
                        </div>

                        @if($sale->notes)
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-sticky-note text-primary me-2"></i>
                                <span class="fw-bold">Catatan:</span>
                            </div>
                            <div class="ms-4">
                                <span class="text-gray-700">{{ $sale->notes }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pembayaran -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>Ringkasan Pembayaran
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="payment-summary">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-gray-600">Total Belanja:</span>
                                <span class="fs-5 fw-bold text-primary">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-gray-600">Jumlah Bayar:</span>
                                <span class="fs-5 fw-bold text-success">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-gray-600">Kembalian:</span>
                                <span class="fs-5 fw-bold text-info">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-gray-600">Status:</span>
                                @if($sale->paid_amount >= $sale->total_amount)
                                    <span class="badge bg-success fs-6">Lunas</span>
                                @else
                                    <span class="badge bg-warning fs-6">Belum Lunas</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Cepat -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Statistik Cepat
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="quick-stats">
                            <div class="stat-item mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-pills text-info me-2"></i>
                                    <span class="fw-bold text-gray-600">Total Item:</span>
                                </div>
                                <div class="ms-4">
                                    <span class="badge bg-info fs-6">{{ $sale->saleDetails->count() }}</span>
                                </div>
                            </div>

                            <div class="stat-item mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-boxes text-info me-2"></i>
                                    <span class="fw-bold text-gray-600">Total Quantity:</span>
                                </div>
                                <div class="ms-4">
                                    <span class="badge bg-info fs-6">{{ $sale->saleDetails->sum('quantity') }}</span>
                                </div>
                            </div>

                            <div class="stat-item mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-info me-2"></i>
                                    <span class="fw-bold text-gray-600">Waktu Transaksi:</span>
                                </div>
                                <div class="ms-4">
                                    <span class="text-gray-700">{{ $sale->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Item -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-list me-2"></i>Detail Item
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60" class="text-center">#</th>
                                        <th>Obat</th>
                                        <th width="150" class="text-center">Harga Satuan</th>
                                        <th width="100" class="text-center">Jumlah</th>
                                        <th width="150" class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->saleDetails as $index => $detail)
                                        <tr>
                                            <td class="text-center fw-bold text-gray-600">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="medicine-icon me-3">
                                                        <i class="fas fa-pills text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="text-gray-900 d-block">{{ $detail->medicine->name }}</strong>
                                                        <small class="text-muted">
                                                            <i class="fas fa-barcode me-1"></i>
                                                            {{ $detail->medicine->code }} |
                                                            <i class="fas fa-tags me-1"></i>
                                                            {{ $detail->medicine->category ? $detail->medicine->category->name : '-' }} |
                                                            <i class="fas fa-ruler me-1"></i>
                                                            {{ $detail->medicine->unit ? $detail->medicine->unit->name : '-' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-gray-700">Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary fs-6">{{ $detail->quantity }}</span>
                                            </td>
                                            <td class="text-end">
                                                <strong class="text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold fs-5">Total:</td>
                                        <td class="text-end">
                                            <strong class="text-primary fs-5">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informasi:</strong> Transaksi ini telah selesai dan tidak dapat diubah atau dihapus untuk menjaga integritas data.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.sales-detail-container {
    background-color: #f8f9fc;
    min-height: 100vh;
    padding: 2rem 0;
}

.sales-detail-header {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.sales-detail-actions .btn {
    border-radius: 0.375rem;
    font-weight: 500;
}

.card {
    border: none;
    border-radius: 0.5rem;
}

.card-header {
    border-bottom: none;
    border-radius: 0.5rem 0.5rem 0 0 !important;
    padding: 1rem 1.25rem;
}

.info-item, .stat-item {
    padding: 0.5rem 0;
}

.medicine-icon {
    width: 40px;
    height: 40px;
    background-color: #e3f2fd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1976d2;
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

.table tfoot td {
    padding: 1rem 0.75rem;
    border-top: 2px solid #e3e6f0;
    background-color: #f8f9fc;
}

.badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.5rem 0.75rem;
}

@media (max-width: 768px) {
    .sales-detail-header {
        padding: 1rem;
    }

    .sales-detail-actions {
        flex-direction: column;
        gap: 0.5rem;
    }

    .sales-detail-actions .btn {
        width: 100%;
    }

    .table-responsive {
        font-size: 0.875rem;
    }

    .medicine-icon {
        width: 32px;
        height: 32px;
    }
}
</style>
@endpush
