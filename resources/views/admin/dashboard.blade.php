<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>AdminHub - Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --info: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            --dark: #2c3e50;
            --light: #f8f9fa;
            --white: #ffffff;
            --gray-100: #f1f3f4;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
            --shadow: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --shadow-xl: 0 20px 25px rgba(0,0,0,0.15);
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--gray-800);
            line-height: 1.6;
        }

        /* SIDEBAR */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: var(--primary);
            z-index: 2000;
            transition: var(--transition);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        #sidebar.hide {
            width: 80px;
        }

        #sidebar .brand {
            display: flex;
            align-items: center;
            height: 70px;
            padding: 0 24px;
            color: var(--white);
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
        }

        #sidebar .brand i {
            font-size: 2rem;
            margin-right: 12px;
            transition: var(--transition);
        }

        #sidebar.hide .brand .text {
            opacity: 0;
            pointer-events: none;
        }

        #sidebar .side-menu {
            list-style: none;
            padding: 20px 0;
        }

        #sidebar .side-menu.top {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
            padding-bottom: 20px;
        }

        #sidebar .side-menu li {
            margin: 4px 0;
        }

        #sidebar .side-menu li a,
        #sidebar .side-menu li button {
            display: flex;
            align-items: center;
            padding: 16px 24px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 0 25px 25px 0;
            margin-right: 20px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            background: none;
            border: none;
            width: 100%;
            font-family: inherit;
            font-size: 0.95rem;
            cursor: pointer;
        }

        #sidebar .side-menu li a::before,
        #sidebar .side-menu li button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: var(--transition);
            z-index: -1;
        }

        #sidebar .side-menu li a:hover::before,
        #sidebar .side-menu li button:hover::before,
        #sidebar .side-menu li.active a::before {
            width: 100%;
        }

        #sidebar .side-menu li a:hover,
        #sidebar .side-menu li button:hover,
        #sidebar .side-menu li.active a {
            color: var(--white);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            transform: translateX(8px);
        }

        #sidebar .side-menu li a i,
        #sidebar .side-menu li button i {
            font-size: 1.4rem;
            margin-right: 16px;
            min-width: 24px;
            transition: var(--transition);
        }

        #sidebar.hide .side-menu li a .text,
        #sidebar.hide .side-menu li button .text {
            opacity: 0;
            pointer-events: none;
        }

        /* CONTENT */
        #content {
            position: relative;
            width: calc(100% - 280px);
            left: 280px;
            transition: var(--transition);
            min-height: 100vh;
        }

        #sidebar.hide ~ #content {
            width: calc(100% - 80px);
            left: 80px;
        }

        /* NAVBAR */
        #content nav {
            height: 70px;
            background: var(--white);
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 24px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow);
            border-bottom: 1px solid var(--gray-200);
        }

        #content nav .bx-menu {
            font-size: 1.8rem;
            cursor: pointer;
            color: var(--gray-600);
            transition: var(--transition);
            padding: 8px;
            border-radius: var(--border-radius);
        }

        #content nav .bx-menu:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        #content nav .nav-link {
            font-size: 1rem;
            font-weight: 500;
            color: var(--gray-600);
            text-decoration: none;
            transition: var(--transition);
        }

        #content nav form {
            flex: 1;
            max-width: 400px;
        }

        #content nav .form-input {
            position: relative;
            display: flex;
            align-items: center;
        }

        #content nav .form-input input {
            width: 100%;
            padding: 12px 16px 12px 48px;
            border: 2px solid var(--gray-200);
            border-radius: 25px;
            font-size: 0.95rem;
            background: var(--gray-100);
            transition: var(--transition);
            outline: none;
        }

        #content nav .form-input input:focus {
            border-color: #667eea;
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        #content nav .form-input .search-btn {
            position: absolute;
            left: 16px;
            background: none;
            border: none;
            color: var(--gray-400);
            font-size: 1.2rem;
            cursor: pointer;
            transition: var(--transition);
        }

        /* MAIN */
        main {
            padding: 32px 24px;
            background: transparent;
            min-height: calc(100vh - 70px);
        }

        .head-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .head-title .left h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--gray-800);
            margin-bottom: 8px;
            background: var(--primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 8px;
        }

        .breadcrumb li a {
            color: var(--gray-500);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .breadcrumb li a:hover,
        .breadcrumb li a.active {
            color: #667eea;
        }

        .btn-download {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--primary);
            color: var(--white);
            text-decoration: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* BOX INFO */
        .box-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
            list-style: none;
        }

        .box-info li {
            background: var(--white);
            padding: 32px 24px;
            border-radius: var(--border-radius-lg);
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .box-info li::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary);
        }

        .box-info li:nth-child(2)::before {
            background: var(--success);
        }

        .box-info li:nth-child(3)::before {
            background: var(--warning);
        }

        .box-info li:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .box-info li i {
            font-size: 3rem;
            padding: 20px;
            border-radius: 50%;
            color: var(--white);
            background: var(--primary);
        }

        .box-info li:nth-child(2) i {
            background: var(--success);
        }

        .box-info li:nth-child(3) i {
            background: var(--warning);
        }

        .box-info li .text h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 4px;
        }

        .box-info li .text p {
            color: var(--gray-600);
            font-weight: 500;
            font-size: 1rem;
        }

        /* TABLE DATA */
        .table-data {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 24px;
        }

        .table-data .order,
        .table-data .todo {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .table-data .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px;
            border-bottom: 1px solid var(--gray-200);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .table-data .head h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--gray-800);
        }

        .table-data .head i {
            font-size: 1.2rem;
            color: var(--gray-500);
            cursor: pointer;
            padding: 8px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            margin-left: 8px;
        }

        .table-data .head i:hover {
            background: var(--gray-200);
            color: #667eea;
        }

        /* TABLE */
        .table-data table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-data table th {
            padding: 20px 24px;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            background: var(--gray-100);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-data table td {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
            vertical-align: middle;
        }

        .table-data table tbody tr {
            transition: var(--transition);
        }

        .table-data table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
        }

        .table-data table td img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 12px;
            border: 2px solid var(--gray-200);
        }

        .table-data table td p {
            font-weight: 500;
            color: var(--gray-700);
        }

        .status {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status.completed {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .status.pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
        }

        .status.process {
            background: linear-gradient(135deg, #cce5ff 0%, #b3d9ff 100%);
            color: #004085;
        }

        /* TODO */
        .todo-list {
            list-style: none;
            padding: 0;
        }

        .todo-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .todo-list li:hover {
            background: var(--gray-100);
        }

        .todo-list li:last-child {
            border-bottom: none;
        }

        .todo-list li.completed p {
            text-decoration: line-through;
            color: var(--gray-500);
        }

        .todo-list li.not-completed p {
            color: var(--gray-800);
            font-weight: 500;
        }

        .todo-list li i {
            color: var(--gray-400);
            cursor: pointer;
            padding: 8px;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .todo-list li i:hover {
            background: var(--gray-200);
            color: var(--gray-600);
        }

        /* RESPONSIVE */
        @media screen and (max-width: 1200px) {
            .table-data {
                grid-template-columns: 1fr;
            }
        }

        @media screen and (max-width: 768px) {
            #sidebar {
                width: 80px;
            }

            #content {
                width: calc(100% - 80px);
                left: 80px;
            }

            #content nav {
                padding: 0 16px;
                gap: 16px;
            }

            #content nav form {
                max-width: 200px;
            }

            main {
                padding: 20px 16px;
            }

            .head-title {
                flex-direction: column;
                align-items: flex-start;
            }

            .head-title .left h1 {
                font-size: 2rem;
            }

            .box-info {
                grid-template-columns: 1fr;
            }
        }

        @media screen and (max-width: 576px) {
            #sidebar {
                width: 0;
                overflow: hidden;
            }

            #content {
                width: 100%;
                left: 0;
            }

            #content nav form {
                display: none;
            }

            .table-data table {
                font-size: 0.85rem;
            }

            .table-data table td,
            .table-data table th {
                padding: 12px 16px;
            }
        }

        /* ANIMATIONS */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .box-info li {
            animation: slideInUp 0.6s ease forwards;
        }

        .box-info li:nth-child(2) {
            animation-delay: 0.1s;
        }

        .box-info li:nth-child(3) {
            animation-delay: 0.2s;
        }

        .table-data .order,
        .table-data .todo {
            animation: slideInUp 0.6s ease forwards;
        }

        .table-data .todo {
            animation-delay: 0.1s;
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">AdminHub</span>
        </a>
        <ul class="side-menu top">
            <li class="active">
                <a href="#">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.menus.index') }}">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Menu Makanan & Minuman</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kasir.index') }}">
                    <i class='bx bxs-user-plus'></i>
                    <span class="text">Register Kasir</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.shifts.index') }}">
                    <i class='bx bxs-doughnut-chart'></i>
                    <span class="text">Shift Kasir</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.transactions.index') }}">
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">Transaksi Pelanggan</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu' id="menu-toggle"></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="#">
                <div class="form-input">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                    <input type="search" placeholder="Search anything...">
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">8</span>
            </a>
            <a href="#" class="profile">
                <img src="{{ asset('img/people.png') }}" alt="Profile">
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
                <a href="#" class="btn-download">
                    <i class='bx bxs-cloud-download'></i>
                    <span class="text">Download PDF</span>
                </a>
            </div>

            <ul class="box-info">
                <li>
                    <i class='bx bxs-calendar-check'></i>
                    <span class="text">
                        <h3>1,020</h3>
                        <p>New Orders</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3>2,834</h3>
                        <p>Visitors</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-dollar-circle'></i>
                    <span class="text">
                        <h3>$2,543</h3>
                        <p>Total Sales</p>
                    </span>
                </li>
            </ul>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Kasir yang Sedang Bertugas</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Kasir</th>
                                <th>Waktu Mulai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeShifts as $shift)
                            <tr>
                                <td>
                                    <p>{{ $shift->kasir_name }}</p>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</td>
                                <td><span class="status process">Aktif</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="text-align:center;">Tidak ada kasir yang sedang bertugas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Quick Tasks</h3>
                        <div>
                            <i class='bx bx-plus'></i>
                            <i class='bx bx-filter'></i>
                        </div>
                    </div>
                    <ul class="todo-list">
                        <li class="completed">
                            <p>Review daily sales report</p>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                        <li class="completed">
                            <p>Update menu prices</p>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                        <li class="not-completed">
                            <p>Check inventory levels</p>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                        <li class="completed">
                            <p>Process pending orders</p>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                        <li class="not-completed">
                            <p>Schedule staff meetings</p>
                            <i class='bx bx-dots-vertical-rounded'></i>
                        </li>
                    </ul>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
    
    <script>
        // Sidebar Toggle
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('hide');
        });

        // Dark Mode Toggle (if needed)
        const switchMode = document.getElementById('switch-mode');
        
        switchMode.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
            }
        });

        // Add loading animation to buttons
        document.querySelectorAll('button, .btn-download').forEach(btn => {
            btn.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });

        // Animate numbers on page load
        function animateNumbers() {
            const numbers = document.querySelectorAll('.box-info h3');
            numbers.forEach(num => {
                const finalNumber = parseInt(num.textContent.replace(/[^0-9]/g, ''));
                let currentNumber = 0;
                const increment = finalNumber / 50;
                const timer = setInterval(() => {
                    currentNumber += increment;
                    if (currentNumber >= finalNumber) {
                        currentNumber = finalNumber;
                        clearInterval(timer);
                    }
                    if (num.textContent.includes('$')) {
                        num.textContent = '$' + Math.floor(currentNumber).toLocaleString();
                    } else {
                        num.textContent = Math.floor(currentNumber).toLocaleString();
                    }
                }, 20);
            });
        }

        // Run animation when page loads
        window.addEventListener('load', animateNumbers);
    </script>
</body>
</html>