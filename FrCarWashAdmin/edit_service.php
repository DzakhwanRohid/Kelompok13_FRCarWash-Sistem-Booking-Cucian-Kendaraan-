<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRCarWash Admin - Edit Layanan</title>
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
                            <a class="nav-link active" aria-current="page" href="manage_services.php">
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
                <h2 class="mb-4">Edit Layanan</h2>

                <?php
                include 'db_config.php';

                $service_id = $_GET['id'] ?? 0;
                $service_data = null;
                $service_name_error = '';
                $price_error = '';
                $edit_success = false;

                // Ambil data layanan yang akan diedit
                if ($service_id > 0) {
                    $stmt = $conn->prepare("SELECT service_id, service_name, description, price FROM services WHERE service_id = ?");
                    if ($stmt) {
                        $stmt->bind_param("i", $service_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $service_data = $result->fetch_assoc();
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Layanan tidak ditemukan.</div>';
                            $service_id = 0; // Set to 0 to prevent form display
                        }
                        $stmt->close();
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Error preparing statement: ' . htmlspecialchars($conn->error) . '</div>';
                    }
                }

                // Proses form submission (UPDATE)
                if ($_SERVER["REQUEST_METHOD"] == "POST" && $service_id > 0) {
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
                        $stmt = $conn->prepare("UPDATE services SET service_name = ?, description = ?, price = ? WHERE service_id = ?");
                        if ($stmt) {
                            $stmt->bind_param("ssdi", $service_name, $description, $price, $service_id); // s: string, d: double, i: integer

                            if ($stmt->execute()) {
                                $edit_success = true;
                                // Update data di $service_data agar form menampilkan data terbaru
                                $service_data['service_name'] = $service_name;
                                $service_data['description'] = $description;
                                $service_data['price'] = $price;
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

                <?php if ($edit_success): ?>
                    <div class="alert alert-success" role="alert">
                        Layanan berhasil diperbarui! <a href="manage_services.php" class="alert-link">Kembali ke Manajemen Layanan</a>.
                    </div>
                <?php endif; ?>

                <?php if ($service_id > 0 && $service_data): ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . htmlspecialchars($service_id); ?>">
                    <div class="mb-3">
                        <label for="service_name" class="form-label">Nama Layanan:</label>
                        <input type="text" class="form-control <?php echo (!empty($service_name_error)) ? 'is-invalid' : ''; ?>" id="service_name" name="service_name" required value="<?php echo isset($_POST['service_name']) ? htmlspecialchars($_POST['service_name']) : htmlspecialchars($service_data['service_name']); ?>">
                        <div class="invalid-feedback">
                            <?php echo $service_name_error; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi:</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars($service_data['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Harga (Rp):</label>
                        <input type="number" step="0.01" class="form-control <?php echo (!empty($price_error)) ? 'is-invalid' : ''; ?>" id="price" name="price" required value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : htmlspecialchars($service_data['price']); ?>">
                        <div class="invalid-feedback">
                            <?php echo $price_error; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="manage_services.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>
                <?php endif; ?>
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