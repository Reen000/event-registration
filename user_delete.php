<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Jika bukan admin, arahkan kembali ke halaman login
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Dapatkan ID user dari URL
$user_id = $_GET['id'];

// Hapus user dari database, termasuk semua registrasi event-nya
$sql_delete_user = "DELETE FROM users WHERE user_id = $user_id";
$sql_delete_registrations = "DELETE FROM registrations WHERE user_id = $user_id";

if ($conn->query($sql_delete_registrations) === TRUE && $conn->query($sql_delete_user) === TRUE) {
    header("Location:user_management.php");
} else {
    echo "Error deleting user: " . $conn->error;
}

$conn->close();

// Redirect ke halaman user management
header("Location: user_management.php");
exit;
?>
