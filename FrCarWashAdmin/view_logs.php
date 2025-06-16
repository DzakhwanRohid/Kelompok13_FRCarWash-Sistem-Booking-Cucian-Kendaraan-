<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRCarWash Admin - Lihat Log</title>
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
        .table-responsive {
            margin-top: 20px;
        }
        .logo-img {
            height: 40px; /* Adjust as needed */
            margin-right: 10px;
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
                            <a class="nav-link" href="admin_dashboard.php">
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
                            <a class="nav-link active" aria-current="page" href="view_logs.php">
                                <i class="fas fa-history"></i> Lihat Log
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <h2 class="mb-4">Log Aktivitas Sistem</h2>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Log</th>
                                <th>Waktu</th>
                                <th>Tipe</th>
                                <th>Pesan Log</th>
                                <th>Tabel</th>
                                <th>ID Record</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'db_config.php'; // Pastikan file koneksi database Anda ada

                            // Query untuk mengambil data dari tabel activity_logs
                            // Data ini diisi secara otomatis oleh TRIGGER
                            $sql = "SELECT log_id, log_timestamp, log_type, log_message, table_name, record_id FROM activity_logs ORDER BY log_timestamp DESC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['log_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['log_timestamp']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['log_type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['log_message']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['table_name']) . "</td>";
                                    echo "<td>" . ($row['record_id'] ? htmlspecialchars($row['record_id']) : 'N/A') . "</td>"; // Tampilkan N/A jika record_id null
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>Tidak ada log aktivitas.</td></tr>";
                            }

                            // Koneksi ke database ditutup
                            if (isset($conn) && $conn instanceof mysqli) {
                                $conn->close();
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>