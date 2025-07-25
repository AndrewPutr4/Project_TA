<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran</title>
    
    <script type="text/javascript"
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { 
            background-color: #f8f9fa; 
        }
        .payment-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }
        .payment-card {
            width: 100%;
            max-width: 500px; /* Batas lebar maksimum di layar besar */
            border: none;
            border-radius: 1rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="payment-container">
    <div class="card payment-card">
        <div class="card-body p-4 p-md-5 text-center">
            
            <h2 class="fw-bold mb-3">Selesaikan Pembayaran</h2>
            <p class="text-muted mb-4">Anda akan dialihkan ke halaman pembayaran yang aman.</p>
            
            <ul class="list-group list-group-flush my-4 text-start">
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span>Nomor Pesanan</span>
                    <strong>{{ $order->order_number }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span>Total Tagihan</span>
                    <strong class="text-danger fs-5">Rp{{ number_format($order->total, 0, ',', '.') }}</strong>
                </li>
            </ul>

            <div class="d-grid">
                <button id="pay-button" class="btn btn-warning btn-lg fw-bold">
                    <i class="bi bi-shield-check me-2"></i>Bayar Sekarang
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