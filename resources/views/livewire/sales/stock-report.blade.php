<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-boxes me-2"></i>
                            Laporan Stok Obat
                        </h4>
                        <div>
                           {{-- print  --}}
                            <a href="{{ route('reports.print-stock') }}" class="btn btn-info" target="_blank">
                                <i class="fas fa-print me-1"></i> Print
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Form -->
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="category">Kategori</label>
                                        <select class="form-control" wire:model="categoryFilter">
                                            <option value="">Semua Kategori</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="brand">Brand</label>
                                        <select class="form-control" wire:model="brandFilter">
                                            <option value="">Semua Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="stock_status">Status Stok</label>
                                        <select class="form-control" wire:model="stockStatus">
                                            <option value="all">Semua Status</option>
                                            <option value="available">Stok Tersedia</option>
                                            <option value="low">Stok Menipis</option>
                                            <option value="out">Habis</option>
                                            <option value="expiring">Akan Kadaluarsa</option>
                                            <option value="expired">Sudah Expired</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
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

                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="card-title">Total Obat</h6>
                                                <h4>{{ number_format($stockStats['total_medicines']) }}</h4>
                                                <small>Jenis</small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-pills fa-2x"></i>
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
                                                <h6 class="card-title">Stok Tersedia</h6>
                                                <h4>{{ number_format($stockStats['available_stock']) }}</h4>
                                                <small>Jenis</small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-check-circle fa-2x"></i>
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
                                                <h6 class="card-title">Stok Menipis</h6>
                                                <h4>{{ number_format($stockStats['low_stock']) }}</h4>
                                                <small>Jenis</small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="card-title">Total Nilai Stok</h6>
                                                <h4>Rp {{ number_format($stockStats['total_stock_value'], 0, ',', '.') }}</h4>
                                                <small>Rupiah</small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-money-bill-wave fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistik Kategori -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-tags me-2"></i>
                                            Statistik Stok per Kategori
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Kategori</th>
                                                        <th>Jumlah Obat</th>
                                                        <th>Total Stok</th>
                                                        <th>Total Nilai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($categoryStockStats as $stat)
                                                    <tr>
                                                        <td>{{ $stat->category_name }}</td>
                                                        <td>{{ number_format($stat->total_medicines) }}</td>
                                                        <td>{{ number_format($stat->total_stock) }}</td>
                                                        <td>Rp {{ number_format($stat->total_value, 0, ',', '.') }}</td>
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
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Status Stok
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-danger">{{ number_format($stockStats['out_of_stock']) }}</h4>
                                                    <small class="text-muted">Habis</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-warning">{{ number_format($stockStats['expiring_soon']) }}</h4>
                                                    <small class="text-muted">Akan Kadaluarsa</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-danger">{{ number_format($stockStats['expired']) }}</h4>
                                                    <small class="text-muted">Sudah Expired</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Detail Stok -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-list me-2"></i>
                                    Detail Stok Obat
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(count($medicines) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode</th>
                                                <th>Nama Obat</th>
                                                <th>Kategori</th>
                                                <th>Brand</th>
                                                <th>Stok</th>
                                                <th>Harga</th>
                                                <th>Total Nilai</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($medicines as $index => $medicine)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><code>{{ $medicine->code }}</code></td>
                                                <td>
                                                    <a href="{{ route('medicines.show', $medicine->id) }}" class="text-decoration-none">
                                                        <strong>{{ $medicine->name }}</strong>
                                                    </a>
                                                </td>
                                                <td>{{ $medicine->category->name ?? 'N/A' }}</td>
                                                <td>{{ $medicine->brand->name ?? 'N/A' }}</td>
                                                <td>
                                                    @if($medicine->stock == 0)
                                                        <span class="badge bg-danger">{{ $medicine->stock }}</span>
                                                    @elseif($medicine->stock < $medicine->min_stock)
                                                        <span class="badge bg-warning">{{ $medicine->stock }}</span>
                                                    @else
                                                        <span class="badge bg-success">{{ $medicine->stock }}</span>
                                                    @endif
                                                </td>
                                                <td>Rp {{ number_format($medicine->price, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($medicine->stock * $medicine->price, 0, ',', '.') }}</td>
                                                <td>
                                                    @if($medicine->expired_date && $medicine->expired_date <= \Carbon\Carbon::now())
                                                        <span class="badge bg-danger">Expired</span>
                                                    @elseif($medicine->stock == 0)
                                                        <span class="badge bg-danger">Habis</span>
                                                    @elseif($medicine->expired_date && $medicine->expired_date <= \Carbon\Carbon::now()->addDays(30))
                                                        <span class="badge bg-warning">Akan Kadaluarsa</span>
                                                    @elseif($medicine->stock < $medicine->min_stock)
                                                        <span class="badge bg-warning">Menipis</span>
                                                    @else
                                                        <span class="badge bg-success">Tersedia</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak ada data stok</h5>
                                    <p class="text-muted">Tidak ada obat yang sesuai dengan filter yang dipilih.</p>
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


