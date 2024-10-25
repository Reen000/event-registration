<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    // Jika bukan user, arahkan kembali ke halaman login
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

$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];

// Cek apakah user terdaftar dalam event ini
$sql_check_registration = "SELECT * FROM registrations WHERE event_id = $event_id AND user_id = $user_id";
$registration_result = $conn->query($sql_check_registration);

if ($registration_result->num_rows == 0) {
    echo "<p style='color:red;'>You are not registered for this event.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Jika user mengonfirmasi pembatalan, hapus dari tabel registrations
    $sql_cancel = "DELETE FROM registrations WHERE event_id = $event_id AND user_id = $user_id";
    if ($conn->query($sql_cancel) === TRUE) {
        echo "<p style='color:green;'>You have successfully canceled your registration.</p>";
        header("Location: registered_events.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Event Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: linear-gradient(120deg, #2980b9, #8e44ad);
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            color: #333;
            margin-bottom: 1.5rem;
        }

        p {
            margin-bottom: 1.5rem;
            color: #555;
        }

        form input[type="submit"] {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #c0392b;
        }

        a {
            display: block;
            margin-top: 1rem;
            color: #2980b9;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function confirmCancel() {
            return confirm("Are you sure you want to cancel your registration?");
        }
    </script>
</head>
<body>

    <div class="container">
        <h1>Cancel Event Registration</h1>
        <p>Are you sure you want to cancel your registration for this event?</p>

        <form method="POST" onsubmit="return confirmCancel()">
            <input type="submit" value="Yes, Cancel Registration">
        </form>

        <a href="registered_events.php">Back to Registered Events</a><br>
        <a href="logout.php">Logout</a>
    </div>

</body>
</html>

<?php $conn->close(); ?>
