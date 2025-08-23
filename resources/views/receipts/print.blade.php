<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Thermal - {{ \App\Helpers\AppSettingHelper::pharmacyName() ?: 'Apotek' }}</title>
    <style>
        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
                max-width: 80mm;
            }
            body {
                margin: 0 !important;
                padding: 0 !important;
                width: 80mm !important;
                max-width: 80mm !important;
                min-width: 80mm !important;
            }
            .no-print { display: none !important; }
            .receipt {
                width: 80mm !important;
                max-width: 80mm !important;
                min-width: 80mm !important;
                margin: 0 !important;
                padding: 3mm !important;
                border: none !important;
                box-shadow: none !important;
                page-break-after: avoid;
                page-break-before: avoid;
            }
            .receipt * {
                font-size: 10px !important;
                line-height: 1.2 !important;
            }
            .pharmacy-name {
                font-size: 14px !important;
            }
            .thank-you {
                font-size: 11px !important;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            background: white;
            width: 80mm;
            max-width: 80mm;
            min-width: 80mm;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .receipt {
            width: 80mm;
            max-width: 80mm;
            min-width: 80mm;
            margin: 0 auto;
            padding: 3mm;
            background: white;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .header {
            text-align: center;
            padding-bottom: 6px;
            margin-bottom: 6px;
            border-bottom: 1px solid #000;
        }

        .pharmacy-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pharmacy-info {
            font-size: 9px;
            color: #000;
            margin-bottom: 2px;
            text-align: center;
        }

        .transaction-info {
            padding-bottom: 6px;
            margin-bottom: 6px;
            border-bottom: 1px solid #000;
        }

        .transaction-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 10px;
            align-items: center;
        }

        .transaction-label {
            font-weight: bold;
            color: #000;
        }

        .transaction-value {
            color: #000;
            font-weight: normal;
        }

        .items {
            padding-bottom: 6px;
            margin-bottom: 6px;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-weight: bold;
            font-size: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        .items-header span:nth-child(1) { width: 35%; }
        .items-header span:nth-child(2) { width: 25%; text-align: right; }
        .items-header span:nth-child(3) { width: 15%; text-align: center; }
        .items-header span:nth-child(4) { width: 25%; text-align: right; }

        .item {
            margin-bottom: 3px;
            padding: 1px 0;
            border-bottom: 1px dotted #ccc;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 1px;
            font-size: 10px;
            color: #000;
            word-wrap: break-word;
        }

        .item-details {
            font-size: 8px;
            color: #666;
            margin-bottom: 1px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1px;
        }

        .item-quantity {
            color: #000;
            font-size: 9px;
        }

        .item-subtotal {
            font-weight: bold;
            color: #000;
            font-size: 9px;
        }

        .total-section {
            padding-bottom: 6px;
            margin-bottom: 6px;
            border-top: 1px solid #000;
            padding-top: 6px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 10px;
            align-items: center;
        }

        .total-row.grand-total {
            font-weight: bold;
            font-size: 11px;
            padding-top: 3px;
            margin-top: 3px;
            color: #000;
        }

        .payment-info {
            border-top: 1px solid #000;
            padding-top: 6px;
            margin-bottom: 6px;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            color: #000;
            margin-top: 6px;
        }

        .footer-line {
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .thank-you {
            text-align: center;
            font-weight: bold;
            margin: 6px 0;
            font-size: 11px;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .timestamp {
            border-top: 1px dashed #000;
            padding-top: 3px;
            margin-top: 4px;
            font-size: 8px;
            color: #666;
            line-height: 1.2;
            text-align: center;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .print-button:hover {
            background: #0056b3;
        }

        .auto-print-notice {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            z-index: 1000;
        }

        .quote-section {
            text-align: center;
            margin: 6px 0;
            padding: 4px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }

        .quote-text {
            font-style: italic;
            font-size: 9px;
            color: #000;
            line-height: 1.2;
        }

        /* Force narrow width for all elements */
        * {
            max-width: 80mm !important;
            box-sizing: border-box;
        }

        /* Ensure text doesn't overflow */
        .pharmacy-name, .pharmacy-info, .transaction-row, .item-name, .item-details {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 80mm) {
            body, .receipt {
                width: 100vw;
                max-width: 100vw;
            }
        }
    </style>
</head>
<body>
    <div class="auto-print-notice no-print">
        <i class="fas fa-print"></i> Struk akan otomatis print dalam 3 detik
    </div>

    <button class="print-button no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Print Manual
    </button>

    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="pharmacy-name">
                {{ \App\Helpers\AppSettingHelper::pharmacyName() ?: 'SIAPOTEK POS' }}
            </div>
            @if(\App\Helpers\AppSettingHelper::address())
                <div class="pharmacy-info">{{ \App\Helpers\AppSettingHelper::address() }}</div>
            @endif
            @if(\App\Helpers\AppSettingHelper::phone())
                <div class="pharmacy-info">Telp: {{ \App\Helpers\AppSettingHelper::phone() }}</div>
            @endif
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div class="transaction-row">
                <span class="transaction-label">No. Invoice:</span>
                <span class="transaction-value">{{ $sale->invoice_number ?? 'TRX-' . date('YmdHis') }}</span>
            </div>
            <div class="transaction-row">
                <span class="transaction-label">Tanggal:</span>
                <span class="transaction-value">{{ $sale->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="transaction-row">
                <span class="transaction-label">Kasir:</span>
                <span class="transaction-value">{{ $sale->user->name ?? 'Kasir' }}</span>
            </div>
        </div>

        <!-- Items -->
        <div class="items">
            <div class="items-header">
                <span>Item</span>
                <span>Harga</span>
                <span>Qty</span>
                <span>Total</span>
            </div>
            @foreach($sale->saleDetails ?? [] as $item)
                <div class="item">
                    <div class="item-name">{{ $item->medicine->name ?? 'Obat' }}</div>
                    <div class="item-details">
                        {{ $item->medicine->code ?? 'OBT' . date('Ymd') }} |
                        {{ $item->medicine->unit ? $item->medicine->unit->name : 'Botol' }}
                    </div>
                    <div class="item-row">
                        <span class="item-quantity">{{ $item->quantity ?? 1 }} x {{ number_format($item->price ?? 0, 0, ',', '.') }}</span>
                        <span class="item-subtotal">{{ number_format(($item->quantity ?? 1) * ($item->price ?? 0), 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row grand-total">
                <span>Total Belanja:</span>
                <span>Rp {{ number_format($sale->total_amount ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="total-row">
                <span>Jumlah Bayar:</span>
                <span>Rp {{ number_format($sale->paid_amount ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Kembalian:</span>
                <span>Rp {{ number_format(($sale->paid_amount ?? 0) - ($sale->total_amount ?? 0), 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Metode:</span>
                <span>
                    @switch($sale->payment_method)
                        @case('cash')
                            Tunai
                            @break
                        @case('debit')
                            Debit
                            @break
                        @case('credit')
                            Kartu Kredit
                            @break
                        @case('transfer')
                            Transfer Bank
                            @break
                        @default
                            {{ $sale->payment_method ?? 'Tunai' }}
                    @endswitch
                </span>
            </div>
        </div>

        <!-- Quote Section -->
        <div class="quote-section">
            <div class="quote-text">
                "Semoga lekas sembuh dan sehat selalu"
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">TERIMA KASIH</div>
            <div class="thank-you">SELAMAT DATANG KEMBALI</div>
            <div class="footer-line">Struk ini adalah bukti pembayaran yang sah</div>
            <div class="footer-line">Simpan dengan baik untuk keperluan pengembalian</div>
        </div>

        <!-- Timestamp -->
        <div class="timestamp">
            Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <script>
        // Auto-print untuk thermal printer
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 3000); // Print otomatis dalam 3 detik
        };

        // Listen for app settings changes
        if (window.BroadcastChannel) {
            const channel = new BroadcastChannel('pharmacyInfo');
            channel.onmessage = function(event) {
                // Refresh page when pharmacy info changes
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            };
        }
    </script>
</body>
</html>
