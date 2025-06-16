<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRCarWash Admin - Manajemen Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #007bff !important; }
        .navbar-brand, .nav-link { color: #fff !important; }
        .sidebar { background-color: #343a40; color: #fff; height: 100vh; padding-top: 20px; }
        .sidebar .nav-link { color: #fff; padding: 10px 15px; display: block; }
        .sidebar .nav-link:hover { background-color: #007bff; }
        .content { padding: 20px; }
        .table-responsive { margin-top: 20px; }
        .btn-action { margin-right: 5px; }
        .logo-img { height: 40px; margin-right: 10px; }
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
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="admin_dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
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
                                <i class="fas fa-car-wash"></i> Manajemen Layanan
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <h2 class="mb-4">Manajemen Pelanggan</h2>
                <a href="add_customer.php" class="btn btn-primary mb-3"><i class="fas fa-user-plus"></i> Tambah Pelanggan Baru</a>

                <?php
                // Tampilkan pesan status dari operasi CRUD
                if (isset($_GET['status'])) {
                    if ($_GET['status'] == 'success_delete') {
                        echo '<div class="alert alert-success" role="alert">Pelanggan berhasil dihapus!</div>';
                    } elseif ($_GET['status'] == 'error_delete') {
                        echo '<div class="alert alert-danger" role="alert">Gagal menghapus pelanggan: ' . htmlspecialchars($_GET['message'] ?? 'Terjadi kesalahan.') . '</div>';
                    } elseif ($_GET['status'] == 'success_add') {
                        echo '<div class="alert alert-success" role="alert">Pelanggan baru berhasil ditambahkan!</div>';
                    } elseif ($_GET['status'] == 'success_edit') {
                        echo '<div class="alert alert-success" role="alert">Pelanggan berhasil diperbarui!</div>';
                    }
                }
                ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Pelanggan</th>
                                <th>Nama Depan</th>
                                <th>Nama Belakang</th>
                                <th>Nomor Telepon</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'db_config.php';

                            $sql = "SELECT customer_id, first_name, last_name, phone_number, email FROM customers ORDER BY first_name, last_name ASC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['customer_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                    echo "<td>";
                                    echo "<a href='edit_customer.php?id=" . htmlspecialchars($row['customer_id']) . "' class='btn btn-warning btn-sm btn-action'><i class='fas fa-edit'></i> Edit</a>";
                                    echo "<a href='delete_customer.php?id=" . htmlspecialchars($row['customer_id']) . "' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pelanggan ini? Ini juga akan mempengaruhi pemesanan terkait pelanggan ini.\")'><i class='fas fa-trash-alt'></i> Hapus</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>Tidak ada pelanggan.</td></tr>";
                            }

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