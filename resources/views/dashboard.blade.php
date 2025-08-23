@extends('layouts.app')

@section('content')
@include('components.alerts')

<!-- Page Header -->
@component('components.page-header')
    @slot('actions')
        <a href="{{ route('sales.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 me-1"></i>
            Transaksi Baru
        </a>
    @endslot
@endcomponent

<!-- Pharmacy Info Card -->
@if(\App\Helpers\AppSettingHelper::pharmacyName() || \App\Helpers\AppSettingHelper::address())
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow border-left-info" id="pharmacy-info-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="text-info">
                            <i class="fas fa-clinic-medical fa-3x"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="card-title text-info mb-1" id="pharmacy-name">
                            {{ \App\Helpers\AppSettingHelper::pharmacyName() ?: 'Apotek' }}
                        </h5>
                        @if(\App\Helpers\AppSettingHelper::address())
                            <p class="card-text text-muted mb-1" id="pharmacy-address">
                                <i class="fas fa-map-marker-alt fa-sm fa-fw me-1"></i>
                                {{ \App\Helpers\AppSettingHelper::address() }}
                            </p>
                        @endif
                        <div class="row">
                            @if(\App\Helpers\AppSettingHelper::phone())
                                <div class="col-md-3">
                                    <small class="text-muted" id="pharmacy-phone">
                                        <i class="fas fa-phone fa-sm fa-fw me-1"></i>
                                        {{ \App\Helpers\AppSettingHelper::phone() }}
                                    </small>
                                </div>
                            @endif
                            @if(\App\Helpers\AppSettingHelper::email())
                                <div class="col-md-3">
                                    <small class="text-muted" id="pharmacy-email">
                                        <i class="fas fa-envelope fa-sm fa-fw me-1"></i>
                                        {{ \App\Helpers\AppSettingHelper::email() }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Main Statistics Row -->
<div class="row mb-4">
    <!-- Total Penjualan Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('sales.index') }}" class="text-decoration-none">
            <div class="card bg-primary text-white shadow h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Penjualan</h6>
                            <h4>{{ number_format($stats['total_sales']) }}</h4>
                            <small>Transaksi</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Obat Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('medicines.index') }}" class="text-decoration-none">
            <div class="card bg-success text-white shadow h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Obat</h6>
                            <h4>{{ number_format($stats['total_medicines']) }}</h4>
                            <small>Barang</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-pills fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Users Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('users.index') }}" class="text-decoration-none">
            <div class="card bg-info text-white shadow h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Users</h6>
                            <h4>{{ number_format($stats['total_users']) }}</h4>
                            <small>Pengguna</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Today's Sales Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('sales.index') }}" class="text-decoration-none">
            <div class="card bg-warning text-dark shadow h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Penjualan Hari Ini</h6>
                            <h4>{{ number_format($stats['today_sales']) }}</h4>
                            <small>Transaksi</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Financial & Alert Statistics Row -->
<div class="row mb-4">
    <!-- Today's Revenue Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('reports.sales') }}" class="text-decoration-none">
            <div class="card bg-success text-white shadow h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Pendapatan Hari Ini</h6>
                            <h4>Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</h4>
                            <small>Rupiah</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Low Stock Medicines Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('medicines.index') }}" class="text-decoration-none">
            <div class="card bg-danger text-white shadow h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Stok Menipis</h6>
                            <h4>{{ number_format($stats['low_stock_medicines']) }}</h4>
                            <small>Perlu restock</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Expiring Medicines Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('medicines.index') }}" class="text-decoration-none">
            <div class="card bg-warning text-dark shadow h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Akan Expired</h6>
                            <h4>{{ number_format($stats['expiring_medicines']) }}</h4>
                            <small>Dalam 30 hari</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Expired Medicines Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('medicines.index') }}" class="text-decoration-none">
            <div class="card bg-danger text-white shadow h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Sudah Expired</h6>
                            <h4>{{ number_format($stats['expired_medicines'] ?? 0) }}</h4>
                            <small>Perlu dibuang</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-times fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Data Overview Row -->
