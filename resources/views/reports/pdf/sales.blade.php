<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
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
        .badge-info { background-color: #d1ecf1; color: #0c5460; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .page-break {
            page-break-before: always;
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
        <h1>LAPORAN PENJUALAN</h1>
        <p>Apotek POS System</p>
        @if(isset($period) && $period == 'all')
            <p>Periode: <strong>Semua Data</strong></p>
        @elseif($startDate && $endDate)
            <p>Periode: {{ \App\Helpers\DateHelper::formatDDMMYYYY($startDate) }} - {{ \App\Helpers\DateHelper::formatDDMMYYYY($endDate) }}</p>
        @else
            <p>Periode: <strong>Semua Data</strong></p>
        @endif
        @if(isset($paymentMethod) && $paymentMethod && $paymentMethod !== '')
            <p>Metode Pembayaran: <strong>{{ ucfirst($paymentMethod) }}</strong></p>
        @endif
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-row">
            <div class="summary-item">
                <h3>Total Penjualan</h3>
                <div class="value">{{ $totalSales }}</div>
            </div>
            <div class="summary-item">
                <h3>Total Pendapatan</h3>
                <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <h3>Rata-rata per Transaksi</h3>
                <div class="value">Rp {{ $totalSales > 0 ? number_format($totalRevenue / $totalSales, 0, ',', '.') : '0' }}</div>
            </div>
        </div>
    </div>

    @if($paymentMethodStats->count() > 0)
    <h3>Statistik Metode Pembayaran</h3>
    <table>
        <thead>
            <tr>
                <th>Metode Pembayaran</th>
                <th class="text-center">Total Transaksi</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentMethodStats as $payment)
            <tr>
                <td>{{ ucfirst($payment->payment_method) }}</td>
                <td class="text-center">{{ $payment->total_sales }}</td>
                <td class="text-right">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($dailyStats->count() > 0)
    <h3>Statistik Penjualan Harian</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-center">Total Penjualan</th>
                <th class="text-right">Total Pendapatan</th>
                <th class="text-right">Rata-rata per Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyStats as $daily)
            <tr>
                <td>{{ \App\Helpers\DateHelper::formatDDMMYYYY($daily->date) }}</td>
                <td class="text-center">{{ $daily->total_sales }}</td>
                <td class="text-right">Rp {{ number_format($daily->total_revenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ $daily->total_sales > 0 ? number_format($daily->total_revenue / $daily->total_sales, 0, ',', '.') : '0' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <h3>Detail Penjualan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Metode Pembayaran</th>
                <th class="text-center">Total Items</th>
                <th class="text-right">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $index => $sale)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $sale->invoice_number }}</strong></td>
                <td>{{ \App\Helpers\DateHelper::formatDDMMYYYY($sale->created_at, true) }}</td>
                <td>{{ $sale->user->name }}</td>
                <td>
                    <span class="badge badge-{{ $sale->payment_method == 'cash' ? 'success' : ($sale->payment_method == 'card' ? 'info' : 'warning') }}">
                        {{ ucfirst($sale->payment_method) }}
                    </span>
                </td>
                <td class="text-center">{{ $sale->saleDetails->count() ?? 0 }}</td>
                <td class="text-right">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data penjualan</td>
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
