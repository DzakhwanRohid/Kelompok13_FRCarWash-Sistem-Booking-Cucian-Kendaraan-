<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRCarWash Admin - Manajemen Layanan</title>
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
                        <a class="nav-link active" aria-current="page" href="manage_services.php">
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <h2 class="mb-4">Tambah Layanan Baru</h2>

                <?php
                include 'db_config.php';

                $service_name_error = '';
                $price_error = '';
                $add_success = false;

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $service_name = $_POST['service_name'] ?? '';
                    $description = $_POST['description'] ?? '';
                    $price = $_POST['price'] ?? '';

                    // Validasi
                    if (empty($service_name)) {
                        $service_name_error = "Nama layanan wajib diisi.";
                    }
                    if (!is_numeric($price) || $price < 0) {
                        $price_error = "Harga harus berupa angka positif.";
                    }

                    if (empty($service_name_error) && empty($price_error)) {
                        $stmt = $conn->prepare("INSERT INTO services (service_name, description, price) VALUES (?, ?, ?)");
                        if ($stmt) {
                            $stmt->bind_param("ssd", $service_name, $description, $price); // s: string, d: double/decimal

                            if ($stmt->execute()) {
                                $add_success = true;
                                // Clear form fields after successful submission
                                $_POST = []; // Reset POST data
                            } else {
                                echo '<div class="alert alert-danger" role="alert">Error: ' . htmlspecialchars($stmt->error) . '</div>';
                            }
                            $stmt->close();
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Error preparing statement: ' . htmlspecialchars($conn->error) . '</div>';
                        }
                    }
                }
                ?>

                <?php if ($add_success): ?>
                    <div class="alert alert-success" role="alert">
                        Layanan baru berhasil ditambahkan! <a href="manage_services.php" class="alert-link">Lihat daftar layanan</a>.
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="service_name" class="form-label">Nama Layanan:</label>
                        <input type="text" class="form-control <?php echo (!empty($service_name_error)) ? 'is-invalid' : ''; ?>" id="service_name" name="service_name" required value="<?php echo isset($_POST['service_name']) ? htmlspecialchars($_POST['service_name']) : ''; ?>">
                        <div class="invalid-feedback">
                            <?php echo $service_name_error; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi:</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Harga (Rp):</label>
                        <input type="number" step="0.01" class="form-control <?php echo (!empty($price_error)) ? 'is-invalid' : ''; ?>" id="price" name="price" required value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                        <div class="invalid-feedback">
                            <?php echo $price_error; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Tambah Layanan</button>
                    <a href="manage_services.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>