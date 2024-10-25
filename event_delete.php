<!-- event_delete.php -->
<?php
$event_id = $_GET['id'];

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hapus event dengan konfirmasi
if (isset($_POST['confirm'])) {
    $sql = "DELETE FROM events WHERE event_id = $event_id";

    if ($conn->query($sql) === TRUE) {
        header("Location:admin_dashboard.php");
    } else {
        echo "Error deleting event: " . $conn->error;
}
} else {
?>
    <form method="POST">
        <p>Are you sure you want to delete this event?</p>
        <input type="submit" name="confirm" value="Yes, delete it!">
    </form>
<?php
}

$conn->close();
?>