<div class="row mb-4">
    <!-- Recent Sales -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line me-1"></i>
                    Penjualan Terbaru
                </h6>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-external-link-alt me-1"></i>
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($lists['recent_sales']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Invoice</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-right">Total</th>
                                    <th>Kasir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lists['recent_sales'] as $sale)
                                <tr>
                                    <td>
                                        <a href="{{ route('sales.show', $sale->id) }}" class="text-decoration-none">
                                            <span class="badge bg-primary">{{ $sale->invoice_number }}</span>
                                        </a>
                                    </td>
                                    <td class="text-center">{{ \App\Helpers\DateHelper::formatDDMMYYYY($sale->created_at, true) }}</td>
                                    <td class="text-right">
                                        <strong>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>{{ $sale->user->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada penjualan</h5>
                        <p class="text-muted">Data penjualan akan muncul setelah ada transaksi</p>
                    </div>
                @endif

                <!-- Top Selling Medicines Section -->
                <hr class="my-4">
                <div class="d-flex flex-row align-items-center justify-content-between mb-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-fire me-1"></i>
                        Obat Terlaris
                    </h6>
                    <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-chart-bar me-1"></i>
                        Lihat Detail
                    </a>
                </div>

                @if($charts['top_selling_medicines']->count() > 0)
                    <div class="top-selling-section">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <thead>
                                    <tr class="border-bottom">
                                        <th class="text-muted small text-center" style="width: 60px;">Rank</th>
                                        <th class="text-muted small" style="width: 80px;">Kode</th>
                                        <th class="text-muted small">Nama Obat</th>
                                        <th class="text-muted small text-center" style="width: 100px;">Total Terjual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($charts['top_selling_medicines']->take(5) as $index => $medicine)
                                    <tr class="border-bottom">
                                        <td class="py-2 text-center">
                                            @if($index == 0)
                                                <div class="rank-badge rank-1">🥇</div>
                                            @elseif($index == 1)
                                                <div class="rank-badge rank-2">🥈</div>
                                            @elseif($index == 2)
                                                <div class="rank-badge rank-3">🥉</div>
                                            @else
                                                <div class="rank-badge rank-other">{{ $index + 1 }}</div>
                                            @endif
                                        </td>
                                        <td class="py-2">
                                            <span class="badge bg-info">{{ $medicine->code }}</span>
                                        </td>
                                        <td class="py-2">
                                            <div class="fw-bold text-dark">{{ Str::limit($medicine->name, 30) }}</div>
                                        </td>
                                        <td class="py-2 text-center">
                                            <span class="badge bg-success fs-6">{{ number_format($medicine->total_sold) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-chart-line fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0 small">Belum ada data penjualan obat</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stock Alerts -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <div class="d-flex flex-row align-items-center justify-content-between mb-2">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Peringatan Stok
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="stockFilterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter me-1"></i>
                            Filter
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="stockFilterDropdown">
                            <a class="dropdown-item" href="#" onclick="filterStockAlerts('all')">
                                <i class="fas fa-list me-2"></i>Semua
                            </a>
                            <a class="dropdown-item" href="#" onclick="filterStockAlerts('low')">
                                <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Stok Menipis
                            </a>
                            <a class="dropdown-item" href="#" onclick="filterStockAlerts('expiring')">
                                <i class="fas fa-clock me-2 text-warning"></i>Akan Expired
                            </a>
                            <a class="dropdown-item" href="#" onclick="filterStockAlerts('expired')">
                                <i class="fas fa-calendar-times me-2 text-danger"></i>Sudah Expired
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <small class="text-muted d-block">Stok Menipis</small>
                        <span class="badge bg-danger">{{ $lists['low_stock_medicines_list']->count() }}</span>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Akan Expired</small>
                        <span class="badge bg-warning">{{ $lists['expiring_medicines_list']->count() }}</span>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Sudah Expired</small>
                        <span class="badge bg-danger">{{ $lists['expired_medicines_list']->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Low Stock Medicines -->
                <div class="stock-alert-section mb-4" data-type="low">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-danger mb-0">
                            <i class="fas fa-exclamation-triangle me-1"></i> Stok Menipis
                        </h6>
                        <span class="badge bg-danger">{{ $lists['low_stock_medicines_list']->count() }}</span>
                    </div>
                    @if($lists['low_stock_medicines_list']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    @foreach($lists['low_stock_medicines_list']->take(3) as $medicine)
                                    @php
                                        $stockLevel = $medicine->stock;
                                        $minStock = $medicine->min_stock ?? 10;
                                        $stockClass = $stockLevel == 0 ? 'bg-danger' :
                                                    ($stockLevel <= 5 ? 'bg-danger' :
                                                    ($stockLevel <= $minStock ? 'bg-warning' : 'bg-info'));
                                        $stockStatus = $stockLevel == 0 ? 'Habis' :
                                                     ($stockLevel <= 5 ? 'Kritis' :
                                                     ($stockLevel <= $minStock ? 'Menipis' : 'Normal'));
                                        $stockPercentage = $minStock > 0 ? round(($stockLevel / $minStock) * 100) : 0;
                                    @endphp
                                    <tr class="border-bottom">
                                        <td class="py-2">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <span class="badge bg-secondary">{{ $medicine->code }}</span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-dark">{{ Str::limit($medicine->name, 25) }}</div>
                                                    <small class="text-muted">{{ $medicine->category->name ?? 'Tanpa Kategori' }}</small>
                                                    <div class="mt-1">
                                                        <small class="text-muted">Min. Stok: {{ $minStock }} {{ $medicine->unit->name ?? 'unit' }}</small>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 text-end">
                                                    <span class="badge {{ $stockClass }} fs-6">{{ $medicine->stock }} @if($medicine->unit) {{ $medicine->unit->name }} @endif</span>
                                                    <div class="mt-1">
                                                        <small class="text-muted">{{ $stockStatus }}</small>
                                                    </div>
                                                    @if($stockLevel > 0 && $minStock > 0)
                                                        <div class="mt-1">
                                                            <small class="text-muted">{{ $stockPercentage }}% dari min. stok</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($lists['low_stock_medicines_list']->count() > 3)
                            <div class="text-center mt-3">
                                <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <div class="text-success mb-3">
                                <i class="fas fa-boxes fa-3x"></i>
                            </div>
                            <h6 class="text-success mb-2">Stok Aman</h6>
                            <p class="text-muted mb-0">Semua obat memiliki stok yang mencukupi</p>
                            <small class="text-muted">Terakhir diperiksa: {{ \App\Helpers\DateHelper::formatDDMMYYYY(now(), true) }}</small>
                        </div>
                    @endif
                </div>

                <!-- Expiring Medicines -->
                <div class="stock-alert-section mb-4" data-type="expiring">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-warning mb-0">
                            <i class="fas fa-clock me-1"></i> Akan Expired (30 Hari)
                        </h6>
                        <span class="badge bg-warning">{{ $lists['expiring_medicines_list']->count() }}</span>
                    </div>
                    @if($lists['expiring_medicines_list']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    @foreach($lists['expiring_medicines_list']->take(3) as $medicine)
                                    @php
                                        $daysUntilExpired = \Carbon\Carbon::parse($medicine->expired_date)->diffInDays(now(), false);
                                        $expiredClass = $daysUntilExpired <= 7 ? 'bg-danger' : 'bg-warning';
                                        $expiredStatus = $daysUntilExpired <= 7 ? 'Kritis' : 'Perhatian';
                                    @endphp
                                    <tr class="border-bottom">
                                        <td class="py-2">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <span class="badge bg-secondary">{{ $medicine->code }}</span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-dark">{{ Str::limit($medicine->name, 25) }}</div>
                                                    <small class="text-muted">{{ $medicine->category->name ?? 'Tanpa Kategori' }}</small>
                                                </div>
                                                <div class="flex-shrink-0 text-end">
                                                    <span class="badge {{ $expiredClass }} fs-6">{{ abs($daysUntilExpired) }} hari</span>
                                                    <div class="mt-1">
                                                        <small class="text-muted">{{ $expiredStatus }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($lists['expiring_medicines_list']->count() > 3)
                            <div class="text-center mt-3">
                                <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <div class="text-success mb-3">
                                <i class="fas fa-shield-check fa-3x"></i>
                            </div>
                            <h6 class="text-success mb-2">Status Stok Aman</h6>
                            <p class="text-muted mb-0">Tidak ada obat yang akan expired dalam 30 hari ke depan</p>
                            <small class="text-muted">Terakhir diperiksa: {{ \App\Helpers\DateHelper::formatDDMMYYYY(now(), true) }}</small>
                        </div>
                    @endif
                </div>

                <!-- Expired Medicines -->
                <div class="stock-alert-section" data-type="expired">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-danger mb-0">
                            <i class="fas fa-calendar-times me-1"></i> Sudah Expired
                        </h6>
                        <span class="badge bg-danger">{{ $lists['expired_medicines_list']->count() }}</span>
                    </div>
                    @if($lists['expired_medicines_list']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    @foreach($lists['expired_medicines_list']->take(3) as $medicine)
                                    @php
                                        $daysExpired = \Carbon\Carbon::parse($medicine->expired_date)->diffInDays(now());
                                        $expiredClass = $daysExpired <= 7 ? 'bg-danger' : 'bg-secondary';
                                        $expiredStatus = $daysExpired <= 7 ? 'Baru Expired' : 'Expired Lama';
                                    @endphp
                                    <tr class="border-bottom">
                                        <td class="py-2">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <span class="badge bg-secondary">{{ $medicine->code }}</span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-dark">{{ Str::limit($medicine->name, 25) }}</div>
                                                    <small class="text-muted">{{ $medicine->category->name ?? 'Tanpa Kategori' }}</small>
                                                </div>
                                                <div class="flex-shrink-0 text-end">
                                                    <span class="badge {{ $expiredClass }} fs-6">{{ $daysExpired }} hari lalu</span>
                                                    <div class="mt-1">
                                                        <small class="text-danger fw-bold">PERLU DIBUANG</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($lists['expired_medicines_list']->count() > 3)
                            <div class="text-center mt-3">
                                <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <div class="text-success mb-3">
                                <i class="fas fa-check-circle fa-3x"></i>
                            </div>
                            <h6 class="text-success mb-2">Tidak Ada Obat Expired</h6>
                            <p class="text-muted mb-0">Semua obat masih dalam masa berlaku</p>
                            <small class="text-muted">Terakhir diperiksa: {{ \App\Helpers\DateHelper::formatDDMMYYYY(now(), true) }}</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button (FAB) -->
<div class="fab-container">
    <div class="fab-button" id="fabButton">
        <i class="fas fa-plus"></i>
    </div>
    <div class="fab-options" id="fabOptions">
        <a href="{{ route('medicines.create') }}" class="fab-option" title="Tambah Obat">
            <i class="fas fa-pills"></i>
        </a>
        <a href="{{ route('sales.create') }}" class="fab-option" title="Transaksi Baru">
            <i class="fas fa-shopping-cart"></i>
        </a>
        <a href="{{ route('categories.index') }}" class="fab-option" title="Kelola Kategori">
            <i class="fas fa-tags"></i>
        </a>
        <a href="{{ route('users.index') }}" class="fab-option" title="Kelola User">
            <i class="fas fa-users"></i>
        </a>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Monthly Sales Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-area me-1"></i>
                    Grafik Penjualan Bulanan
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-credit-card me-1"></i>
                    Metode Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="paymentMethodsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Alert Charts Row -->
<div class="row mb-4">
    <!-- Stock Level Distribution Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie me-1"></i>
                    Distribusi Level Stok
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="stockLevelChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiry Timeline Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar me-1"></i>
                    Timeline Kadaluarsa (30 Hari Kedepan)
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="expiryTimelineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Stock Alert Filter Functionality -->
<script>
function filterStockAlerts(type) {
    const sections = document.querySelectorAll('.stock-alert-section');

    sections.forEach(section => {
        if (type === 'all') {
            section.style.display = 'block';
        } else {
            if (section.dataset.type === type) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }
    });

    // Update filter button text
    const filterBtn = document.getElementById('stockFilterDropdown');
    const filterTexts = {
        'all': 'Semua',
        'low': 'Stok Menipis',
        'expiring': 'Akan Expired',
        'expired': 'Sudah Expired'
    };
    filterBtn.innerHTML = `<i class="fas fa-filter me-1"></i>${filterTexts[type]}`;
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap tooltip initialization if available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>

<!-- FAB Styles -->
<style>
.fab-container {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
}

.fab-button {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
    z-index: 1001;
}

.fab-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(0,0,0,0.4);
}

.fab-options {
    position: absolute;
    bottom: 70px;
    right: 0;
    display: none;
    flex-direction: column;
    gap: 15px;
    transition: all 0.3s ease;
}

.fab-options.show {
    display: flex;
}

.fab-option {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 18px;
    text-decoration: none;
    box-shadow: 0 2px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(20px);
}

.fab-options.show .fab-option {
    opacity: 1;
    transform: translateY(0);
}

.fab-option:nth-child(1) { transition-delay: 0.1s; }
.fab-option:nth-child(2) { transition-delay: 0.2s; }
.fab-option:nth-child(3) { transition-delay: 0.3s; }
.fab-option:nth-child(4) { transition-delay: 0.4s; }

.fab-option:hover {
    transform: scale(1.1);
    background: #667eea;
    color: white;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.fab-option {
    position: relative;
}

.fab-option::before {
    content: attr(title);
    position: absolute;
    right: 60px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    pointer-events: none;
    z-index: 1002;
}

.fab-option::after {
    content: '';
    position: absolute;
    right: 55px;
    top: 50%;
    transform: translateY(-50%);
    border: 5px solid transparent;
    border-left-color: rgba(0,0,0,0.8);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    pointer-events: none;
}

.fab-option:hover::before,
.fab-option:hover::after {
    opacity: 1;
    visibility: visible;
    right: 65px;
}

/* Stock Alert Styles */
.stock-alert-section {
    transition: all 0.3s ease;
}

.stock-alert-section .table-borderless tbody tr {
    transition: all 0.2s ease;
}

.stock-alert-section .table-borderless tbody tr:hover {
    background-color: rgba(0,0,0,0.02);
    transform: translateX(2px);
}

.stock-alert-section .badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.stock-alert-section .fs-6 {
    font-size: 0.875rem !important;
}

.stock-alert-section .dropdown-toggle::after {
    margin-left: 0.5em;
}

.stock-alert-section .btn-outline-danger:hover,
.stock-alert-section .btn-outline-warning:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

/* Top Selling Medicines Styles */
.top-selling-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
}

.top-selling-section .table-sm tbody tr {
    transition: all 0.2s ease;
}

.top-selling-section .table-sm tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.05);
    transform: translateX(3px);
}

.top-selling-section .badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.top-selling-section .rank-badge {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 0.875rem;
    font-weight: bold;
}

.top-selling-section .rank-1 {
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    color: #856404;
}

.top-selling-section .rank-2 {
    background: linear-gradient(135deg, #c0c0c0 0%, #e2e3e5 100%);
    color: #495057;
}

.top-selling-section .rank-3 {
    background: linear-gradient(135deg, #cd7f32 0%, #d4a574 100%);
    color: #8b4513;
}

.top-selling-section .rank-other {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #6c757d;
}

/* Card Hover Effects */
.card-hover {
    transition: all 0.3s ease;
    cursor: pointer;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.card-hover:hover .card-body {
    transform: scale(1.02);
    transition: transform 0.3s ease;
}

/* Responsive adjustments for stock alerts */
@media (max-width: 1200px) {
    .stock-alert-section .d-flex.align-items-center {
        flex-direction: column;
        align-items: flex-start !important;
    }

    .stock-alert-section .flex-shrink-0.text-end {
        margin-top: 0.5rem;
        align-self: flex-end;
    }
}

@media (max-width: 768px) {
    .fab-container {
        bottom: 20px;
        right: 20px;
    }

    .fab-button {
        width: 55px;
        height: 55px;
        font-size: 20px;
    }

    .fab-options {
        bottom: 65px;
        gap: 12px;
    }

    .fab-option {
        width: 45px;
        height: 45px;
        font-size: 16px;
    }

    .fab-option::before {
        right: 50px;
        font-size: 11px;
        padding: 6px 10px;
    }

    .fab-option::after {
        right: 45px;
    }

    .fab-option:hover::before,
    .fab-option:hover::after {
        right: 55px;
    }
}
</style>

<script>
// FAB Functionality
document.addEventListener('DOMContentLoaded', function() {
    const fabButton = document.getElementById('fabButton');
    const fabOptions = document.getElementById('fabOptions');

    if (fabButton && fabOptions) {
        fabButton.addEventListener('click', function() {
            fabOptions.classList.toggle('show');
        });

        // Close FAB when clicking outside
        document.addEventListener('click', function(event) {
            if (!fabButton.contains(event.target) && !fabOptions.contains(event.target)) {
                fabOptions.classList.remove('show');
            }
        });

        // Close FAB when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                fabOptions.classList.remove('show');
            }
        });
    }
});

// Check if chart elements exist before creating charts
const monthlySalesCanvas = document.getElementById('monthlySalesChart');
const paymentMethodsCanvas = document.getElementById('paymentMethodsChart');
const stockLevelCanvas = document.getElementById('stockLevelChart');
const expiryTimelineCanvas = document.getElementById('expiryTimelineChart');

if (monthlySalesCanvas) {
    // Monthly Sales Chart
    const monthlySalesCtx = monthlySalesCanvas.getContext('2d');

    // Prepare data for monthly sales chart
    const monthlyLabels = @json($charts['monthly_sales']->count() > 0 ? $charts['monthly_sales']->pluck('month')->toArray() : []);
    const monthlySalesData = @json($charts['monthly_sales']->count() > 0 ? $charts['monthly_sales']->pluck('total_sales')->toArray() : []);
    const monthlyRevenueData = @json($charts['monthly_sales']->count() > 0 ? $charts['monthly_sales']->pluck('total_revenue')->toArray() : []);

    // Convert month numbers to month names
    const monthNames = monthlyLabels.map(function(month) {
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return monthNames[month - 1] || month;
    });

    // Convert revenue to millions
    const revenueInMillions = monthlyRevenueData.map(function(revenue) {
        return revenue / 1000000;
    });

    const monthlySalesChart = new Chart(monthlySalesCtx, {
        type: 'line',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Total Penjualan',
                data: monthlySalesData,
                borderColor: 'rgb(78, 115, 223)',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.1
            }, {
                label: 'Total Pendapatan (Juta)',
                data: revenueInMillions,
                borderColor: 'rgb(28, 200, 138)',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                tension: 0.1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Total Penjualan'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Total Pendapatan (Juta)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
}

if (paymentMethodsCanvas) {
    // Payment Methods Chart
    const paymentMethodsCtx = paymentMethodsCanvas.getContext('2d');

    // Prepare data for payment methods chart
    const paymentLabels = @json($charts['payment_methods']->count() > 0 ? $charts['payment_methods']->pluck('payment_method')->toArray() : []);
    const paymentData = @json($charts['payment_methods']->count() > 0 ? $charts['payment_methods']->pluck('total')->toArray() : []);

    const paymentMethodsChart = new Chart(paymentMethodsCtx, {
        type: 'doughnut',
        data: {
            labels: paymentLabels,
            datasets: [{
                data: paymentData,
                backgroundColor: [
                    'rgb(78, 115, 223)',
                    'rgb(28, 200, 138)',
                    'rgb(246, 194, 62)',
                    'rgb(231, 74, 59)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

if (stockLevelCanvas) {
    // Stock Level Distribution Chart
    const stockLevelCtx = stockLevelCanvas.getContext('2d');

    // Prepare data for stock level distribution
    const stockLevelData = {
        labels: ['Stok Aman', 'Stok Menipis', 'Stok Kritis', 'Stok Habis'],
        datasets: [{
            data: [
                @json($stats['total_medicines'] - $stats['low_stock_medicines']),
                @json($stats['low_stock_medicines']),
                0, // Will be calculated
                0  // Will be calculated
            ],
            backgroundColor: [
                'rgb(28, 200, 138)',   // Green - Safe
                'rgb(246, 194, 62)',   // Yellow - Low
                'rgb(231, 74, 59)',    // Red - Critical
                'rgb(108, 117, 125)'   // Gray - Empty
            ],
            hoverOffset: 4
        }]
    };

    // Calculate critical and empty stock
    const criticalStock = @json($lists['low_stock_medicines_list']->where('stock', '<=', 5)->count());
    const emptyStock = @json($lists['low_stock_medicines_list']->where('stock', 0)->count());

    stockLevelData.datasets[0].data[2] = criticalStock;
    stockLevelData.datasets[0].data[3] = emptyStock;

    const stockLevelChart = new Chart(stockLevelCtx, {
        type: 'doughnut',
        data: stockLevelData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

if (expiryTimelineCanvas) {
    // Expiry Timeline Chart
    const expiryTimelineCtx = expiryTimelineCanvas.getContext('2d');

    // Prepare data for expiry timeline (next 30 days)
    const expiryData = {
        labels: ['Hari Ini', '1-7 Hari', '8-14 Hari', '15-21 Hari', '22-30 Hari'],
        datasets: [{
            label: 'Jumlah Obat',
            data: [0, 0, 0, 0, 0], // Will be calculated
            backgroundColor: 'rgba(231, 74, 59, 0.8)',
            borderColor: 'rgb(231, 74, 59)',
            borderWidth: 2
        }]
    };

    // Calculate expiry data from expiring medicines list
    const expiringMedicines = @json($lists['expiring_medicines_list']->map(function($medicine) {
        $daysUntilExpired = \Carbon\Carbon::parse($medicine->expired_date)->diffInDays(now(), false);
        return abs($daysUntilExpired);
    }));

    // Group by time ranges
    expiringMedicines.forEach(days => {
        if (days === 0) {
            expiryData.datasets[0].data[0]++;
        } else if (days <= 7) {
            expiryData.datasets[0].data[1]++;
        } else if (days <= 14) {
            expiryData.datasets[0].data[2]++;
        } else if (days <= 21) {
            expiryData.datasets[0].data[3]++;
        } else if (days <= 30) {
            expiryData.datasets[0].data[4]++;
        }
    });

    const expiryTimelineChart = new Chart(expiryTimelineCtx, {
        type: 'bar',
        data: expiryData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Obat'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Rentang Waktu'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Jumlah Obat: ${context.parsed.y}`;
                        }
                    }
                }
            }
        }
    });
}
</script>
@endpush
