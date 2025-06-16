<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRCarWash Admin - Edit Pemesanan</title>
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
                            <a class="nav-link active" aria-current="page" href="admin_dashboard.php">
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
                <h2 class="mb-4">Edit Pemesanan</h2>

                <?php
                include 'db_config.php';

                $booking_id = $_GET['id'] ?? 0;
                $booking_data = null;
                $customer_error = '';
                $service_error = '';
                $status_error = '';
                $edit_success = false;

                // Mengambil daftar pelanggan untuk dropdown
                $customers_query = "SELECT customer_id, first_name, last_name FROM customers ORDER BY first_name, last_name ASC";
                $customers_result = $conn->query($customers_query);
                $customers = [];
                if ($customers_result->num_rows > 0) {
                    while ($row = $customers_result->fetch_assoc()) {
                        $customers[] = $row;
                    }
                }

                // Mengambil daftar layanan untuk dropdown
                $services_query = "SELECT service_id, service_name, price FROM services ORDER BY service_name ASC";
                $services_result = $conn->query($services_query);
                $services = [];
                if ($services_result->num_rows > 0) {
                    while ($row = $services_result->fetch_assoc()) {
                        $services[] = $row;
                    }
                }

                // Status yang valid untuk pemesanan
                $valid_statuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];

                // Ambil data pemesanan yang akan diedit
                if ($booking_id > 0) {
                    $stmt = $conn->prepare("SELECT booking_id, customer_id, service_id, booking_date, status FROM bookings WHERE booking_id = ?");
                    $stmt->bind_param("i", $booking_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $booking_data = $result->fetch_assoc();
                        // Pisahkan tanggal dan waktu
                        $booking_data['booking_date_only'] = date('Y-m-d', strtotime($booking_data['booking_date']));
                        $booking_data['booking_time_only'] = date('H:i', strtotime($booking_data['booking_date']));
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Pemesanan tidak ditemukan.</div>';
                        $booking_id = 0; // Set to 0 to prevent form display
                    }
                    $stmt->close();
                }

                // Proses form submission (UPDATE)
                if ($_SERVER["REQUEST_METHOD"] == "POST" && $booking_id > 0) {
                    $customer_id = $_POST['customer_id'];
                    $service_id = $_POST['service_id'];
                    $booking_date_time = $_POST['booking_date'] . ' ' . $_POST['booking_time'];
                    $status = $_POST['status'];

                    // Validasi
                    if (empty($customer_id)) {
                        $customer_error = "Pilih pelanggan adalah wajib.";
                    }
                    if (empty($service_id)) {
                        $service_error = "Pilih layanan adalah wajib.";
                    }
                    if (!in_array($status, $valid_statuses)) {
                        $status_error = "Status tidak valid.";
                    }

                    if (empty($customer_error) && empty($service_error) && empty($status_error)) {
                        $stmt = $conn->prepare("UPDATE bookings SET customer_id = ?, service_id = ?, booking_date = ?, status = ? WHERE booking_id = ?");
                        $stmt->bind_param("iissi", $customer_id, $service_id, $booking_date_time, $status, $booking_id);

                        if ($stmt->execute()) {
                            $edit_success = true;
                            // Update data di $booking_data agar form menampilkan data terbaru
                            $booking_data['customer_id'] = $customer_id;
                            $booking_data['service_id'] = $service_id;
                            $booking_data['booking_date'] = $booking_date_time;
                            $booking_data['booking_date_only'] = $_POST['booking_date'];
                            $booking_data['booking_time_only'] = $_POST['booking_time'];
                            $booking_data['status'] = $status;
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Error: ' . htmlspecialchars($stmt->error) . '</div>';
                        }
                        $stmt->close();
                    }
                }
                ?>

                <?php if ($edit_success): ?>
                    <div class="alert alert-success" role="alert">
                        Pemesanan berhasil diperbarui! <a href="admin_dashboard.php" class="alert-link">Kembali ke Dashboard</a>.
                    </div>
                <?php endif; ?>

                <?php if ($booking_id > 0 && $booking_data): ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . htmlspecialchars($booking_id); ?>">
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Pilih Pelanggan:</label>
                        <select class="form-select <?php echo (!empty($customer_error)) ? 'is-invalid' : ''; ?>" id="customer_id" name="customer_id" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?php echo htmlspecialchars($customer['customer_id']); ?>"
                                    <?php echo (isset($_POST['customer_id']) ? $_POST['customer_id'] : $booking_data['customer_id']) == $customer['customer_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo $customer_error; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="service_id" class="form-label">Pilih Layanan:</label>
                        <select class="form-select <?php echo (!empty($service_error)) ? 'is-invalid' : ''; ?>" id="service_id" name="service_id" required>
                            <option value="">-- Pilih Layanan --</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?php echo htmlspecialchars($service['service_id']); ?>"
                                    <?php echo (isset($_POST['service_id']) ? $_POST['service_id'] : $booking_data['service_id']) == $service['service_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($service['service_name']) . " (Rp " . number_format($service['price'], 0, ',', '.') . ")"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo $service_error; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="booking_date" class="form-label">Tanggal Booking:</label>
                            <input type="date" class="form-control" id="booking_date" name="booking_date" required
                                value="<?php echo isset($_POST['booking_date']) ? htmlspecialchars($_POST['booking_date']) : htmlspecialchars($booking_data['booking_date_only']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="booking_time" class="form-label">Waktu Booking:</label>
                            <input type="time" class="form-control" id="booking_time" name="booking_time" required
                                value="<?php echo isset($_POST['booking_time']) ? htmlspecialchars($_POST['booking_time']) : htmlspecialchars($booking_data['booking_time_only']); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status Pemesanan:</label>
                        <select class="form-select <?php echo (!empty($status_error)) ? 'is-invalid' : ''; ?>" id="status" name="status" required>
                            <?php foreach ($valid_statuses as $s): ?>
                                <option value="<?php echo htmlspecialchars($s); ?>"
                                    <?php echo (isset($_POST['status']) ? $_POST['status'] : $booking_data['status']) == $s ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($s); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo $status_error; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="admin_dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Koneksi ke database ditutup di akhir script
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>