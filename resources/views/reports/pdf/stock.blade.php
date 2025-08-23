<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Obat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .summary-item {
            flex: 1;
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            margin: 0 5px;
        }
        .summary-item h3 {
            margin: 0;
            color: #333;
            font-size: 14px;
        }
        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .badge-info { background-color: #d1ecf1; color: #0c5460; }
        .badge-secondary { background-color: #e2e3e5; color: #383d41; }
        .alert-section {
            margin-bottom: 20px;
        }
        .alert-section h3 {
            color: #721c24;
            border-bottom: 1px solid #721c24;
            padding-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN STOK OBAT</h1>
        <p>Apotek POS System</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-row">
            <div class="summary-item">
                <h3>Total Obat</h3>
                <div class="value">{{ $stockStats['total_medicines'] }}</div>
            </div>
            <div class="summary-item">
                <h3>Stok Tersedia</h3>
                <div class="value">{{ $stockStats['total_medicines'] - $stockStats['low_stock'] - $stockStats['out_of_stock'] }}</div>
            </div>
            <div class="summary-item">
                <h3>Stok Menipis</h3>
                <div class="value">{{ $stockStats['low_stock'] }}</div>
            </div>
            <div class="summary-item">
                <h3>Total Nilai Stok</h3>
                <div class="value">Rp {{ number_format($stockStats['total_stock_value'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    @if($lowStockMedicines->count() > 0)
    <div class="alert-section">
        <h3>⚠️ Stok Menipis ({{ $lowStockMedicines->count() }})</h3>
        <table>
            <thead>
                <tr>
                    <th>Obat</th>
                    <th>Kode</th>
                    <th>Stok</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockMedicines->take(10) as $medicine)
                <tr>
                    <td>{{ $medicine->name }}</td>
                    <td>{{ $medicine->code }}</td>
                    <td class="text-center">
                        <span class="badge badge-warning">{{ $medicine->stock }}</span>
                    </td>
                    <td class="text-center">{{ $medicine->unit->name ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($lowStockMedicines->count() > 10)
        <p style="text-align: center; color: #666; font-style: italic;">
            Dan {{ $lowStockMedicines->count() - 10 }} obat lainnya...
        </p>
        @endif
    </div>
    @endif

    @if($expiringMedicines->count() > 0)
    <div class="alert-section">
        <h3>⏰ Akan Expired ({{ $expiringMedicines->count() }})</h3>
        <table>
            <thead>
                <tr>
                    <th>Obat</th>
                    <th>Kode</th>
                    <th>Expired Date</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expiringMedicines->take(10) as $medicine)
                <tr>
                    <td>{{ $medicine->name }}</td>
                    <td>{{ $medicine->code }}</td>
                    <td class="text-center">
                        <span class="badge badge-danger">{{ \App\Helpers\DateHelper::formatDDMMYYYY($medicine->expired_date) }}</span>
                    </td>
                    <td class="text-center">{{ $medicine->stock }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($expiringMedicines->count() > 10)
        <p style="text-align: center; color: #666; font-style: italic;">
            Dan {{ $expiringMedicines->count() - 10 }} obat lainnya...
        </p>
        @endif
    </div>
    @endif

    <h3>Daftar Semua Obat</h3>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Obat</th>
                <th>Kategori</th>
                <th>Brand</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Unit</th>
                <th class="text-right">Harga</th>
                <th class="text-center">Status</th>
                <th class="text-center">Expired Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medicines as $medicine)
            <tr>
                <td>{{ $medicine->code }}</td>
                <td>
                    <strong>{{ $medicine->name }}</strong>
                    @if($medicine->description)
                        <br><small style="color: #666;">{{ Str::limit($medicine->description, 50) }}</small>
                    @endif
                </td>
                <td>{{ $medicine->category->name ?? '-' }}</td>
                <td>{{ $medicine->brand->name ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $medicine->stock == 0 ? 'danger' : ($medicine->stock <= 10 ? 'warning' : 'success') }}">
                        {{ $medicine->stock }}
                    </span>
                </td>
                <td class="text-center">{{ $medicine->unit->name ?? 'N/A' }}</td>
                <td class="text-right">Rp {{ number_format($medicine->price, 0, ',', '.') }}</td>
                <td class="text-center">
                    @if($medicine->stock == 0)
                        <span class="badge badge-danger">Habis</span>
                    @elseif($medicine->stock <= 10)
                        <span class="badge badge-warning">Menipis</span>
                    @else
                        <span class="badge badge-success">Tersedia</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($medicine->expired_date)
                        @php
                            $expiredDate = \Carbon\Carbon::parse($medicine->expired_date);
                            $now = \Carbon\Carbon::now();
                            $daysUntilExpired = $now->diffInDays($expiredDate, false);
                        @endphp
                        <span class="badge badge-{{ $daysUntilExpired < 0 ? 'danger' : ($daysUntilExpired <= 30 ? 'warning' : 'success') }}">
                            {{ \App\Helpers\DateHelper::formatDDMMYYYY($expiredDate) }}
                            @if($daysUntilExpired < 0)
                                <br><small>Expired</small>
                            @elseif($daysUntilExpired <= 30)
                                <br><small>{{ $daysUntilExpired }} hari lagi</small>
                            @endif
                        </span>
                    @else
                        <span class="badge badge-secondary">N/A</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data obat</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem Apotek POS</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>
</html>
