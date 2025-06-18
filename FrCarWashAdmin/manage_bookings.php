<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRCarWash Admin - Manajemen Pemesanan</title>
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
                        <a class="nav-link active" aria-current="page" href="manage_bookings.php">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Manajemen Pemesanan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Pemesanan</li>
                </ol>
            </nav>
        </div>

        <?php
        // Tampilkan pesan status jika ada
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success_add') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Pemesanan berhasil ditambahkan!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } elseif ($_GET['status'] == 'success_edit') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Pemesanan berhasil diperbarui!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } elseif ($_GET['status'] == 'success_delete') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Pemesanan berhasil dihapus!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            } elseif ($_GET['status'] == 'error_delete') {
                $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Terjadi kesalahan saat menghapus pemesanan.';
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Gagal menghapus pemesanan: ' . $message . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        }
        ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pemesanan</h6>
                <a href="add_booking.php" class="btn btn-primary btn-sm">Tambah Pemesanan</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Nama Pelanggan</th>
                                <th>Email Pelanggan</th>
                                <th>Telepon Pelanggan</th>
                                <th>Tanggal Booking</th>
                                <th>Jenis Layanan</th>
                                <th>Harga Layanan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'db_config.php'; // Pastikan db_config.php sudah di-include

                            // Menggunakan JOIN untuk mengambil detail dari tabel customers dan services
                            $sql = "SELECT b.booking_id,
                                           c.first_name, c.last_name, c.email AS customer_email, c.phone_number AS customer_phone,
                                           b.booking_date,
                                           s.service_name, s.price AS service_price,
                                           b.status AS booking_status
                                    FROM bookings b
                                    JOIN customers c ON b.customer_id = c.customer_id
                                    JOIN services s ON b.service_id = s.service_id
                                    ORDER BY b.booking_date DESC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['booking_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['customer_email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['customer_phone']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['service_name']) . "</td>";
                                    echo "<td>Rp " . number_format($row['service_price'], 2, ',', '.') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['booking_status']) . "</td>";
                                    echo "<td>";
                                    echo "<a href='edit_booking.php?id=" . htmlspecialchars($row['booking_id']) . "' class='btn btn-warning btn-sm btn-action'><i class='fas fa-edit'></i> Edit</a>";
                                    echo "<a href='delete_booking.php?id=" . htmlspecialchars($row['booking_id']) . "' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pemesanan ini?\")'><i class='fas fa-trash-alt'></i> Hapus</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>Tidak ada pemesanan.</td></tr>";
                            }

                            // Koneksi ke database ditutup di akhir script
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