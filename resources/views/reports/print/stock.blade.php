<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Obat - Print</title>
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
            .total-row {
                background: #e3f2fd !important;
                border-top: 2px solid #2196f3;
            }
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

        .print-actions {
            margin-bottom: 30px;
            padding: 22px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
            text-align: center;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
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

        .total-row {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%) !important;
            font-weight: 700;
            border-top: 2px solid #2196f3;
        }

        .total-row td {
            border-top: 2px solid #2196f3;
            font-weight: 700;
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
        .badge-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border: 1px solid #f4d03f;
        }
        .badge-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f1b0b7;
        }
        .badge-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border: 1px solid #abd5dc;
        }
        .badge-secondary {
            background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%);
            color: #383d41;
            border: 1px solid #c6c8ca;
        }

        .alert-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
        }

        .alert-section h3 {
            color: #856404;
            margin: 0 0 15px 0;
            font-size: 16px;
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

            .print-actions {
                padding: 15px;
            }

            .print-actions button,
            .print-actions a {
                padding: 10px 15px;
                font-size: 12px;
                min-width: 100px;
            }
        }

        @media (max-width: 480px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }

            .print-actions {
                flex-direction: column;
                align-items: center;
            }

            .print-actions button,
            .print-actions a {
                margin: 5px 0;
                width: 100%;
                max-width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="print-actions no-print">
        <button type="button" class="btn-print" onclick="window.print()">
            🖨️ Print Laporan
        </button>
        <a href="{{ route('reports.export-stock') }}" class="btn-export-csv">
            📊 Export CSV
        </a>
        <a href="{{ route('reports.export-stock-excel') }}?format=excel" class="btn-export-excel">
            📈 Export Excel
        </a>
        <a href="{{ route('reports.export-stock-pdf') }}?format=pdf" class="btn-export-pdf">
            📄 Export PDF
        </a>
    </div>

    <div class="print-header">
        <h1>LAPORAN STOK OBAT</h1>
        <p><strong>Apotek POS System</strong></p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Obat</h3>
            <div class="value">{{ $stockStats['total_medicines'] }}</div>
            <small>Obat</small>
        </div>
        <div class="summary-card">
            <h3>Stok Tersedia</h3>
            <div class="value">{{ $stockStats['total_medicines'] - $stockStats['low_stock'] - $stockStats['out_of_stock'] }}</div>
            <small>Obat</small>
        </div>
        <div class="summary-card">
            <h3>Stok Menipis</h3>
            <div class="value">{{ $stockStats['low_stock'] }}</div>
            <small>Obat</small>
        </div>
        <div class="summary-card">
            <h3>Total Nilai Stok</h3>
            <div class="value">Rp {{ number_format($stockStats['total_stock_value'], 0, ',', '.') }}</div>
            <small>Rupiah</small>
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

    <h3 class="section-title">📋 Daftar Semua Obat</h3>
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
        @if($medicines->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="5"><strong>TOTAL</strong></td>
                <td class="text-center"><strong>{{ $medicines->sum('stock') }}</strong></td>
                <td class="text-center"><strong>-</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($medicines->sum('price'), 0, ',', '.') }}</strong></td>
                <td class="text-center"><strong>-</strong></td>
                <td class="text-center"><strong>-</strong></td>
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
