<!-- event_edit_process.php -->
<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form
$event_id = $_POST['event_id'];
$event_name = $_POST['event_name'];
$description = $_POST['description'];
$date = $_POST['date'];
$time = $_POST['time'];
$location = $_POST['location'];
$max_participants = $_POST['max_participants'];
$status = $_POST['status'];

// Jika ada file baru di-upload
if ($_FILES['banner']['name']) {
    $banner = $_FILES['banner']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($banner);

    if (move_uploaded_file($_FILES['banner']['tmp_name'], $target_file)) {
        // Update dengan banner baru
        $sql = "UPDATE events SET event_name='$event_name', description='$description', date='$date', time='$time', 
                location='$location', banner='$banner', max_participants=$max_participants, status='$status' WHERE event_id=$event_id";
    }
} else {
    // Update tanpa mengubah banner
    $sql = "UPDATE events SET event_name='$event_name', description='$description', date='$date', time='$time', 
            location='$location', max_participants=$max_participants, status='$status' WHERE event_id=$event_id";
}

if ($conn->query($sql) === TRUE) {
    header("Location:admin_dashboard.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
