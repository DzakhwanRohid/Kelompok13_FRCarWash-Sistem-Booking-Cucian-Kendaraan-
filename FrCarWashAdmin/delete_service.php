<?php
include 'db_config.php';

// Pastikan koneksi $conn ada sebelum digunakan
if (!isset($conn) || !$conn instanceof mysqli) {
    header("Location: manage_services.php?status=error_delete&message=" . urlencode("Koneksi database gagal."));
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $service_id = $_GET['id'];
    $success = false;
    $error_message = '';

    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("DELETE FROM services WHERE service_id = ?");

    if ($stmt) { // Pastikan prepare berhasil
        $stmt->bind_param("i", $service_id);

        if ($stmt->execute()) {
            $success = true;
        } else {
            // Error bisa terjadi jika ada foreign key constraint (misal: layanan masih ada di tabel bookings)
            $error_message = $stmt->error;
        }
        $stmt->close(); // Tutup statement setelah eksekusi, baik sukses atau gagal
    } else {
        $error_message = $conn->error; // Error saat prepare
    }

    $conn->close(); // Tutup koneksi database

    if ($success) {
        header("Location: manage_services.php?status=success_delete");
        exit();
    } else {
        header("Location: manage_services.php?status=error_delete&message=" . urlencode("Gagal menghapus layanan: " . $error_message));
        exit();
    }

} else {
    // Jika tidak ada ID yang diberikan, redirect ke manage_services.php dengan pesan error
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    header("Location: manage_services.php?status=error_delete&message=" . urlencode("ID layanan tidak valid."));
    exit();
}
?>