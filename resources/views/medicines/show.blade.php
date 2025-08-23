@extends('layouts.app')

@section('title', 'Detail Obat: ' . $medicine->name)

@section('content')
@include('components.alerts')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Obat</h1>
        <div>
            <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit fa-sm"></i> Edit
            </a>
            <a href="{{ route('medicines.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Medicine Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Obat</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-primary">{{ $medicine->name }}</h5>
                            <p class="text-muted mb-0">{{ $medicine->description ?: 'Tidak ada deskripsi' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="badge badge-info fs-6">{{ $medicine->code }}</span>
                        </div>
                    </div>

                    <hr>

                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Informasi Dasar</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Kategori:</strong></td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $medicine->category->name ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Merk:</strong></td>
                                    <td>
                                        <span class="badge badge-light">{{ $medicine->brand->name ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Satuan:</strong></td>
                                    <td>
                                        @if($medicine->unit)
                                            <span class="badge badge-info">{{ $medicine->unit->name }} @if($medicine->unit->abbreviation) ({{ $medicine->unit->abbreviation }}) @endif</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Produsen:</strong></td>
                                    <td>
                                        @if($medicine->manufacturer)
                                            <span class="badge badge-secondary">{{ $medicine->manufacturer->name }} @if($medicine->manufacturer->country) - {{ $medicine->manufacturer->country }} @endif</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Status Stok</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Stok Saat Ini:</strong></td>
                                    <td>
                                        @if($medicine->stock <= 0)
                                            <span class="badge badge-danger fs-6">Habis</span>
                                        @elseif($medicine->stock <= ($medicine->min_stock ?? 10))
                                            <span class="badge badge-warning fs-6">{{ $medicine->stock }}</span>
                                        @else
                                            <span class="badge badge-success fs-6">{{ $medicine->stock }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Stok Minimum:</strong></td>
                                    <td>{{ $medicine->min_stock ?? 10 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($medicine->stock <= 0)
                                            <span class="text-danger">Stok Habis</span>
                                        @elseif($medicine->stock <= ($medicine->min_stock ?? 10))
                                            <span class="text-warning">Stok Menipis</span>
                                        @else
                                            <span class="text-success">Stok Aman</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- Pricing Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Informasi Harga</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Harga Beli:</strong></td>
                                    <td>
                                        @if($medicine->purchase_price)
                                            <span class="text-success fs-6">Rp {{ number_format($medicine->purchase_price, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Harga Jual:</strong></td>
                                    <td>
                                        <span class="text-primary fs-6">Rp {{ number_format($medicine->price, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Margin:</strong></td>
                                    <td>
                                        @if($medicine->purchase_price && $medicine->purchase_price > 0)
                                            @php
                                                $margin = (($medicine->price - $medicine->purchase_price) / $medicine->purchase_price) * 100;
                                            @endphp
                                            <span class="text-{{ $margin > 0 ? 'success' : 'danger' }}">
                                                {{ number_format($margin, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-gray-800">Informasi Kadaluarsa</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Tanggal:</strong></td>
                                    <td>
                                        @if($medicine->expired_date < now())
                                            <span class="badge badge-danger fs-6">{{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->expired_date) }}</span>
                                            <br><small class="text-danger">Kadaluarsa</small>
                                        @elseif($medicine->expired_date < now()->addDays(30))
                                            <span class="badge badge-warning fs-6">{{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->expired_date) }}</span>
                                            <br><small class="text-danger">Segera Kadaluarsa</small>
                                        @else
                                            <span class="badge badge-success fs-6">{{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->expired_date) }}</span>
                                            <br><small class="text-success">Masih Berlaku</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Sisa Waktu:</strong></td>
                                    <td>
                                        @if($medicine->expired_date < now())
                                            <span class="text-danger">Sudah Kadaluarsa</span>
                                        @else
                                            @php
                                                $daysLeft = now()->diffInDays($medicine->expired_date, false);
                                            @endphp
                                            @if($daysLeft > 365)
                                                <span class="text-success">{{ floor($daysLeft / 365) }} tahun {{ $daysLeft % 365 }} hari</span>
                                            @elseif($daysLeft > 30)
                                                <span class="text-success">{{ floor($daysLeft / 30) }} bulan {{ $daysLeft % 30 }} hari</span>
                                            @else
                                                <span class="text-{{ $daysLeft > 7 ? 'success' : 'warning' }}">{{ $daysLeft }} hari</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- Additional Information -->
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-gray-800">Informasi Tambahan</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Dibuat:</strong> {{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->created_at, true) }}<br>
                                <strong>Terakhir Diupdate:</strong> {{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->updated_at, true) }}
                            </div>
                            <div class="col-md-6">
                                <strong>ID Obat:</strong> {{ $medicine->id }}<br>
                                <strong>Kode Internal:</strong> {{ $medicine->code }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Obat
                        </a>
                        <a href="{{ route('medicines.add-stock') }}?medicine_id={{ $medicine->id }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Stok
                        </a>
                        <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> Lihat Semua Obat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stock Alerts -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Peringatan Stok</h6>
                </div>
                <div class="card-body">
                    @if($medicine->stock <= 0)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Stok Habis!</strong><br>
                            Obat ini tidak tersedia untuk dijual.
                        </div>
                    @elseif($medicine->stock <= ($medicine->min_stock ?? 10))
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Stok Menipis!</strong><br>
                            Stok saat ini: {{ $medicine->stock }} @if($medicine->unit) {{ $medicine->unit->name }} @endif<br>
                            Minimum: {{ $medicine->min_stock ?? 10 }} @if($medicine->unit) {{ $medicine->unit->name }} @endif
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Stok Aman</strong><br>
                            Stok saat ini: {{ $medicine->stock }} @if($medicine->unit) {{ $medicine->unit->name }} @endif
                        </div>
                    @endif

                    @if($medicine->expired_date < now())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Obat Kadaluarsa!</strong><br>
                            Tanggal: {{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->expired_date) }}
                        </div>
                    @elseif($medicine->expired_date < now()->addDays(30))
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Segera Kadaluarsa!</strong><br>
                            Tanggal: {{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->expired_date) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $medicine->stock }}</h4>
                                <small class="text-muted">Stok Tersedia</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info">@if($medicine->unit) {{ $medicine->unit->name }} @if($medicine->unit->abbreviation) ({{ $medicine->unit->abbreviation }}) @endif @else - @endif</h4>
                            <small class="text-muted">Satuan</small>
                        </div>
                    </div>

                    @if($medicine->purchase_price && $medicine->purchase_price > 0)
                        <hr>
                        <div class="text-center">
                            <h6 class="text-success">Total Nilai Stok</h6>
                            <h4 class="text-success">Rp {{ number_format($medicine->stock * $medicine->purchase_price, 0, ',', '.') }}</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
