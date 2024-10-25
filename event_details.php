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

// Dapatkan event_id dari URL
$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan detail event berdasarkan event_id
$sql = "SELECT * FROM events WHERE event_id = $event_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
} else {
    echo "Event not found.";
    exit;
}

// Cek apakah user sudah mendaftar ke event ini
$sql_check_registration = "SELECT * FROM registrations WHERE event_id = $event_id AND user_id = $user_id";
$registration_result = $conn->query($sql_check_registration);
$already_registered = ($registration_result->num_rows > 0);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$already_registered) {
    // Jika user mengklik tombol register, masukkan data ke tabel registrations
    $sql_register = "INSERT INTO registrations (user_id, event_id, registration_date) VALUES ($user_id, $event_id, NOW())";
    if ($conn->query($sql_register) === TRUE) {
        echo "Successfully registered for the event!";
        header("Location: registered_events.php"); // Redirect ke halaman event yang sudah didaftarkan
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
    <title>Event Details</title>
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
            width: 100%;
            max-width: 600px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        p {
            margin-bottom: 1rem;
            color: #555;
        }

        .event-detail strong {
            color: #333;
        }

        form input[type="submit"] {
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #21618c;
        }

        a {
            display: inline-block;
            margin-top: 1rem;
            color: #2980b9;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .status {
            font-weight: bold;
            color: #e74c3c;
        }

        .status.open {
            color: #27ae60;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Event Details for <?= htmlspecialchars($event['event_name']); ?></h1>

        <p class="event-detail"><strong>Date:</strong> <?= htmlspecialchars($event['date']); ?></p>
        <p class="event-detail"><strong>Time:</strong> <?= htmlspecialchars($event['time']); ?></p>
        <p class="event-detail"><strong>Location:</strong> <?= htmlspecialchars($event['location']); ?></p>
        <p class="event-detail"><strong>Description:</strong> <?= htmlspecialchars($event['description']); ?></p>
        <p class="event-detail"><strong>Max Participants:</strong> <?= htmlspecialchars($event['max_participants']); ?></p>
        <p class="event-detail"><strong>Status:</strong> 
            <span class="status <?= htmlspecialchars($event['status']); ?>"><?= htmlspecialchars($event['status']); ?></span>
        </p>

        <!-- Jika user belum terdaftar, tampilkan tombol register -->
        <?php if (!$already_registered && $event['status'] == 'open'): ?>
            <form method="POST">
                <input type="submit" value="Register for Event">
            </form>
        <?php elseif ($already_registered): ?>
            <p>You have already registered for this event.</p>
        <?php else: ?>
            <p>This event is closed for registration.</p>
        <?php endif; ?>

        <br>
        <a href="welcome.php">Back</a><br>
        <a href="events.php">Back to Available Events</a><br>
        <a href="logout.php">Logout</a>
    </div>

</body>
</html>

<?php $conn->close(); ?>
