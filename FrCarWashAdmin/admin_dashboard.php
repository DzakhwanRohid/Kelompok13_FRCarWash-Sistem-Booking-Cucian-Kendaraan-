<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRCarWash Admin - Dashboard Overview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff !important;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .sidebar {
            background-color: #343a40; /* Dark sidebar */
            color: #fff;
            height: 100vh;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 10px 15px;
            display: block;
        }
        .sidebar .nav-link:hover {
            background-color: #007bff;
        }
        .content {
            padding: 20px;
        }
        .logo-img {
            height: 40px; /* Adjust as needed */
            margin-right: 10px;
        }
        /* Style untuk cards agregat */
        .card-header {
            font-weight: bold;
        }
        .card-title {
            font-size: 1.8em;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="logofr.png" alt="FRCarWash Logo" class="logo-img">
                FRCarWash Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="FRCarWash/index.html">Kembali ke Situs</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="admin_dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_bookings.php">
                                <i class="fas fa-calendar-check"></i> Manajemen Pemesanan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_services.php">
                                <i class="fas fa-cogs"></i> Manajemen Layanan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_customers.php">
                                <i class="fas fa-users"></i> Manajemen Pelanggan
                            </a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="view_logs.php">
                                <i class="fas fa-history"></i> Lihat Log
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <h2 class="mb-4">Dashboard Overview</h2>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Total Pendapatan (Bulan Terakhir)</div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php
                                    include 'db_config.php'; // Pastikan db_config.php sudah di-include

                                    // Menggunakan FUNCTION CalculateTotalRevenueLastMonth()
                                    $sql_revenue_last_month = "SELECT CalculateTotalRevenueLastMonth() AS total_revenue_last_month";
                                    $result_revenue_last_month = $conn->query($sql_revenue_last_month);
                                    if ($result_revenue_last_month && $result_revenue_last_month->num_rows > 0) {
                                        $revenue_row_last_month = $result_revenue_last_month->fetch_assoc();
                                        echo "Rp " . number_format($revenue_row_last_month['total_revenue_last_month'], 2, ',', '.');
                                    } else {
                                        echo "Rp 0.00";
                                    }
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-header">Jumlah Pemesanan Hari Ini</div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php
                                    $sql_today_bookings = "SELECT COUNT(*) AS total_today_bookings
                                                           FROM bookings
                                                           WHERE DATE(booking_date) = CURRENT_DATE()";
                                    $result_today_bookings = $conn->query($sql_today_bookings);
                                    if ($result_today_bookings && $result_today_bookings->num_rows > 0) {
                                        $today_bookings_row = $result_today_bookings->fetch_assoc();
                                        echo $today_bookings_row['total_today_bookings'] . " Pemesanan";
                                    } else {
                                        echo "0 Pemesanan";
                                    }
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-header">Layanan Paling Populer (Bulan Ini)</div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php
                                    $sql_popular_service = "SELECT s.service_name, COUNT(b.booking_id) AS service_count
                                                            FROM bookings b
                                                            JOIN services s ON b.service_id = s.service_id
                                                            WHERE MONTH(b.booking_date) = MONTH(CURRENT_DATE())
                                                            AND YEAR(b.booking_date) = YEAR(CURRENT_DATE())
                                                            GROUP BY s.service_name
                                                            ORDER BY service_count DESC
                                                            LIMIT 1";
                                    $result_popular_service = $conn->query($sql_popular_service);
                                    if ($result_popular_service && $result_popular_service->num_rows > 0) {
                                        $popular_service_row = $result_popular_service->fetch_assoc();
                                        echo htmlspecialchars($popular_service_row['service_name']) . " (" . $popular_service_row['service_count'] . "x)";
                                    } else {
                                        echo "N/A";
                                    }
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header">Total Pendapatan Keseluruhan</div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php
                                    $sql_total_revenue_all = "SELECT COALESCE(SUM(s.price), 0.00) AS total_revenue
                                                              FROM bookings b
                                                              JOIN services s ON b.service_id = s.service_id
                                                              WHERE b.status = 'Completed'";
                                    $result_total_revenue_all = $conn->query($sql_total_revenue_all);
                                    if ($result_total_revenue_all && $result_total_revenue_all->num_rows > 0) {
                                        $total_revenue_all_row = $result_total_revenue_all->fetch_assoc();
                                        echo "Rp " . number_format($total_revenue_all_row['total_revenue'], 2, ',', '.');
                                    } else {
                                        echo "Rp 0.00";
                                    }
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Jumlah Pelanggan Terdaftar</div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php
                                    $sql_total_customers = "SELECT COUNT(*) AS total_customers FROM customers";
                                    $result_total_customers = $conn->query($sql_total_customers);
                                    if ($result_total_customers && $result_total_customers->num_rows > 0) {
                                        $total_customers_row = $result_total_customers->fetch_assoc();
                                        echo $total_customers_row['total_customers'] . " Pelanggan";
                                    } else {
                                        echo "0 Pelanggan";
                                    }
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-secondary mb-3">
                            <div class="card-header">Rata-rata Harga Layanan</div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php
                                    $sql_avg_service_price = "SELECT COALESCE(AVG(price), 0.00) AS avg_price FROM services";
                                    $result_avg_service_price = $conn->query($sql_avg_service_price);
                                    if ($result_avg_service_price && $result_avg_service_price->num_rows > 0) {
                                        $avg_service_price_row = $result_avg_service_price->fetch_assoc();
                                        echo "Rp " . number_format($avg_service_price_row['avg_price'], 2, ',', '.');
                                    } else {
                                        echo "Rp 0.00";
                                    }
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Koneksi ke database ditutup di akhir script untuk memastikan semua operasi selesai
                if (isset($conn) && $conn instanceof mysqli) {
                    $conn->close();
                }
                ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>