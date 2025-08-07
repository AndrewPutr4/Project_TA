<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>customer</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  
  <link href="/assets/img/favicon.png" rel="icon">
  <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
  
  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  
  <link href="/assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">
      <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
        <h1 class="sitename">Warung Bakso Selingsing</h1>
        <span>.</span>
      </a>
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Menu</a></li>
          <li><a href="{{ route('orders.history') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">Riwayat Pesanan</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <main class="main">
    <section id="menu" class="menu section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Our Menu</h2>
        <p><span>Check Our</span> <span class="description-title">Bakso Selingsing Menu</span></p>
      </div><div class="container">
        <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
          <li class="nav-item">
            <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#menu-foods">
              <h4>MAKANAN</h4>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#menu-drinks">
              <h4>MINUMAN</h4>
            </a>
          </li>
        </ul>

        <div class="tab-content" data-aos="fade-up" data-aos-delay="200">
          {{-- Tab Makanan --}}
          <div class="tab-pane fade active show" id="menu-foods">
            <div class="tab-header text-center">
              <p>Menu</p>
              <h3>Makanan</h3>
            </div>
            <div class="row gy-5">
              @forelse($foods as $food)
                <div class="col-lg-4 menu-item">
                  <a href="{{ $food->image ? asset('storage/'.$food->image) : 'https://placehold.co/400x300?text=No+Image' }}" class="glightbox">
                    <img src="{{ $food->image ? asset('storage/'.$food->image) : 'https://placehold.co/400x300?text=No+Image' }}" class="menu-img img-fluid" alt="{{ $food->name }}">
                  </a>
                  <h4>{{ $food->name }}</h4>
                  <p class="ingredients">
                    {{ $food->description ?? '-' }}
                  </p>
                  <p class="price">
                    Rp{{ number_format($food->price, 0, ',', '.') }}
                  </p>
                  <div class="order-controls d-flex align-items-center justify-content-center mt-2" data-menu="{{ $food->id }}">
                    <button class="btn btn-sm btn-outline-secondary btn-minus" style="display:none;">-</button>
                    <span class="order-qty mx-2" style="display:none;">0</span>
                    <button class="btn btn-sm btn-outline-primary btn-plus">+</button>
                  </div>
                </div>
              @empty
                <div class="col-12 text-center text-muted">Tidak ada makanan tersedia.</div>
              @endforelse
            </div>
          </div>

          {{-- Tab Minuman --}}
          <div class="tab-pane fade" id="menu-drinks">
            <div class="tab-header text-center">
              <p>Menu</p>
              <h3>Minuman</h3>
            </div>
            <div class="row gy-5">
              @forelse($drinks as $drink)
                <div class="col-lg-4 menu-item">
                  <a href="{{ $drink->image ? asset('storage/'.$drink->image) : 'https://placehold.co/400x300?text=No+Image' }}" class="glightbox">
                    <img src="{{ $drink->image ? asset('storage/'.$drink->image) : 'https://placehold.co/400x300?text=No+Image' }}" class="menu-img img-fluid" alt="{{ $drink->name }}">
                  </a>
                  <h4>{{ $drink->name }}</h4>
                  <p class="ingredients">
                    {{ $drink->description ?? '-' }}
                  </p>
                  <p class="price">
                    Rp{{ number_format($drink->price, 0, ',', '.') }}
                  </p>
                  <div class="order-controls d-flex align-items-center justify-content-center mt-2" data-menu="{{ $drink->id }}">
                    <button class="btn btn-sm btn-outline-secondary btn-minus" style="display:none;">-</button>
                    <span class="order-qty mx-2" style="display:none;">0</span>
                    <button class="btn btn-sm btn-outline-primary btn-plus">+</button>
                  </div>
                </div>
              @empty
                <div class="col-12 text-center text-muted">Tidak ada minuman tersedia.</div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </section><div id="checkout-bar" class="checkout-bar">
      <div class="container d-flex justify-content-between align-items-center">
        <span class="fw-bold" id="checkout-total-label">Check Out</span>
        <a href="#" class="btn btn-warning fw-bold px-4 py-2" id="checkout-btn" style="border-radius: 24px;">Check Out</a>
      </div>
    </div>
  </main>

  <footer id="footer" class="footer dark-background">
    <div class="container">
      <div class="row gy-3">
        <div class="col-lg-3 col-md-6 d-flex">
          <i class="bi bi-geo-alt icon"></i>
          <div class="address">
            <h4>Address</h4>
            <p>Jl. Raya Abianbase No.80, Dalung</p>
            <p>Kec. Kuta Utara, Kabupaten Badung, Bali</p>
            <p></p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 d-flex">
          <i class="bi bi-telephone icon"></i>
          <div>
            <h4>Contact</h4>
            <p>
              <strong>Phone:</strong> <span>0813-5375-9061</span><br>
              <strong>Email:</strong> <span>baksobalung779@gmail.com</span><br>
            </p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 d-flex">
          <i class="bi bi-clock icon"></i>
          <div>
            <h4>Opening Hours</h4>
            <p>
              <strong>Open Everyday</strong><br>
              <strong>Monday-Sanday:</strong> <span>10.00 AM - 22.00 PM</span><br>
            </p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <h4>Follow Us</h4>
          <div class="social-links d-flex">
            
            <a href="https://www.facebook.com/share/1CHSi3ebgg/?mibextid=wwXIfr" class="facebook"><i class="bi bi-facebook"></i></a>
            <a href="https://www.instagram.com/bakso_balung_slingsing?igsh=dzU5ZzZwc3ZycHRm" class="instagram"><i class="bi bi-instagram"></i></a>
            
          </div>
        </div>
      </div>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/php-email-form/validate.js"></script>
  <script src="/assets/vendor/aos/aos.js"></script>
  <script src="/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>

  <script src="/assets/js/main.js"></script>

  <style>
    .order-controls .btn {
      width: 32px;
      height: 32px;
      padding: 0;
      font-size: 1.2rem;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .order-controls .order-qty {
      min-width: 24px;
      text-align: center;
      font-weight: bold;
      font-size: 1.1rem;
    }

    .menu-item {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      padding: 1.2rem 0.8rem 1.5rem 0.8rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 1.5rem;
      min-height: 350px;
      height: 100%;
      transition: box-shadow 0.2s;
    }

    .menu-item:hover {
      box-shadow: 0 6px 24px rgba(0,0,0,0.10);
    }

    .menu-img {
      width: 100%;
      max-width: 220px;
      height: 160px;
      object-fit: cover;
      border-radius: 14px;
      margin-bottom: 0.7rem;
      box-shadow: 0 1px 6px rgba(0,0,0,0.04);
      background: #f3f3f3;
      display: block;
    }

    @media (max-width: 767.98px) {
      .row.gy-5 {
        row-gap: 1.2rem !important;
      }
      .menu-item {
        margin-bottom: 1.5rem;
        border-radius: 14px;
        min-height: 260px;
        padding: 0.8rem 0.3rem 1.2rem 0.3rem;
      }
      .menu-img {
        max-width: 100%;
        height: 130px;
        border-radius: 12px;
      }
    }

    @media (max-width: 575.98px) {
      .row.gy-5 {
        row-gap: 0.7rem !important;
      }
      .menu-item {
        margin-bottom: 1rem;
        padding: 0.7rem 0.2rem;
        border-radius: 10px;
        min-height: 180px;
      }
      .menu-img {
        height: 100px;
        border-radius: 10px;
      }
      .order-controls .btn {
        width: 28px;
        height: 28px;
        font-size: 1rem;
      }
      .order-controls .order-qty {
        font-size: 1rem;
      }
    }

    .checkout-bar {
      position: fixed;
      left: 0;
      right: 0;
      bottom: 0;
      background: #fffbe6;
      border-top: 1px solid #ffe6a1;
      box-shadow: 0 -2px 12px rgba(255,180,0,0.07);
      z-index: 999;
      padding: 0.6rem 0;
      display: none; /* ✅ Sembunyikan secara default */
    }

    .checkout-bar.show {
      display: block; /* ✅ Tampilkan saat ada item */
    }

    .checkout-bar .container {
      max-width: 600px;
    }

    @media (max-width: 767.98px) {
      .checkout-bar .container {
        padding-left: 18px;
        padding-right: 18px;
      }
      .checkout-bar {
        padding: 0.5rem 0;
      }
      #checkout-btn {
        font-size: 1.05rem;
        padding: 0.5rem 1.5rem;
      }
    }

    body {
      padding-bottom: 70px;
    }

    #scroll-top {
      display: none !important;
    }

    /* ✅ Loading indicator */
    .loading {
      opacity: 0.6;
      pointer-events: none;
    }

    /* ✅ Notification styles */
    .notification {
      position: fixed;
      bottom: 80px;
      left: 50%;
      transform: translateX(-50%);
      background: #ffb400;
      color: #fff;
      padding: 12px 28px;
      border-radius: 24px;
      font-weight: bold;
      box-shadow: 0 2px 12px rgba(0,0,0,0.10);
      z-index: 2000;
      animation: slideUp 0.3s ease;
    }

    .notification.success {
      background: #28a745;
    }

    .notification.error {
      background: #dc3545;
    }

    @keyframes slideUp {
      from {
        transform: translateX(-50%) translateY(20px);
        opacity: 0;
      }
      to {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
      }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // ✅ Load cart count saat halaman dimuat
      updateCartDisplay();
      loadExistingCartItems();
      
      document.querySelectorAll('.order-controls').forEach(function(ctrl) {
        const btnPlus = ctrl.querySelector('.btn-plus');
        const btnMinus = ctrl.querySelector('.btn-minus');
        const qtySpan = ctrl.querySelector('.order-qty');
        const menuId = ctrl.getAttribute('data-menu');
        let qty = 0;

        btnPlus.addEventListener('click', function() {
          qty++;
          updateQuantityDisplay(ctrl, qty);
          sendToCart(menuId, qty);
        });

        btnMinus.addEventListener('click', function() {
          qty--;
          if (qty < 0) qty = 0;
          updateQuantityDisplay(ctrl, qty);
          sendToCart(menuId, qty);
        });
      });

      // ✅ FUNGSI: Update tampilan quantity
      function updateQuantityDisplay(ctrl, qty) {
        const qtySpan = ctrl.querySelector('.order-qty');
        const btnMinus = ctrl.querySelector('.btn-minus');
        
        if (qty <= 0) {
          qtySpan.style.display = 'none';
          btnMinus.style.display = 'none';
        } else {
          qtySpan.style.display = '';
          btnMinus.style.display = '';
          qtySpan.textContent = qty;
        }
      }

      // ✅ FUNGSI: Kirim data ke server via AJAX
      function sendToCart(menuId, quantity) {
        // Show loading state
        const ctrl = document.querySelector(`[data-menu="${menuId}"]`);
        ctrl.classList.add('loading');

        fetch('/api/cart/add', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            menu_id: menuId,
            quantity: quantity
          })
        })
        .then(response => response.json())
        .then(data => {
          ctrl.classList.remove('loading');
          
          if (data.success) {
            updateCartDisplay();
            showNotification(data.message, 'success');
          } else {
            showNotification(data.message || 'Terjadi kesalahan', 'error');
          }
        })
        .catch(error => {
          ctrl.classList.remove('loading');
          console.error('Error:', error);
          showNotification('Terjadi kesalahan jaringan', 'error');
        });
      }

      // ✅ FUNGSI: Update tampilan checkout bar
      function updateCartDisplay() {
        fetch('/api/cart/count')
        .then(response => response.json())
        .then(data => {
          const checkoutLabel = document.getElementById('checkout-total-label');
          const checkoutBar = document.getElementById('checkout-bar');
          
          if (data.count > 0) {
            checkoutLabel.textContent = `${data.count} item - Rp${formatNumber(data.total)}`;
            checkoutBar.classList.add('show');
          } else {
            checkoutLabel.textContent = 'Check Out';
            checkoutBar.classList.remove('show');
          }
        })
        .catch(error => console.error('Error updating cart display:', error));
      }

      // ✅ FUNGSI: Load existing cart items dari server
      function loadExistingCartItems() {
        fetch('/api/cart/count')
        .then(response => response.json())
        .then(data => {
          // Update tampilan berdasarkan data dari server
          updateCartDisplay();
          
          // TODO: Jika perlu, update quantity display berdasarkan data server
          // Ini bisa ditambahkan jika ingin menampilkan quantity yang sudah ada
        })
        .catch(error => console.error('Error loading cart:', error));
      }

      // ✅ FUNGSI: Format angka dengan titik pemisah ribuan
      function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }

      // ✅ FUNGSI: Tampilkan notifikasi
      function showNotification(message, type = 'info') {
        // Hapus notifikasi yang sudah ada
        const existingNotif = document.getElementById('notif-message');
        if (existingNotif) {
          existingNotif.remove();
        }

        const notif = document.createElement('div');
        notif.id = 'notif-message';
        notif.className = `notification ${type}`;
        notif.textContent = message;
        document.body.appendChild(notif);
        
        setTimeout(() => {
          if (notif.parentNode) {
            notif.remove();
          }
        }, 3000);
      }

      // ✅ Checkout validation
      document.getElementById('checkout-btn').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Show loading state
        const btn = this;
        const originalText = btn.textContent;
        btn.textContent = 'Loading...';
        btn.disabled = true;
        
        fetch('/api/cart/count')
        .then(response => response.json())
        .then(data => {
          btn.textContent = originalText;
          btn.disabled = false;
          
          if (data.count < 1) {
            showNotification('Minimal pesan 1 menu', 'error');
          } else {
            window.location.href = '/checkout';
          }
        })
        .catch(error => {
          btn.textContent = originalText;
          btn.disabled = false;
          console.error('Error:', error);
          showNotification('Terjadi kesalahan', 'error');
        });
      });

      // ✅ Handle network errors gracefully
      window.addEventListener('online', function() {
        showNotification('Koneksi internet tersambung kembali', 'success');
      });

      window.addEventListener('offline', function() {
        showNotification('Koneksi internet terputus', 'error');
      });
    });
  </script>
</body>
</html>