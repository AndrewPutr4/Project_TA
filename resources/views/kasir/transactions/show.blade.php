@extends('layouts.kasir')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-md mx-auto">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Warung Ibu Titin</h1>
            <p class="text-gray-600">Jl. Raya Uluwatu No. 123, Kuta Selatan, Bali</p>
            <p class="text-gray-600">Telp: 0812-3456-7890</p>
        </div>

        <div class="border-t border-b border-dashed border-gray-400 py-4 my-4">
            <div class="flex justify-between text-sm">
                <span class="font-semibold">No. Transaksi:</span>
                <span>{{ $transaction->id }}</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="font-semibold">Tanggal:</span>
                <span>{{ $transaction->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="font-semibold">Kasir:</span>
                <span>{{ $transaction->kasir->name }}</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="font-semibold">Pelanggan:</span>
                <span>{{ $transaction->order->customer_name }}</span>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold mb-2">Detail Pesanan:</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">Item</th>
                        <th class="text-center py-2">Qty</th>
                        <th class="text-right py-2">Harga</th>
                        <th class="text-right py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->order->orderItems as $item)
                    <tr>
                        <td class="py-2">{{ $item->menu->name }}</td>
                        <td class="text-center py-2">{{ $item->quantity }}</td>
                        <td class="text-right py-2">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right py-2">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t border-dashed border-gray-400 pt-4 mt-4">
            <div class="flex justify-between font-semibold">
                <span>Total</span>
                <span>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span>Bayar</span>
                <span>Rp{{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span>Kembali</span>
                <span>Rp{{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="text-center mt-8">
            <p class="text-gray-600 text-sm">Terima kasih atas kunjungan Anda!</p>
        </div>

        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('kasir.transactions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
            <a href="{{ route('kasir.transactions.receipt', $transaction->id) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-print mr-2"></i>Cetak Struk
            </a>
        </div>
    </div>
</div>
@endsection
