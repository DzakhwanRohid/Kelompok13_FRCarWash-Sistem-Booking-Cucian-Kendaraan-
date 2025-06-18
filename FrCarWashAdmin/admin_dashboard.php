<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRCarWash Admin - Dashboard Overview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarContent" aria-controls="sidebarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown user-profile-dropdown">
                    <a class="nav-link dropdown-toggle p-0" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                        <li><h6 class="dropdown-header">Admin FRCarWash</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-user-circle me-2"></i> Profil Saya
                        </a></li>
                        <li><a class="dropdown-item" href="FRCarWash/index.html">
                            <i class="fas fa-home me-2"></i> Kembali ke Situs
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-sign-out-alt me-2"></i> Log Out
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="sidebar-wrapper collapse d-lg-block" id="sidebarContent"> 
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <img src="logofr.png" alt="FRCarWash Logo" class="brand-logo">
                <span class="brand-text">FRCarWash</span>
            </div>
            
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="sidebar-heading">NAVIGATION</li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="admin_dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="sidebar-heading">Menu Manajemen</li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_bookings.php">
                            <i class="fas fa-calendar-alt"></i> Manajemen Pemesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_customers.php">
                            <i class="fas fa-users"></i> Manajemen Pelanggan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_services.php">
                            <i class="fas fa-users-cog"></i> Manajemen Layanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_logs.php">
                            <i class="fas fa-clipboard-list"></i> Lihat Log Aktivitas
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <main class="content">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>

        <?php
        // Tampilkan pesan status jika ada
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success_delete') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Data berhasil dihapus!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } elseif ($_GET['status'] == 'error_delete') {
                $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Terjadi kesalahan saat menghapus data.';
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Gagal menghapus data: ' . $message . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        }
        ?>

        <div class="row mt-4">
            <?php
            // Pastikan file koneksi database di-include
            if (!isset($conn)) {
                include 'db_config.php';
            }

            // --- Aggregasi COUNT ---
            // Total Pelanggan
            $sql_total_customers = "SELECT COUNT(*) AS total_customers FROM customers";
            $result_total_customers = $conn->query($sql_total_customers);
            $total_customers = 0;
            if ($result_total_customers && $result_total_customers->num_rows > 0) {
                $row = $result_total_customers->fetch_assoc();
                $total_customers = $row['total_customers'];
            }

            // Total Layanan
            $sql_total_services = "SELECT COUNT(*) AS total_services FROM services";
            $result_total_services = $conn->query($sql_total_services);
            $total_services = 0;
            if ($result_total_services && $result_total_services->num_rows > 0) {
                $row = $result_total_services->fetch_assoc();
                $total_services = $row['total_services'];
            }

            // Total Pemesanan
            $sql_total_bookings = "SELECT COUNT(*) AS total_bookings FROM bookings";
            $result_total_bookings = $conn->query($sql_total_bookings);
            $total_bookings = 0;
            if ($result_total_bookings && $result_total_bookings->num_rows > 0) {
                $row = $result_total_bookings->fetch_assoc();
                $total_bookings = $row['total_bookings'];
            }

            // --- Aggregasi SUM ---

            // Total Pendapatan Bulan Lalu (menggunakan Function database)
            $total_revenue_last_month = 0.00;
            $sql_revenue_last_month_function = "SELECT CalculateTotalRevenueLastMonth() AS total_revenue";
            $result_revenue_last_month_function = $conn->query($sql_revenue_last_month_function);

            if ($result_revenue_last_month_function && $result_revenue_last_month_function->num_rows > 0) {
                $row_revenue_last_month_function = $result_revenue_last_month_function->fetch_assoc();
                $total_revenue_last_month = $row_revenue_last_month_function['total_revenue'];
            }

            // Total Pendapatan Bulan Ini (menggunakan Function database baru)
            $total_revenue_current_month = 0.00;
            $sql_revenue_current_month_function = "SELECT CalculateTotalRevenueCurrentMonth() AS total_revenue";
            $result_revenue_current_month_function = $conn->query($sql_revenue_current_month_function);

            if ($result_revenue_current_month_function && $result_revenue_current_month_function->num_rows > 0) {
                $row_revenue_current_month_function = $result_revenue_current_month_function->fetch_assoc();
                $total_revenue_current_month = $row_revenue_current_month_function['total_revenue'];
            }

            // Total Pendapatan Keseluruhan (dari semua booking yang selesai)
            $sql_total_overall_revenue = "SELECT COALESCE(SUM(s.price), 0.00) AS overall_revenue 
                                          FROM bookings b JOIN services s ON b.service_id = s.service_id 
                                          WHERE b.status = 'Completed'"; // Asumsi status 'Completed'
            $result_total_overall_revenue = $conn->query($sql_total_overall_revenue);
            $total_overall_revenue = 0.00;
            if ($result_total_overall_revenue && $result_total_overall_revenue->num_rows > 0) {
                $row = $result_total_overall_revenue->fetch_assoc();
                $total_overall_revenue = $row['overall_revenue'];
            }

            // Total Harga Semua Layanan (total nilai inventori layanan, bukan pendapatan)
            $sql_total_service_value = "SELECT COALESCE(SUM(price), 0.00) AS total_value FROM services";
            $result_total_service_value = $conn->query($sql_total_service_value);
            $total_service_value = 0.00;
            if ($result_total_service_value && $result_total_service_value->num_rows > 0) {
                $row = $result_total_service_value->fetch_assoc();
                $total_service_value = $row['total_value'];
            }

            // --- Aggregasi AVG ---

            // Rata-rata Harga Layanan
            $sql_avg_service_price = "SELECT COALESCE(AVG(price), 0.00) AS avg_price FROM services";
            $result_avg_service_price = $conn->query($sql_avg_service_price);
            $avg_service_price = 0.00;
            if ($result_avg_service_price && $result_avg_service_price->num_rows > 0) {
                $avg_service_price_row = $result_avg_service_price->fetch_assoc();
                $avg_service_price = $avg_service_price_row['avg_price'];
            }

            // Rata-rata Pemesanan per Pelanggan (Estimasi)
            $sql_avg_bookings_per_customer = "SELECT COALESCE(COUNT(b.booking_id) / COUNT(DISTINCT c.customer_id), 0) AS avg_per_customer 
                                              FROM bookings b JOIN customers c ON b.customer_id = c.customer_id";
            $result_avg_bookings_per_customer = $conn->query($sql_avg_bookings_per_customer);
            $avg_bookings_per_customer = 0.00;
            if ($result_avg_bookings_per_customer && $result_avg_bookings_per_customer->num_rows > 0) {
                $row = $result_avg_bookings_per_customer->fetch_assoc();
                $avg_bookings_per_customer = $row['avg_per_customer'];
            }

            // --- Aggregasi MIN ---

            // Harga Layanan Terendah
            $sql_min_service_price = "SELECT COALESCE(MIN(price), 0.00) AS min_price FROM services";
            $result_min_service_price = $conn->query($sql_min_service_price);
            $min_service_price = 0.00;
            if ($result_min_service_price && $result_min_service_price->num_rows > 0) {
                $row = $result_min_service_price->fetch_assoc();
                $min_service_price = $row['min_price'];
            }

            // Harga Pemesanan Terendah (dari booking yang selesai)
            $sql_min_booking_price = "SELECT COALESCE(MIN(s.price), 0.00) AS min_booking_price 
                                      FROM bookings b JOIN services s ON b.service_id = s.service_id 
                                      WHERE b.status = 'Completed'";
            $result_min_booking_price = $conn->query($sql_min_booking_price);
            $min_booking_price = 0.00;
            if ($result_min_booking_price && $result_min_booking_price->num_rows > 0) {
                $row = $result_min_booking_price->fetch_assoc();
                $min_booking_price = $row['min_booking_price'];
            }

            // --- Aggregasi MAX ---

            // Harga Layanan Tertinggi
            $sql_max_service_price = "SELECT COALESCE(MAX(price), 0.00) AS max_price FROM services";
            $result_max_service_price = $conn->query($sql_max_service_price);
            $max_service_price = 0.00;
            if ($result_max_service_price && $result_max_service_price->num_rows > 0) {
                $row = $result_max_service_price->fetch_assoc();
                $max_service_price = $row['max_price'];
            }

            // Harga Pemesanan Tertinggi (dari booking yang selesai)
            $sql_max_booking_price = "SELECT COALESCE(MAX(s.price), 0.00) AS max_booking_price 
                                      FROM bookings b JOIN services s ON b.service_id = s.service_id 
                                      WHERE b.status = 'Completed'";
            $result_max_booking_price = $conn->query($sql_max_booking_price);
            $max_booking_price = 0.00;
            if ($result_max_booking_price && $result_max_booking_price->num_rows > 0) {
                $row = $result_max_booking_price->fetch_assoc();
                $max_booking_price = $row['max_booking_price'];
            }
            ?>
            <h3 class="mt-4 mb-3">Statistik Pendapatan</h3>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value">Rp <?php echo number_format($total_revenue_last_month, 2, ',', '.'); ?></h5>
                                    <p class="card-label">Pendapatan Bulan Lalu</p>
                                </div>
                                <i class="fas fa-coins card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-dark text-white">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value">Rp <?php echo number_format($total_revenue_current_month, 2, ',', '.'); ?></h5>
                                    <p class="card-label">Pendapatan Bulan Ini</p>
                                </div>
                                <i class="fas fa-chart-line card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-success text-white">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="mt-4 mb-3">Ringkasan Umum</h3>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value"><?php echo $total_customers; ?></h5>
                                    <p class="card-label">Total Pelanggan</p>
                                </div>
                                <i class="fas fa-users card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-primary text-white">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value"><?php echo $total_services; ?></h5>
                                    <p class="card-label">Total Layanan</p>
                                </div>
                                <i class="fas fa-users-cog card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-primary text-white">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value"><?php echo $total_bookings; ?></h5>
                                    <p class="card-label">Total Pemesanan</p>
                                </div>
                                <i class="fas fa-calendar-alt card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-primary text-white">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="mt-4 mb-3">Statistik Nilai</h3>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value">Rp <?php echo number_format($total_overall_revenue, 2, ',', '.'); ?></h5>
                                    <p class="card-label">Total Pendapatan Keseluruhan</p>
                                </div>
                                <i class="fas fa-hand-holding-usd card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-secondary text-white">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value">Rp <?php echo number_format($total_service_value, 2, ',', '.'); ?></h5>
                                    <p class="card-label">Total Nilai Semua Layanan</p>
                                </div>
                                <i class="fas fa-tags card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-secondary text-white">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="mt-4 mb-3">Rata-rata</h3>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value">Rp <?php echo number_format($avg_service_price, 2, ',', '.'); ?></h5>
                                    <p class="card-label">Rata-rata Harga Layanan</p>
                                </div>
                                <i class="fas fa-dollar-sign card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-info text-white">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value"><?php echo number_format($avg_bookings_per_customer, 2); ?></h5>
                                    <p class="card-label">Rata-rata Pemesanan</p>
                                </div>
                                <i class="fas fa-users-cog card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-info text-white">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="mt-4 mb-3">Nilai Min & Max</h3>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value">Rp <?php echo number_format($min_service_price, 2, ',', '.'); ?></h5>
                                    <p class="card-label">Harga Layanan Terendah</p>
                                </div>
                                <i class="fas fa-arrow-down card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-warning text-dark">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value">Rp <?php echo number_format($min_booking_price, 2, ',', '.'); ?></h5>
                                    <p class="card-label">Pemesanan Terendah (Selesai)</p>
                                </div>
                                <i class="fas fa-minus-circle card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-warning text-dark">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value">Rp <?php echo number_format($max_service_price, 2, ',', '.'); ?></h5>
                                    <p class="card-label">Harga Layanan Tertinggi</p>
                                </div>
                                <i class="fas fa-arrow-up card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-danger text-white">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="card-value">Rp <?php echo number_format($max_booking_price, 2, ',', '.'); ?></h5>
                                    <p class="card-label">Pemesanan Tertinggi (Selesai)</p>
                                </div>
                                <i class="fas fa-plus-circle card-main-icon"></i>
                            </div>
                        </div>
                        <div class="card-footer card-bottom-bar bg-danger text-white">
                            <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                        </div>
                    </div>
                </div>
            </div>

        </div> <?php
        // Koneksi ke database ditutup di akhir script untuk memastikan semua operasi selesai
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
        ?>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>