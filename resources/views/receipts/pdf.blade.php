<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $sale->invoice_number }}</title>
    <style>
        @page {
            margin: 10mm;
            size: A4;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #333;
        }

        .invoice-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background: #667eea !important;
            color: white !important;
            padding: 10px !important;
            text-align: center !important;
            border-bottom: 2px solid #5a6fd8 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            width: 100% !important;
            margin: 0 !important;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        .logo-container {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .logo {
            width: 45px !important;
            height: 45px !important;
            object-fit: contain !important;
            border-radius: 5px !important;
            background: white !important;
            padding: 3px !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2) !important;
        }

        .logo-placeholder {
            width: 60px !important;
            height: 60px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: white !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2) !important;
        }

        .logo-placeholder i {
            font-size: 24px !important;
            color: #667eea !important;
        }

        .header-text {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .pharmacy-name {
            font-size: 20px !important;
            font-weight: bold !important;
            margin: 0 0 3px 0 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            color: white !important;
            display: block !important;
        }

        .pharmacy-subtitle {
            font-size: 10px !important;
            margin: 0 0 6px 0 !important;
            color: white !important;
            font-weight: 500 !important;
            display: block !important;
        }

        .pharmacy-details {
            display: block !important;
            text-align: center !important;
            font-size: 9px !important;
            color: white !important;
        }

        .detail-item {
            display: block !important;
            margin: 2px 0 !important;
            color: white !important;
        }

        .detail-item i {
            font-size: 9px !important;
            margin-right: 4px !important;
        }

        .content {
            padding: 12px;
        }

        .section {
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 6px;
            padding-bottom: 3px;
            border-bottom: 1px solid #ecf0f1;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .section-title i {
            color: #667eea;
        }

        .transaction-card {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
        }

        .transaction-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .transaction-item {
            display: flex;
            flex-direction: column;
        }

        .transaction-label {
            font-weight: 600;
            color: #7f8c8d;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 1px;
        }

        .transaction-value {
            font-size: 11px;
            font-weight: 500;
            color: #2c3e50;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background: white;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 5px rgba(0,0,0,0.05);
        }

        .items-table thead {
            background: #667eea;
            color: white;
        }

        .items-table th {
            padding: 6px 4px;
            text-align: left;
            font-weight: 600;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .items-table td {
            padding: 6px 4px;
            border-bottom: 1px solid #ecf0f1;
            vertical-align: top;
        }

        .items-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .item-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .item-details {
            font-size: 9px;
            color: #7f8c8d;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .item-detail {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .item-detail i {
            color: #667eea;
            font-size: 8px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-section {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
            padding: 2px 0;
        }

        .summary-row:last-child {
            margin-bottom: 0;
            padding-top: 8px;
            border-top: 2px solid #ecf0f1;
            font-weight: 700;
            font-size: 13px;
            color: #2c3e50;
        }

        .summary-label {
            color: #7f8c8d;
            font-weight: 500;
        }

        .summary-value {
            font-weight: 600;
            color: #2c3e50;
        }

        .payment-method {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .payment-cash { background: #d4edda; color: #155724; }
        .payment-debit { background: #d1ecf1; color: #0c5460; }
        .payment-credit { background: #fff3cd; color: #856404; }
        .payment-transfer { background: #d6d8db; color: #1b1e21; }

        .footer {
            background: #2c3e50;
            color: white;
            padding: 12px;
            text-align: center;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer-left, .footer-right {
            flex: 1;
            min-width: 200px;
        }

        .footer-title {
            font-weight: 600;
            margin-bottom: 4px;
            color: #bdc3c7;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .footer-text {
            font-size: 9px;
            line-height: 1.2;
            opacity: 0.9;
        }

        .timestamp {
            font-size: 8px;
            opacity: 0.7;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #34495e;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-paid { background: #d4edda; color: #155724; }
        .status-unpaid { background: #f8d7da; color: #721c24; }

        @media print {
            body { margin: 0; }
            .invoice-container { box-shadow: none; }
            @page { margin: 8mm; }

            .header {
                background: #667eea !important;
                color: white !important;
                padding: 20px !important;
                text-align: center !important;
                border-bottom: 3px solid #5a6fd8 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 15px !important;
                width: 100% !important;
                margin: 0 !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .logo {
                width: 60px !important;
                height: 60px !important;
                object-fit: contain !important;
                border-radius: 8px !important;
                background: white !important;
                padding: 5px !important;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2) !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .pharmacy-name, .pharmacy-subtitle, .pharmacy-details, .detail-item {
                color: white !important;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
                        <div class="logo-container">
                @php
                    $logoBase64 = \App\Helpers\AppSettingHelper::logoBase64();
                    $logoPath = \App\Helpers\AppSettingHelper::get('logo_path');
                @endphp
                @if($logoBase64 && $logoPath)
                    <img src="{{ $logoBase64 }}" alt="Logo {{ \App\Helpers\AppSettingHelper::pharmacyName() }}" class="logo">
                @else
                    <div class="logo-placeholder">
                        <i class="fas fa-clinic-medical"></i>
                    </div>
                @endif


            </div>
            <div class="header-text">
                <h1 class="pharmacy-name">
                    {{ \App\Helpers\AppSettingHelper::pharmacyName() ?: 'SIAPOTEK POS' }}
                </h1>
                <p class="pharmacy-subtitle">Sistem Informasi Apotek Point of Sale</p>
                <div class="pharmacy-details">
                    @if(\App\Helpers\AppSettingHelper::address())
                        <span class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ \App\Helpers\AppSettingHelper::address() }}
                        </span>
                    @endif
                    @if(\App\Helpers\AppSettingHelper::phone())
                        <span class="detail-item">
                            <i class="fas fa-phone"></i>
                            Telp: {{ \App\Helpers\AppSettingHelper::phone() }}
                        </span>
                    @endif
                    @if(\App\Helpers\AppSettingHelper::get('tax_number'))
                        <span class="detail-item">
                            <i class="fas fa-id-card"></i>
                            NPWP: {{ \App\Helpers\AppSettingHelper::get('tax_number') }}
                        </span>
                    @endif
                    @if(\App\Helpers\AppSettingHelper::get('license_number'))
                        <span class="detail-item">
                            <i class="fas fa-certificate"></i>
                            No. Izin: {{ \App\Helpers\AppSettingHelper::get('license_number') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Transaction Information - All in One Card -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Transaksi
                </div>
                <div class="transaction-card">
                    <div class="transaction-grid">
                        <div class="transaction-item">
                            <div class="transaction-label">Invoice</div>
                            <div class="transaction-value">{{ $sale->invoice_number }}</div>
                        </div>
                        <div class="transaction-item">
                            <div class="transaction-label">Tanggal & Waktu</div>
                            <div class="transaction-value">{{ $sale->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                        <div class="transaction-item">
                            <div class="transaction-label">Kasir</div>
                            <div class="transaction-value">{{ $sale->user->name }}</div>
                        </div>
                        <div class="transaction-item">
                            <div class="transaction-label">Metode</div>
                            <div class="transaction-value">
                                @switch($sale->payment_method)
                                    @case('cash')
                                        <span class="payment-method payment-cash">Tunai</span>
                                        @break
                                    @case('debit')
                                        <span class="payment-method payment-debit">Debit</span>
                                        @break
                                    @case('credit')
                                        <span class="payment-method payment-credit">Kartu Kredit</span>
                                        @break
                                    @case('transfer')
                                        <span class="payment-method payment-transfer">Transfer</span>
                                        @break
                                    @default
                                        <span class="payment-method payment-transfer">{{ $sale->payment_method }}</span>
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-list"></i>
                    Detail Pembelian
                </div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="text-center">Harga Satuan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->saleDetails as $detail)
                            <tr>
                                <td>
                                    <div class="item-name">{{ $detail->medicine->name }}</div>
                                    <div class="item-details">
                                        <div class="item-detail">
                                            <i class="fas fa-barcode"></i>
                                            {{ $detail->medicine->code }}
                                        </div>
                                        @if($detail->medicine->category)
                                            <div class="item-detail">
                                                <i class="fas fa-tags"></i>
                                                {{ $detail->medicine->category->name }}
                                            </div>
                                        @endif
                                        @if($detail->medicine->unit)
                                            <div class="item-detail">
                                                <i class="fas fa-ruler"></i>
                                                {{ $detail->medicine->unit->name }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-calculator"></i>
                    Ringkasan Pembayaran
                </div>
                <div class="summary-section">
                    <div class="summary-row">
                        <span class="summary-label">Total Belanja:</span>
                        <span class="summary-value">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Jumlah Bayar:</span>
                        <span class="summary-value">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Kembalian:</span>
                        <span class="summary-value">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Status Pembayaran:</span>
                        <span class="summary-value">
                            @if($sale->paid_amount >= $sale->total_amount)
                                <span class="status-badge status-paid">Lunas</span>
                            @else
                                <span class="status-badge status-unpaid">Belum Lunas</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            @if($sale->notes)
            <div class="section">
                <div class="section-title">
                    <i class="fas fa-sticky-note"></i>
                    Catatan
                </div>
                <div class="transaction-card">
                    <div class="transaction-value">{{ $sale->notes }}</div>
                </div>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-left">
                    <div class="footer-title">Informasi Penting</div>
                    <div class="footer-text">
                        • Struk ini adalah bukti pembayaran yang sah<br>
                        • Simpan dengan baik untuk keperluan pengembalian<br>
                        • Barang yang sudah dibeli tidak dapat dikembalikan
                    </div>
                </div>
                <div class="footer-right">
                    <div class="footer-title">Terima Kasih</div>
                    <div class="footer-text">
                        Terima kasih atas kepercayaan Anda<br>
                        Semoga lekas sembuh dan sehat selalu
                    </div>
                </div>
            </div>
            <div class="timestamp">
                Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} |
                Sistem: {{ \App\Helpers\AppSettingHelper::pharmacyName() ?: 'SiApotekPOS' }} v1.0
            </div>
        </div>
    </div>
</body>
</html>
