<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Penjualan</title>
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
        .badge-primary { background-color: #cce5ff; color: #004085; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-info { background-color: #d1ecf1; color: #0c5460; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .section-title {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin: 20px 0 15px 0;
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
        <h1>STATISTIK PENJUALAN</h1>
        <p>Apotek POS System</p>
        <p>Tahun: {{ date('Y') }}</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-row">
            <div class="summary-item">
                <h3>Total Penjualan Tahun Ini</h3>
                <div class="value">{{ $monthlyStats->sum('total_sales') }}</div>
            </div>
            <div class="summary-item">
                <h3>Total Pendapatan Tahun Ini</h3>
                <div class="value">Rp {{ number_format($monthlyStats->sum('total_revenue'), 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <h3>Rata-rata per Bulan</h3>
                <div class="value">Rp {{ number_format($monthlyStats->avg('total_revenue'), 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <h3>Bulan Terbaik</h3>
                <div class="value">
                    @if($monthlyStats->count() > 0)
                        {{ date('F', mktime(0, 0, 0, $monthlyStats->sortByDesc('total_revenue')->first()->month, 1)) }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>
    </div>

    <h3 class="section-title">📊 Statistik Penjualan Bulanan ({{ date('Y') }})</h3>
    @if($monthlyStats->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-center">Total Penjualan</th>
                    <th class="text-right">Total Pendapatan</th>
                    <th class="text-right">Rata-rata per Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyStats as $stat)
                <tr>
                    <td><strong>{{ date('F', mktime(0, 0, 0, $stat->month, 1)) }}</strong></td>
                    <td class="text-center">{{ $stat->total_sales }}</td>
                    <td class="text-right">Rp {{ number_format($stat->total_revenue, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($stat->total_revenue / $stat->total_sales, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 20px; color: #666;">
            <p>Belum ada data penjualan</p>
        </div>
    @endif

    <div style="display: flex; gap: 20px;">
        <div style="flex: 1;">
            <h3 class="section-title">⭐ Obat Terlaris</h3>
            @if($topMedicines->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Nama Obat</th>
                            <th class="text-center">Total Terjual</th>
                            <th class="text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topMedicines->take(10) as $medicine)
                        <tr>
                            <td><strong>{{ $medicine->name }}</strong></td>
                            <td class="text-center">
                                <span class="badge badge-primary">{{ $medicine->total_quantity }}</span>
                            </td>
                            <td class="text-right">Rp {{ number_format($medicine->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: 20px; color: #666;">
                    <p>Belum ada data obat terlaris</p>
                </div>
            @endif
        </div>

        <div style="flex: 1;">
            <h3 class="section-title">💳 Statistik Metode Pembayaran</h3>
            @if($paymentMethodStats->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Metode</th>
                            <th class="text-center">Total Transaksi</th>
                            <th class="text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentMethodStats as $payment)
                        <tr>
                            <td>
                                <span class="badge badge-{{ $payment->payment_method == 'cash' ? 'success' : ($payment->payment_method == 'card' ? 'info' : 'warning') }}">
                                    {{ ucfirst($payment->payment_method) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-primary">{{ $payment->total_sales }}</span>
                            </td>
                            <td class="text-right">Rp {{ number_format($payment->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: 20px; color: #666;">
                    <p>Belum ada data metode pembayaran</p>
                </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem Apotek POS</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>
</html>
