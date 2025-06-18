<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRCarWash Admin - Manajemen Pelanggan</title>
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
                        <a class="nav-link active" aria-current="page" href="manage_customers.php">
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <h2 class="mb-4">Tambah Pelanggan Baru</h2>

                <?php
                include 'db_config.php';

                $first_name_error = '';
                $last_name_error = ''; // Optional, depending on your validation
                $phone_number_error = '';
                $email_error = '';
                $add_success = false;

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $first_name = $_POST['first_name'] ?? '';
                    $last_name = $_POST['last_name'] ?? '';
                    $phone_number = $_POST['phone_number'] ?? '';
                    $email = $_POST['email'] ?? '';

                    // Validasi
                    if (empty($first_name)) {
                        $first_name_error = "Nama depan wajib diisi.";
                    }
                    if (empty($phone_number)) {
                        $phone_number_error = "Nomor telepon wajib diisi.";
                    }
                    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $email_error = "Format email tidak valid.";
                    }

                    if (empty($first_name_error) && empty($phone_number_error) && empty($email_error)) {
                        $stmt = $conn->prepare("INSERT INTO customers (first_name, last_name, phone_number, email) VALUES (?, ?, ?, ?)");
                        if ($stmt) {
                            $stmt->bind_param("ssss", $first_name, $last_name, $phone_number, $email);

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
                        Pelanggan baru berhasil ditambahkan! <a href="manage_customers.php" class="alert-link">Lihat daftar pelanggan</a>.
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Nama Depan:</label>
                        <input type="text" class="form-control <?php echo (!empty($first_name_error)) ? 'is-invalid' : ''; ?>" id="first_name" name="first_name" required value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
                        <div class="invalid-feedback">
                            <?php echo $first_name_error; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Nama Belakang (Opsional):</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon:</label>
                        <input type="text" class="form-control <?php echo (!empty($phone_number_error)) ? 'is-invalid' : ''; ?>" id="phone_number" name="phone_number" required value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>">
                        <div class="invalid-feedback">
                            <?php echo $phone_number_error; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email (Opsional):</label>
                        <input type="email" class="form-control <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <div class="invalid-feedback">
                            <?php echo $email_error; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Tambah Pelanggan</button>
                    <a href="manage_customers.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
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