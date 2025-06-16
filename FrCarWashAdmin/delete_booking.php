<?php
include 'db_config.php'; // Pastikan db_config.php sudah di-include

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);

// ...
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?status=success_delete");
        exit(); // <-- Ini menghentikan skrip di sini
    } else {
        header("Location: admin_dashboard.php?status=error_delete&message=" . urlencode($stmt->error));
        exit(); // <-- Ini juga menghentikan skrip di sini
    }
    // <-- Baris ini tidak akan pernah tercapai karena exit() di atasnya
} else {
    // ...
    exit(); // <-- Ini juga menghentikan skrip di sini
}
// ...
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close(); // <-- Baris ini juga tidak akan pernah tercapai karena exit()
}

// Koneksi ke database ditutup di akhir script
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>