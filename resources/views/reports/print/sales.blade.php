<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Print</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body {
                margin: 0;
                padding: 15px;
                background: white !important;
                font-size: 11px;
            }
            .page-break { page-break-before: always; }
            .print-actions { display: none !important; }
            .summary-cards {
                grid-template-columns: repeat(4, 1fr);
                gap: 10px;
                margin-bottom: 20px;
            }
            .summary-card {
                padding: 12px 8px;
                box-shadow: none;
                border: 1px solid #ddd;
            }
            .summary-card .value {
                font-size: 16px;
            }
            .summary-card small {
                font-size: 10px;
            }
            table {
                font-size: 10px;
                margin-bottom: 15px;
                box-shadow: none;
                border: 1px solid #ddd;
            }
            th, td {
                padding: 6px 8px;
                border: 1px solid #ddd;
            }
            th {
                background: #f8f9fa !important;
                color: #333 !important;
                font-size: 10px;
            }
            .section-title {
                font-size: 14px;
                margin: 20px 0 12px 0;
                padding-bottom: 8px;
            }
            .section-title::before {
                height: 16px;
            }
            .print-header {
                margin: -15px -15px 20px -15px;
                padding: 20px 15px;
                border-radius: 0;
            }
            .print-header h1 {
                font-size: 20px;
                margin-bottom: 8px;
            }
            .print-header p {
                font-size: 11px;
                margin: 3px 0;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }

        .print-header {
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 20px;
            margin: -20px -20px 25px -20px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .print-header h1 {
            margin: 0 0 12px 0;
            font-size: 26px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            letter-spacing: 1px;
        }

        .print-header p {
            margin: 6px 0;
            font-size: 13px;
            opacity: 0.95;
            font-weight: 500;
        }

        .print-actions {
            margin-bottom: 30px;
            padding: 22px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
            text-align: center;
        }

        .print-actions button,
        .print-actions a {
            display: inline-block;
            margin: 0 8px 8px 0;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            min-width: 120px;
        }

        .print-actions button:hover,
        .print-actions a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .btn-print {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }

        .btn-export-csv {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
        }

        .btn-export-excel {
            background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
            color: white;
        }

        .btn-export-pdf {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            padding: 22px 18px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }

        .summary-card h3 {
            margin: 0 0 12px 0;
            color: #6c757d;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .summary-card .value {
            font-size: 22px;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .summary-card small {
            font-size: 11px;
            color: #6c757d;
            font-weight: 500;
            display: block;
        }

        .section-title {
            color: #495057;
            border-bottom: 3px solid #007bff;
            padding-bottom: 8px;
            margin: 30px 0 18px 0;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 20px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border-radius: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
        }

        th, td {
            border: 1px solid #e9ecef;
            padding: 10px 12px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-weight: 600;
            color: #495057;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 2px solid #dee2e6;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e3f2fd;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .badge-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #b8dacc;
        }
        .badge-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border: 1px solid #abd5dc;
        }
        .badge-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border: 1px solid #f4d03f;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 2px solid #e9ecef;
            padding-top: 15px;
            font-style: italic;
        }

        .footer p {
            margin: 5px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .stats-card-header {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .stats-card-body {
            padding: 20px;
        }

        .highlight-row {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%) !important;
            font-weight: 600;
        }

        .total-row {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
            font-weight: 700;
            color: #495057;
        }

        .total-row td {
            border-top: 2px solid #007bff;
        }

        @media (max-width: 768px) {
            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .summary-card {
                padding: 15px 10px;
            }

            .summary-card .value {
                font-size: 18px;
            }

            .print-actions button,
            .print-actions a {
                display: block;
                margin-bottom: 10px;
                margin-right: 0;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="print-actions no-print">
        <button type="button" class="btn-print" onclick="window.print()">
            🖨️ Print Laporan
        </button>
        <a href="{{ route('reports.export') }}?period={{ $period ?? 'this_month' }}&start_date={{ $startDate ? $startDate->format('Y-m-d') : '' }}&end_date={{ $endDate ? $endDate->format('Y-m-d') : '' }}&payment_method={{ $paymentMethod ?? '' }}&format=csv" class="btn-export-csv">
            📊 Export CSV
        </a>
        <a href="{{ route('reports.export-excel') }}?period={{ $period ?? 'this_month' }}&start_date={{ $startDate ? $startDate->format('Y-m-d') : '' }}&end_date={{ $endDate ? $endDate->format('Y-m-d') : '' }}&payment_method={{ $paymentMethod ?? '' }}&format=excel" class="btn-export-excel">
            📈 Export Excel
        </a>
        <a href="{{ route('reports.export-pdf') }}?period={{ $period ?? 'this_month' }}&start_date={{ $startDate ? $startDate->format('Y-m-d') : '' }}&end_date={{ $endDate ? $endDate->format('Y-m-d') : '' }}&payment_method={{ $paymentMethod ?? '' }}&format=pdf" class="btn-export-pdf">
            📄 Export PDF
        </a>
    </div>

    <div class="print-header">
        <h1>LAPORAN PENJUALAN</h1>
        <p><strong>Apotek POS System</strong></p>
        @if($period == 'all')
            <p>Periode: <strong>Semua Data</strong></p>
        @elseif($startDate && $endDate)
            <p>Periode: {{ \App\Helpers\DateHelper::formatDDMMYYYY($startDate) }} - {{ \App\Helpers\DateHelper::formatDDMMYYYY($endDate) }}</p>
        @else
            <p>Periode: <strong>Semua Data</strong></p>
        @endif
        @if($paymentMethod && $paymentMethod !== '')
            <p>Metode Pembayaran: <strong>{{ ucfirst($paymentMethod) }}</strong></p>
        @endif
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Penjualan</h3>
            <div class="value">{{ number_format($totalSales) }}</div>
            <small>Transaksi</small>
        </div>
        <div class="summary-card">
            <h3>Total Pendapatan</h3>
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <small>Rupiah</small>
        </div>
        <div class="summary-card">
            <h3>Total Items</h3>
            <div class="value">{{ number_format($totalItems) }}</div>
            <small>Barang</small>
        </div>
        <div class="summary-card">
            <h3>Rata-rata per Transaksi</h3>
            <div class="value">Rp {{ $totalSales > 0 ? number_format($totalRevenue / $totalSales, 0, ',', '.') : '0' }}</div>
            <small>Rupiah</small>
        </div>
    </div>

    @if($paymentMethodStats->count() > 0)
    <h3 class="section-title">💳 Statistik Metode Pembayaran</h3>
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
                <td class="text-right">Rp {{ number_format($payment->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($topMedicines->count() > 0)
    <h3 class="section-title">🏆 Top 10 Obat Terjual</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Kode</th>
                <th class="text-center">Total Qty</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topMedicines as $index => $medicine)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $medicine->name }}</strong></td>
                <td><code>{{ $medicine->code }}</code></td>
                <td class="text-center">{{ number_format($medicine->total_quantity) }}</td>
                <td class="text-right">Rp {{ number_format($medicine->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($dailyStats->count() > 0)
    <h3 class="section-title">📅 Statistik Penjualan Harian</h3>
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
                <td class="text-center">{{ number_format($daily->total_sales) }}</td>
                <td class="text-right">Rp {{ number_format($daily->total_revenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ $daily->total_sales > 0 ? number_format($daily->total_revenue / $daily->total_sales, 0, ',', '.') : '0' }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td><strong>TOTAL</strong></td>
                <td class="text-center"><strong>{{ number_format($dailyStats->sum('total_sales')) }}</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($dailyStats->sum('total_revenue'), 0, ',', '.') }}</strong></td>
                <td class="text-right"><strong>Rp {{ $dailyStats->sum('total_sales') > 0 ? number_format($dailyStats->sum('total_revenue') / $dailyStats->sum('total_sales'), 0, ',', '.') : '0' }}</strong></td>
            </tr>
        </tbody>
    </table>
    @endif

    <h3 class="section-title">📋 Detail Penjualan</h3>
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
                <td>{{ $sale->user->name ?? 'N/A' }}</td>
                <td>
                    <span class="badge badge-{{ $sale->payment_method == 'cash' ? 'success' : ($sale->payment_method == 'card' ? 'info' : 'warning') }}">
                        {{ ucfirst($sale->payment_method) }}
                    </span>
                </td>
                <td class="text-center">{{ $sale->saleDetails->sum('quantity') }}</td>
                <td class="text-right">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data penjualan</td>
            </tr>
            @endforelse
        </tbody>
        @if($sales->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="5"><strong>TOTAL</strong></td>
                <td class="text-center"><strong>{{ number_format($sales->sum(function($sale) { return $sale->saleDetails->sum('quantity'); })) }}</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($sales->sum('total_amount'), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem Apotek POS</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>
</html>
