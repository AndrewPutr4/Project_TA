{{-- resources/views/kasir/transactions/receipt.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - {{ $transaction->transaction_number }}</title>
    <style>
        /* --- Style untuk Tampilan di Layar (Tidak diubah) --- */
        @import url('https://fonts.googleapis.com/css2?family=Inconsolata:wght@400;700&display=swap');

        body.receipt-preview {
            font-family: 'Inconsolata', monospace;
            color: #333;
            width: 300px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .receipt-header p {
            margin: 2px 0;
            font-size: 12px;
        }

        .transaction-details, .item-list, .summary {
            margin-bottom: 15px;
            font-size: 12px;
        }

        .transaction-details table, .item-list table, .summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .item-list th, .item-list td {
            padding: 5px 0;
        }

        .item-list th {
            text-align: left;
            border-bottom: 1px dashed #ccc;
        }

        .item-list td {
            vertical-align: top;
        }

        .summary table td {
            padding: 2px 0;
        }

        .text-right {
            text-align: right;
        }

        .divider {
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
        }

        .actions {
            margin-top: 20px;
            text-align: center;
        }

        .actions button, .actions a button {
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            text-decoration: none;
            color: black;
        }

        /* ================================================================== */
        /* === STYLE KHUSUS UNTUK PRINT KE PRINTER THERMAL 58mm (PENTING) === */
        /* ================================================================== */
        @media print {
            /* Sembunyikan semua elemen di luar area cetak */
            body > *:not(.printable-area) {
                display: none;
            }

            /* Atur halaman kertas struk */
            @page {
                size: 58mm; /* Ukuran kertas struk */
                margin: 0; /* Atur margin: atas, kanan, bawah, kiri */
            }

            /* Atur ulang body untuk print */
            body {
                margin: 0;
                padding: 0;
                background-color: white;
                font-family: 'Inconsolata', monospace; /* Gunakan font monospace yg rapi */
                font-size: 10pt; /* Ukuran font ideal untuk struk */
                color: black;
            }

            /* Tampilkan area yang memang mau dicetak */
            .printable-area {
                display: block;
                width: 100%;
            }

            /* Hapus style layar yang tidak perlu */
            .receipt-preview {
                width: 100%;
                border: none;
                box-shadow: none;
                padding: 0;
            }

            .receipt-header h1 {
                font-size: 14pt; /* Sesuaikan ukuran judul */
            }

            .receipt-header p, .transaction-details, .item-list, .summary, .footer {
                font-size: 10pt; /* Ukuran font konten */
            }

            /* Hapus tombol aksi saat print */
            .actions {
                display: none;
            }

            /* PAKSA TEKS MENJADI TEBAL (BOLD) */
            strong, b {
                font-weight: 700 !important;
            }
        }
    </style>
</head>
<body class="receipt-preview">
    {{-- Bungkus semua konten yang mau dicetak dengan div ini --}}
    <div class="printable-area">
        <div class="receipt-header">
            <h1><strong>Warung Bakso Selingsing</strong></h1>
            <p>Alamat Toko Anda</p>
            <p>Telp: (021) 123-4567</p>
        </div>

        <div class="transaction-details">
            <table>
                <tr>
                    <td>No. Transaksi</td>
                    <td class="text-right">{{ $transaction->transaction_number }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td class="text-right">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td class="text-right">{{ $transaction->kasir->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Pelanggan</td>
                    <td class="text-right">{{ $transaction->customer_name }}</td>
                </tr>
            </table>
        </div>

        <div class="divider"></div>

        <div class="item-list">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->order->orderItems as $item)
                        <tr>
                            <td>{{ $item->menu_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="divider"></div>

        <div class="summary">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">{{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pajak ({{ $transaction->order->tax_percentage }}%)</td>
                    <td class="text-right">{{ number_format($transaction->tax, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Biaya Layanan</td>
                    <td class="text-right">{{ number_format($transaction->service_fee, 0, ',', '.') }}</td>
                </tr>
                @if ($transaction->discount > 0)
                    <tr>
                        <td>Diskon</td>
                        <td class="text-right">-{{ number_format($transaction->discount, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="2"><div class="divider"></div></td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-right"><strong>{{ number_format($transaction->total, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td>Metode Pembayaran</td>
                    <td class="text-right">{{ strtoupper($transaction->payment_method) }}</td>
                </tr>
                <tr>
                    <td>Tunai</td>
                    <td class="text-right">{{ number_format($transaction->cash_received, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td class="text-right"><strong>{{ number_format($transaction->change_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p><strong>Terima kasih atas kunjungan Anda!</strong></p>
        </div>
    </div> {{-- Akhir dari .printable-area --}}

    {{-- Tombol aksi ini TIDAK akan ikut ter-print --}}
    <div class="actions">
        <button onclick="window.print()" class="print-btn">Cetak Struk</button>
        <a href="{{ route('kasir.orders.index') }}" class="order-btn">
            <button type="button">Daftar Order</button>
        </a>
    </div>

    <script>
        // Auto print saat halaman dimuat
        window.onload = function() {
            // Delay sebentar untuk memastikan halaman terload sempurna
            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>
</body>
</html>