<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $sale->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
            background-color: white;
        }

        .receipt {
            max-width: 300px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .pharmacy-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .pharmacy-address {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .pharmacy-phone {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .transaction-info {
            margin-bottom: 15px;
        }

        .transaction-info table {
            width: 100%;
        }

        .transaction-info td {
            padding: 2px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .items-table th {
            border-bottom: 1px solid #000;
            padding: 5px 0;
            text-align: left;
            font-weight: bold;
        }

        .items-table td {
            padding: 3px 0;
            border-bottom: 1px dotted #ccc;
        }

        .item-name {
            font-weight: bold;
        }

        .item-details {
            font-size: 10px;
            color: #666;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-section {
            border-top: 1px solid #000;
            padding-top: 10px;
            margin-bottom: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .total-label {
            font-weight: bold;
        }

        .payment-info {
            border-top: 1px solid #000;
            padding-top: 10px;
            margin-bottom: 15px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .thank-you {
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
        }

        @media print {
            body {
                padding: 0;
            }

            .receipt {
                max-width: none;
                width: 100%;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="pharmacy-name">SIAPOTEK POS</div>
            <div class="pharmacy-address">Jl. Contoh No. 123, Kota</div>
            <div class="pharmacy-phone">Telp: (021) 1234-5678</div>
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <table>
                <tr>
                    <td>No. Invoice:</td>
                    <td class="text-right">{{ $sale->invoice_number }}</td>
                </tr>
                <tr>
                    <td>Tanggal:</td>
                    <td class="text-right">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Kasir:</td>
                    <td class="text-right">{{ $sale->user->name }}</td>
                </tr>
            </table>
        </div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleDetails as $detail)
                    <tr>
                        <td>
                            <div class="item-name">{{ $detail->medicine->name }}</div>
                            <div class="item-details">
                                {{ $detail->medicine->code }} |
                                {{ $detail->medicine->unit ? $detail->medicine->unit->name : '-' }}
                            </div>
                        </td>
                        <td class="text-right">{{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $detail->quantity }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span class="total-label">Total Belanja:</span>
                <span class="total-label">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="total-row">
                <span>Jumlah Bayar:</span>
                <span>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
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
                            {{ $sale->payment_method }}
                    @endswitch
                </span>
            </div>
        </div>

        @if($sale->notes)
            <div class="divider"></div>
            <div><strong>Catatan:</strong></div>
            <div>{{ $sale->notes }}</div>
        @endif

        <div class="divider"></div>

        <div class="thank-you">TERIMA KASIH</div>
        <div class="thank-you">SELAMAT DATANG KEMBALI</div>

        <div class="footer">
            <div>Struk ini adalah bukti pembayaran yang sah</div>
            <div>Simpan dengan baik untuk keperluan pengembalian</div>
            <div>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>

    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Cetak Struk
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ❌ Tutup
        </button>
    </div>
</body>
</html>
