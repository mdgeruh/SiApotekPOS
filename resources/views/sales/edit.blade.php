@extends('layouts.app')

@section('title', 'Edit Penjualan')

@section('content')
<div class="sales-detail-container">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="sales-detail-header">
            <div class="d-flex align-items-center justify-content-between">
                <h1 class="h2 mb-0">
                    <i class="fas fa-edit text-warning mr-3"></i>
                    Edit Penjualan
                </h1>
                <div class="sales-detail-actions">
                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        <span class="d-none d-md-inline"> Kembali ke Detail</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="sales-detail-card mb-4">
                    <div class="card-header">
                        <h6><i class="fas fa-edit mr-2"></i>Edit Informasi Penjualan</h6>
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

                        <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-hashtag text-primary mr-2"></i>No. Invoice</label>
                                        <input type="text" class="form-control" value="{{ $sale->invoice_number }}" readonly>
                                        <small class="text-muted">Nomor invoice tidak dapat diubah</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-calendar text-primary mr-2"></i>Tanggal Transaksi</label>
                                        <input type="text" class="form-control" value="{{ $sale->created_at->format('d/m/Y H:i') }}" readonly>
                                        <small class="text-muted">Tanggal transaksi tidak dapat diubah</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-shopping-cart text-primary mr-2"></i>Total Belanja</label>
                                        <input type="text" class="form-control" value="Rp {{ number_format($sale->total_amount, 0, ',', '.') }}" readonly>
                                        <small class="text-muted">Total belanja tidak dapat diubah</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-money-bill text-primary mr-2"></i>Jumlah Bayar</label>
                                        <input type="text" class="form-control" value="Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}" readonly>
                                        <small class="text-muted">Jumlah bayar tidak dapat diubah</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-exchange-alt text-primary mr-2"></i>Kembalian</label>
                                        <input type="text" class="form-control" value="Rp {{ number_format($sale->change_amount, 0, ',', '.') }}" readonly>
                                        <small class="text-muted">Kembalian tidak dapat diubah</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_method">
                                            <i class="fas fa-credit-card text-primary mr-2"></i>Metode Pembayaran <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control @error('payment_method') is-invalid @enderror"
                                                id="payment_method"
                                                name="payment_method"
                                                required>
                                            <option value="cash" {{ $sale->payment_method == 'cash' ? 'selected' : '' }}>Tunai</option>
                                            <option value="debit" {{ $sale->payment_method == 'debit' ? 'selected' : '' }}>Debit</option>
                                            <option value="credit" {{ $sale->payment_method == 'credit' ? 'selected' : '' }}>Kartu Kredit</option>
                                            <option value="transfer" {{ $sale->payment_method == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">
                                    <i class="fas fa-sticky-note text-primary mr-2"></i>Catatan
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes"
                                          name="notes"
                                          rows="3"
                                          placeholder="Catatan tambahan...">{{ old('notes', $sale->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-right mt-4">
                                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="col-lg-4">
                <div class="sales-detail-card mb-4">
                    <div class="card-header">
                        <h6><i class="fas fa-info-circle mr-2"></i>Informasi Transaksi</h6>
                    </div>
                    <div class="card-body">
                        <table class="sales-info-table">
                            <tr>
                                <td><i class="fas fa-user text-primary mr-2"></i>Kasir:</td>
                                <td>{{ $sale->user->name }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-list text-primary mr-2"></i>Jumlah Item:</td>
                                <td>{{ $sale->saleDetails->count() }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-check-circle text-primary mr-2"></i>Status:</td>
                                <td>
                                    @if($sale->paid_amount >= $sale->total_amount)
                                        <span class="status-badge success">Lunas</span>
                                    @else
                                        <span class="status-badge warning">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="sales-detail-card">
                    <div class="card-header">
                        <h6><i class="fas fa-bolt mr-2"></i>Aksi Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                            <a href="{{ route('sales.print-receipt', $sale->id) }}" class="btn btn-secondary btn-sm" target="_blank">
                                <i class="fas fa-file-pdf"></i> Cetak PDF
                            </a>
                            <a href="{{ route('sales.print-view', $sale->id) }}" class="btn btn-dark btn-sm" target="_blank">
                                <i class="fas fa-print"></i> Cetak View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('css/components/sales-detail.css') }}" rel="stylesheet">
@endpush
