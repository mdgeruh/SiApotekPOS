@extends('layouts.app')

@section('title', 'Log Penjualan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    @include('components.page-header', [
        'title' => 'Log Penjualan',
        'subtitle' => 'Daftar penjualan yang sudah diarsipkan',
        'breadcrumb' => [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Penjualan', 'url' => route('sales.index')],
            ['title' => 'Log Penjualan', 'url' => '#']
        ]
    ])

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penjualan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalSales) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                                Penjualan Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalActive) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Penjualan Diarsipkan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalArchived) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-archive fa-2x text-gray-300"></i>
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
                                Persentase Diarsipkan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalSales > 0 ? number_format(($totalArchived / $totalSales) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Archived Sales Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Penjualan Diarsipkan</h6>
            <a href="{{ route('sales.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Penjualan
            </a>
        </div>
        <div class="card-body">
            @if($archivedSales->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="archivedSalesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Metode Pembayaran</th>
                                <th>Alasan Diarsipkan</th>
                                <th>Tanggal Diarsipkan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedSales as $sale)
                            <tr>
                                <td>
                                    <strong>{{ $sale->invoice_number }}</strong>
                                </td>
                                <td>{{ $sale->user->name }}</td>
                                <td>Rp {{ number_format($sale->total_amount) }}</td>
                                <td>
                                    @switch($sale->payment_method)
                                        @case('cash')
                                            <span class="badge badge-success">Tunai</span>
                                            @break
                                        @case('debit')
                                            <span class="badge badge-info">Debit</span>
                                            @break
                                        @case('credit')
                                            <span class="badge badge-warning">Kartu Kredit</span>
                                            @break
                                        @case('transfer')
                                            <span class="badge badge-primary">Transfer</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">{{ $sale->payment_method }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <small class="text-muted">{{ $sale->archive_reason ?? 'Tidak ada alasan' }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        @if($sale->archived_at)
                                            {{ \Carbon\Carbon::parse($sale->archived_at)->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('sales.restore', $sale) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Kembalikan dari Arsip"
                                                    onclick="return confirm('Apakah Anda yakin ingin mengembalikan penjualan ini dari arsip?')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus Permanen"
                                                onclick="showDeleteConfirmation('{{ route('sales.destroy', $sale) }}', 'Penjualan {{ $sale->invoice_number }}')">
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
                <div class="text-center py-4">
                    <i class="fas fa-archive fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">Belum ada penjualan yang diarsipkan</h5>
                    <p class="text-gray-400">Semua penjualan masih aktif</p>
                </div>
            @endif
        </div>
    </div>
</div>

@include('components.confirmation-modal')
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#archivedSalesTable').DataTable({
            order: [[5, 'desc']], // Sort by archived date descending
            pageLength: 25,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
            }
        });
    });
</script>
@endpush
