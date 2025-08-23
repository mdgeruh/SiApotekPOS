<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Statistik Penjualan
                        </h4>
                        <div>
                            {{-- print  --}}
                            <a href="{{ route('reports.print-statistics') }}?period={{ $period }}" class="btn btn-info" target="_blank">
                                <i class="fas fa-print me-1"></i> Print
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Form -->
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="period">Periode</label>
                                        <select class="form-control" wire:model="period">
                                            <option value="today">Hari Ini</option>
                                            <option value="this_week">Minggu Ini</option>
                                            <option value="this_month">Bulan Ini</option>
                                            <option value="last_3_months">3 Bulan Terakhir</option>
                                            <option value="this_year">Tahun Ini</option>
                                            <option value="last_year">Tahun Lalu</option>
                                            <option value="all">Semua</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
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

                        <!-- Periode Info -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Periode:</strong>
                            {{ ucfirst(str_replace('_', ' ', $period)) }}
                            ({{ $dateRange['start']->format('d/m/Y') }} - {{ $dateRange['end']->format('d/m/Y') }})
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="card-title">Total Penjualan</h6>
                                                <h4>{{ number_format($periodStats->sum('total_sales')) }}</h4>
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
                                                <h4>Rp {{ number_format($periodStats->sum('total_revenue'), 0, ',', '.') }}</h4>
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
                                                <h6 class="card-title">Rata-rata per Bulan</h6>
                                                <h4>Rp {{ $periodStats->count() > 0 ? number_format($periodStats->avg('total_revenue'), 0, ',', '.') : '0' }}</h4>
                                                <small>Rupiah</small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-calculator fa-2x"></i>
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
                                                <h6 class="card-title">Bulan Terbaik</h6>
                                                <h4>
                                                    @if($periodStats->count() > 0)
                                                        {{ date('F', mktime(0, 0, 0, $periodStats->sortByDesc('total_revenue')->first()->month, 1)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </h4>
                                                <small>Berdasarkan Pendapatan</small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-trophy fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts and Tables -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-chart-line me-2"></i>
                                            Statistik Penjualan per Bulan
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Periode</th>
                                                        <th>Penjualan</th>
                                                        <th>Pendapatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($periodStats as $stat)
                                                    <tr>
                                                        <td>
                                                            {{ date('F Y', mktime(0, 0, 0, $stat->month, 1, $stat->year)) }}
                                                        </td>
                                                        <td>{{ number_format($stat->total_sales) }}</td>
                                                        <td>Rp {{ number_format($stat->total_revenue, 0, ',', '.') }}</td>
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
                                                        <th>Transaksi</th>
                                                        <th>Pendapatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($paymentStats as $stat)
                                                    <tr>
                                                        <td>
                                                            @switch($stat->payment_method)
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
                                                                    <span class="badge bg-secondary">{{ ucfirst($stat->payment_method) }}</span>
                                                            @endswitch
                                                        </td>
                                                        <td>{{ number_format($stat->total_sales) }}</td>
                                                        <td>Rp {{ number_format($stat->total_revenue, 0, ',', '.') }}</td>
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
                        </div>

                        <div class="row mb-4">
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
                                                        <td>{{ $medicine->name }}</td>
                                                        <td><code>{{ $medicine->code }}</code></td>
                                                        <td>{{ number_format($medicine->total_quantity) }}</td>
                                                        <td>Rp {{ number_format($medicine->total_revenue, 0, ',', '.') }}</td>
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

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-tags me-2"></i>
                                            Top 10 Kategori Terjual
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Kategori</th>
                                                        <th>Qty</th>
                                                        <th>Pendapatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($categoryStats as $category)
                                                    <tr>
                                                        <td>{{ $category->category_name }}</td>
                                                        <td>{{ number_format($category->total_quantity) }}</td>
                                                        <td>Rp {{ number_format($category->total_revenue, 0, ',', '.') }}</td>
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
                        </div>

                        <!-- Statistik Tahunan -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Statistik Penjualan Tahunan
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tahun</th>
                                                <th>Total Penjualan</th>
                                                <th>Total Pendapatan</th>
                                                <th>Rata-rata per Bulan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($yearlyStats as $stat)
                                            <tr>
                                                <td><strong>{{ $stat->year }}</strong></td>
                                                <td>{{ number_format($stat->total_sales) }}</td>
                                                <td>Rp {{ number_format($stat->total_revenue, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($stat->total_revenue / 12, 0, ',', '.') }}</td>
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
            </div>
        </div>
    </div>
</div>


