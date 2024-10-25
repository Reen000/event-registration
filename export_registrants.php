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

// Dapatkan ID event dari URL
$event_id = $_GET['event_id'];

// Ambil informasi event
$sql_event = "SELECT event_name FROM events WHERE event_id = $event_id";
$result_event = $conn->query($sql_event);
$event = $result_event->fetch_assoc();

// Ambil daftar pendaftar untuk event ini
$sql_registrants = "
    SELECT users.name, users.email, registrations.registration_date 
    FROM registrations 
    INNER JOIN users ON registrations.user_id = users.user_id 
    WHERE registrations.event_id = $event_id";
$result_registrants = $conn->query($sql_registrants);

// Nama file CSV yang akan dihasilkan
$filename = "registrants_" . str_replace(' ', '_', $event['event_name']) . ".csv";

// Header agar browser mendownload file CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=' . $filename);

// Buka output stream untuk menulis data CSV
$output = fopen('php://output', 'w');

// Tulis header kolom ke CSV
fputcsv($output, array('User Name', 'Email', 'Registration Date'));

// Tulis data pendaftar ke CSV
if ($result_registrants->num_rows > 0) {
    while ($registrant = $result_registrants->fetch_assoc()) {
        fputcsv($output, array($registrant['name'], $registrant['email'], $registrant['registration_date']));
    }
} else {
    fputcsv($output, array('No registrants', '', ''));
}

// Tutup output stream
fclose($output);

$conn->close();
?>
