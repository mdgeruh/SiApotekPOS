<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Laporan Penjualan
                        </h4>
                        <div>
                            {{-- print  --}}
                            <a href="{{ route('reports.print-sales') }}?period={{ $period }}&start_date={{ $startDate }}&end_date={{ $endDate }}&payment_method={{ $paymentMethod }}" class="btn btn-info" target="_blank">
                                <i class="fas fa-print me-1"></i> Print
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Form -->
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="period">Periode Cepat</label>
                                        <select class="form-control" wire:model.live="period">
                                            <option value="today">Hari Ini</option>
                                            <option value="this_week">Minggu Ini</option>
                                            <option value="this_month">Bulan Ini</option>
                                            <option value="last_3_months">3 Bulan Terakhir</option>
                                            <option value="this_year">Tahun Ini</option>
                                            <option value="last_year">Tahun Lalu</option>
                                            <option value="all">Semua</option>
                                            <option value="custom">Kustom</option>
                                        </select>
                                    </div>
                                </div>
                                @if($showCustomDate || $period == 'custom')
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="start_date">Tanggal Mulai</label>
                                        <input type="date" class="form-control" wire:model.live="startDate">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="end_date">Tanggal Akhir</label>
                                        <input type="date" class="form-control" wire:model.live="endDate">
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="payment_method">Metode Pembayaran</label>
                                        <select class="form-control" wire:model.live="paymentMethod">
                                            <option value="">Semua Metode</option>
                                            <option value="cash">Tunai</option>
                                            <option value="card">Kartu</option>
                                            <option value="transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <button type="button" class="btn btn-secondary" wire:click="resetFilters">
                                                <i class="fas fa-refresh me-1"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Error Message -->
                        @if($errorMessage)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ $errorMessage }}
                            @if($period !== 'all' && $hasData)
                                <button type="button" class="btn btn-sm btn-outline-warning ms-2" wire:click="showAllData">
                                    <i class="fas fa-eye me-1"></i> Lihat Semua Data
                                </button>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <!-- Periode Info -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Periode:</strong>
                            @if($period == 'custom')
                                {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '' }} - {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '' }}
                            @elseif($period == 'all')
                                Semua Data
                            @else
                                {{ ucfirst(str_replace('_', ' ', $period)) }}
                                @if($dateRange['start'] && $dateRange['end'])
                                    ({{ $dateRange['start']->format('d/m/Y') }} - {{ $dateRange['end']->format('d/m/Y') }})
                                @endif
                            @endif
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="card-title">Total Penjualan</h6>
                                                <h4>{{ number_format($totalSales) }}</h4>
                                                <small>Transaksi</small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-shopping-cart fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="card-title">Total Pendapatan</h6>
                                                <h4>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                                                <small>Rupiah</small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-money-bill-wave fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="card-title">Total Item</h6>
                                                <h4>{{ number_format($totalItems) }}</h4>
                                                <small>Barang</small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-boxes fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="card-title">Rata-rata per Transaksi</h6>
                                                <h4>Rp {{ $totalSales > 0 ? number_format($totalRevenue / $totalSales, 0, ',', '.') : '0' }}</h4>
                                                <small>Rupiah</small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-calculator fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistik Metode Pembayaran dan Top Medicines -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-credit-card me-2"></i>
                                            Statistik Metode Pembayaran
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Metode</th>
                                                        <th>Jumlah Transaksi</th>
                                                        <th>Total Pendapatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($paymentMethodStats as $stat)
                                                    <tr>
                                                        <td>
                                                            @switch($stat['payment_method'])
                                                                @case('cash')
                                                                    <span class="badge bg-success">Tunai</span>
                                                                    @break
                                                                @case('card')
                                                                    <span class="badge bg-info">Kartu</span>
                                                                    @break
                                                                @case('transfer')
                                                                    <span class="badge bg-warning">Transfer</span>
                                                                    @break
                                                                @default
                                                                    <span class="badge bg-secondary">{{ ucfirst($stat['payment_method']) }}</span>
                                                            @endswitch
                                                        </td>
                                                        <td>{{ number_format($stat['total_sales']) }}</td>
                                                        <td>Rp {{ number_format($stat['total_revenue'], 0, ',', '.') }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">Tidak ada data</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-pills me-2"></i>
                                            Top 10 Obat Terjual
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Obat</th>
                                                        <th>Kode</th>
                                                        <th>Qty</th>
                                                        <th>Pendapatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($topMedicines as $medicine)
                                                    <tr>
                                                        <td>{{ $medicine['name'] }}</td>
                                                        <td><code>{{ $medicine['code'] }}</code></td>
                                                        <td>{{ number_format($medicine['total_quantity']) }}</td>
                                                        <td>Rp {{ number_format($medicine['total_revenue'], 0, ',', '.') }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Tidak ada data</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Detail Penjualan -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-list me-2"></i>
                                    Detail Penjualan ({{ $sales ? $sales->count() : 0 }} transaksi)
                                </h6>
                            </div>
                            <div class="card-body">
                                @if($sales && $sales->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Invoice</th>
                                                <th>Tanggal</th>
                                                <th>Kasir</th>
                                                <th>Metode Pembayaran</th>
                                                <th>Total Item</th>
                                                <th>Total Bayar</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sales as $index => $sale)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('sales.show', $sale->id) }}" class="text-decoration-none">
                                                        <strong>{{ $sale->invoice_number }}</strong>
                                                    </a>
                                                </td>
                                                <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                                <td>
                                                    @switch($sale->payment_method)
                                                        @case('cash')
                                                            <span class="badge bg-success">Tunai</span>
                                                            @break
                                                        @case('card')
                                                            <span class="badge bg-info">Kartu</span>
                                                            @break
                                                        @case('transfer')
                                                            <span class="badge bg-warning">Transfer</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ ucfirst($sale->payment_method) }}</span>
                                                    @endswitch
                                                </td>
                                                <td>{{ $sale->saleDetails->sum('quantity') }}</td>
                                                <td><strong>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</strong></td>
                                                <td>
                                                    <span class="badge bg-success">Selesai</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak ada data penjualan</h5>
                                    <p class="text-muted">Tidak ada transaksi penjualan untuk periode yang dipilih.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
