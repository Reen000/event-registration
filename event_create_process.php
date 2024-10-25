<!-- event_create_process.php -->
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
$event_name = $_POST['event_name'];
$description = $_POST['description'];
$date = $_POST['date'];
$time = $_POST['time'];
$location = $_POST['location'];
$max_participants = $_POST['max_participants'];

// Proses file banner
$banner = $_FILES['banner']['name'];
$target_dir = "uploads/";
$target_file = $target_dir . basename($banner);

// Pindahkan file yang di-upload ke folder uploads
if (move_uploaded_file($_FILES['banner']['tmp_name'], $target_file)) {
    // Query untuk menyimpan event ke database
    $sql = "INSERT INTO events (event_name, description, date, time, location, banner, max_participants, status) 
            VALUES ('$event_name', '$description', '$date', '$time', '$location', '$banner', $max_participants, 'open')";

    if ($conn->query($sql) === TRUE) {
        header("Location:admin_dashboard.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Sorry, there was an error uploading the file.";
}

$conn->close();
?>
