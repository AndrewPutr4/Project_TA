<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Selesaikan Pembayaran</title>
    <script type="text/javascript"
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f4; }
        .payment-card {
            max-width: 500px;
            margin: 50px auto;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card payment-card">
        <div class="card-body p-4 text-center">
            <h2 class="mb-3">Selesaikan Pembayaran Anda</h2>
            <p class="text-muted">Anda akan dialihkan ke halaman pembayaran Midtrans.</p>
            
            <ul class="list-group list-group-flush my-4 text-start">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Nomor Pesanan:</span>
                    <strong>{{ $order->order_number }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total Tagihan:</span>
                    <strong class="text-danger fs-5">Rp{{ number_format($order->total, 0, ',', '.') }}</strong>
                </li>
            </ul>

            <div class="d-grid">
                <button id="pay-button" class="btn btn-warning btn-lg fw-bold">
                    <i class="bi bi-shield-check"></i> Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  document.getElementById('pay-button').onclick = function(){
    // SnapToken is passed from the controller
    snap.pay('{{ $snapToken }}', {
      onSuccess: function(result){
        /* You may add your own implementation here */
        window.location.href = '/order/success/' + '{{ $order->id }}';
        console.log(result);
      },
      onPending: function(result){
        /* You may add your own implementation here */
        alert("wating your payment!"); console.log(result);
      },
      onError: function(result){
        /* You may add your own implementation here */
        alert("payment failed!"); console.log(result);
      },
      onClose: function(){
        /* You may add your own implementation here */
        alert('you closed the popup without finishing the payment');
      }
    });
  };
</script>

</body>
</html>