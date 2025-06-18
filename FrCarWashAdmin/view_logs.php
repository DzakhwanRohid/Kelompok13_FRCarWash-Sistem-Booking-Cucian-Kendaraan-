<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRCarWash Admin - Lihat Log</title>
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
                        <a class="nav-link" href="admin_dashboard.php">
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
                        <a class="nav-link active" aria-current="page" href="view_logs.php">
                            <i class="fas fa-clipboard-list"></i> Lihat Log Aktivitas
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <main class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Log Aktivitas</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Log Aktivitas</li>
                </ol>
            </nav>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Log</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID Log</th>
                                <th>Waktu</th>
                                <th>Tipe</th>
                                <th>Pesan</th>
                                <th>Tabel</th>
                                <th>ID Record</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Pastikan file koneksi database di-include
                            if (!isset($conn)) {
                                include 'db_config.php';
                            }

                            // Query untuk mengambil semua log aktivitas
                            $sql = "SELECT log_id, log_timestamp, log_type, log_message, table_name, record_id FROM activity_logs ORDER BY log_timestamp DESC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
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
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>