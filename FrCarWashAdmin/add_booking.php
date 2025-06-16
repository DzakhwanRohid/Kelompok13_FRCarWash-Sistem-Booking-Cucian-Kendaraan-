<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRCarWash Admin - Tambah Pemesanan Baru</title>
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
        #new_customer_fields {
            display: none; /* Sembunyikan secara default */
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
                <h2 class="mb-4">Tambah Pemesanan Baru</h2>

                <?php
                include 'db_config.php';

                $customer_error = '';
                $service_error = '';
                $new_customer_name_error = '';
                $new_customer_email_error = '';
                $new_customer_phone_error = '';
                $booking_success = false;

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

                // Proses form submission
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $customer_type = $_POST['customer_type'] ?? ''; // 'existing' atau 'new'
                    $customer_id = null; // Akan diisi jika memilih existing customer atau setelah insert new customer
                    $service_id = $_POST['service_id'] ?? '';
                    $booking_date_time = $_POST['booking_date'] . ' ' . $_POST['booking_time'];

                    if (empty($service_id)) {
                        $service_error = "Pilih layanan adalah wajib.";
                    }

                    if ($customer_type == 'existing') {
                        $customer_id = $_POST['customer_id'] ?? '';
                        if (empty($customer_id)) {
                            $customer_error = "Pilih pelanggan terdaftar adalah wajib.";
                        }
                    } elseif ($customer_type == 'new') {
                        $new_first_name = $_POST['new_first_name'] ?? '';
                        $new_last_name = $_POST['new_last_name'] ?? '';
                        $new_email = $_POST['new_email'] ?? '';
                        $new_phone = $_POST['new_phone'] ?? '';

                        // Validasi untuk pelanggan baru
                        if (empty($new_first_name)) {
                            $new_customer_name_error = "Nama depan pelanggan baru wajib diisi.";
                        }
                        // Anda bisa tambahkan validasi untuk email dan telepon jika diperlukan
                        // if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) { $new_customer_email_error = "Format email tidak valid."; }
                        // if (empty($new_phone)) { $new_customer_phone_error = "Nomor telepon pelanggan baru wajib diisi."; }


                        if (empty($new_customer_name_error) && empty($new_customer_email_error) && empty($new_customer_phone_error)) {
                            // Insert pelanggan baru
                            $stmt_insert_customer = $conn->prepare("INSERT INTO customers (first_name, last_name, phone_number, email) VALUES (?, ?, ?, ?)");
                            if ($stmt_insert_customer) {
                                $stmt_insert_customer->bind_param("ssss", $new_first_name, $new_last_name, $new_phone, $new_email);
                                if ($stmt_insert_customer->execute()) {
                                    $customer_id = $stmt_insert_customer->insert_id; // Dapatkan ID pelanggan yang baru diinsert
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">Error menambah pelanggan baru: ' . htmlspecialchars($stmt_insert_customer->error) . '</div>';
                                }
                                $stmt_insert_customer->close();
                            } else {
                                echo '<div class="alert alert-danger" role="alert">Error preparing customer insert statement: ' . htmlspecialchars($conn->error) . '</div>';
                            }
                        }
                    } else {
                        $customer_error = "Pilih jenis pelanggan (terdaftar/baru) adalah wajib.";
                    }

                    // Lanjutkan jika sudah ada customer_id (baik dari existing atau newly created) dan tidak ada error lainnya
                    if (!empty($customer_id) && empty($service_error) && empty($customer_error)) {
                        // Memanggil stored procedure sp_add_new_booking
                        $stmt = $conn->prepare("CALL sp_add_new_booking(?, ?, ?)");
                        if ($stmt) {
                            $stmt->bind_param("iis", $customer_id, $service_id, $booking_date_time);

                            if ($stmt->execute()) {
                                $booking_success = true;
                                // Opsional: Clear form fields after successful submission
                                $_POST = []; // Clear all POST data to reset form
                            } else {
                                echo '<div class="alert alert-danger" role="alert">Error: ' . htmlspecialchars($stmt->error) . '</div>';
                            }
                            $stmt->close();
                        } else {
                             echo '<div class="alert alert-danger" role="alert">Error preparing booking statement: ' . htmlspecialchars($conn->error) . '</div>';
                        }
                    } else {
                        // Jika ada error, tampilkan di bawah formulir.
                        // Error sudah diatur di masing-masing variabel: $customer_error, $service_error, $new_customer_name_error, dll.
                    }
                }
                ?>

                <?php if ($booking_success): ?>
                    <div class="alert alert-success" role="alert">
                        Pemesanan baru berhasil ditambahkan! <a href="admin_dashboard.php" class="alert-link">Lihat daftar pemesanan</a>.
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label class="form-label">Jenis Pelanggan:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="customer_type" id="existingCustomer" value="existing" checked onclick="toggleCustomerFields()">
                            <label class="form-check-label" for="existingCustomer">Pelanggan Terdaftar</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="customer_type" id="newCustomer" value="new" onclick="toggleCustomerFields()">
                            <label class="form-check-label" for="newCustomer">Pelanggan Baru</label>
                        </div>
                        <?php if (!empty($customer_error)): ?>
                            <div class="invalid-feedback d-block"><?php echo $customer_error; ?></div>
                        <?php endif; ?>
                    </div>

                    <div id="existing_customer_fields" class="mb-3">
                        <label for="customer_id" class="form-label">Pilih Pelanggan Terdaftar:</label>
                        <select class="form-select <?php echo (!empty($customer_error)) ? 'is-invalid' : ''; ?>" id="customer_id" name="customer_id">
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?php echo htmlspecialchars($customer['customer_id']); ?>"
                                    <?php echo (isset($_POST['customer_id']) && $_POST['customer_id'] == $customer['customer_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo $customer_error; ?>
                        </div>
                        <small class="form-text text-muted">Anda juga dapat menambahkan pelanggan baru di <a href="manage_customers.php">Manajemen Pelanggan</a>.</small>
                    </div>

                    <div id="new_customer_fields" class="mb-3">
                        <h5>Detail Pelanggan Baru</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="new_first_name" class="form-label">Nama Depan:</label>
                                <input type="text" class="form-control <?php echo (!empty($new_customer_name_error)) ? 'is-invalid' : ''; ?>" id="new_first_name" name="new_first_name" value="<?php echo isset($_POST['new_first_name']) ? htmlspecialchars($_POST['new_first_name']) : ''; ?>">
                                <div class="invalid-feedback">
                                    <?php echo $new_customer_name_error; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="new_last_name" class="form-label">Nama Belakang (Opsional):</label>
                                <input type="text" class="form-control" id="new_last_name" name="new_last_name" value="<?php echo isset($_POST['new_last_name']) ? htmlspecialchars($_POST['new_last_name']) : ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_email" class="form-label">Email:</label>
                            <input type="email" class="form-control <?php echo (!empty($new_customer_email_error)) ? 'is-invalid' : ''; ?>" id="new_email" name="new_email" value="<?php echo isset($_POST['new_email']) ? htmlspecialchars($_POST['new_email']) : ''; ?>">
                            <div class="invalid-feedback">
                                <?php echo $new_customer_email_error; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="new_phone" class="form-label">Nomor Telepon:</label>
                            <input type="text" class="form-control <?php echo (!empty($new_customer_phone_error)) ? 'is-invalid' : ''; ?>" id="new_phone" name="new_phone" value="<?php echo isset($_POST['new_phone']) ? htmlspecialchars($_POST['new_phone']) : ''; ?>">
                            <div class="invalid-feedback">
                                <?php echo $new_customer_phone_error; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="service_id" class="form-label">Pilih Layanan:</label>
                        <select class="form-select <?php echo (!empty($service_error)) ? 'is-invalid' : ''; ?>" id="service_id" name="service_id" required>
                            <option value="">-- Pilih Layanan --</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?php echo htmlspecialchars($service['service_id']); ?>"
                                    <?php echo (isset($_POST['service_id']) && $_POST['service_id'] == $service['service_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($service['service_name']) . " (Rp " . number_format($service['price'], 0, ',', '.') . ")"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo $service_error; ?>
                        </div>
                        <small class="form-text text-muted">Jika layanan belum terdaftar, tambahkan di <a href="manage_services.php">Manajemen Layanan</a>.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="booking_date" class="form-label">Tanggal Booking:</label>
                            <input type="date" class="form-control" id="booking_date" name="booking_date" required
                                value="<?php echo isset($_POST['booking_date']) ? htmlspecialchars($_POST['booking_date']) : date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="booking_time" class="form-label">Waktu Booking:</label>
                            <input type="time" class="form-control" id="booking_time" name="booking_time" required
                                value="<?php echo isset($_POST['booking_time']) ? htmlspecialchars($_POST['booking_time']) : date('H:i'); ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Tambah Pemesanan</button>
                    <a href="admin_dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleCustomerFields() {
            const existingCustomerRadio = document.getElementById('existingCustomer');
            const newCustomerRadio = document.getElementById('newCustomer');
            const existingCustomerFields = document.getElementById('existing_customer_fields');
            const newCustomerFields = document.getElementById('new_customer_fields');

            if (existingCustomerRadio.checked) {
                existingCustomerFields.style.display = 'block';
                newCustomerFields.style.display = 'none';
                // Set 'required' attribute for existing customer select
                document.getElementById('customer_id').setAttribute('required', 'required');
                // Remove 'required' for new customer fields
                document.getElementById('new_first_name').removeAttribute('required');
                document.getElementById('new_email').removeAttribute('required');
                document.getElementById('new_phone').removeAttribute('required');
            } else if (newCustomerRadio.checked) {
                existingCustomerFields.style.display = 'none';
                newCustomerFields.style.display = 'block';
                // Remove 'required' for existing customer select
                document.getElementById('customer_id').removeAttribute('required');
                // Set 'required' for new customer fields
                document.getElementById('new_first_name').setAttribute('required', 'required');
                // You might want to make email/phone required as well, add 'required' attribute
                document.getElementById('new_email').setAttribute('required', 'required');
                document.getElementById('new_phone').setAttribute('required', 'required');

            }
        }

        // Panggil fungsi saat halaman dimuat untuk mengatur tampilan awal
        document.addEventListener('DOMContentLoaded', toggleCustomerFields);

        // Jika ada error validasi saat POST, pastikan field yang relevan tetap terlihat
        <?php if (!empty($new_customer_name_error) || !empty($new_customer_email_error) || !empty($new_customer_phone_error)): ?>
            document.getElementById('newCustomer').checked = true;
            toggleCustomerFields();
        <?php elseif (!empty($customer_error)): ?>
            document.getElementById('existingCustomer').checked = true;
            toggleCustomerFields();
        <?php endif; ?>
    </script>
</body>
</html>

<?php
// Koneksi ke database ditutup di akhir script
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>