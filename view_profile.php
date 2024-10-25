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

$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan informasi user
$sql_user = "SELECT name, email FROM users WHERE user_id = $user_id";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

// Query untuk mendapatkan event yang didaftarkan oleh user
$sql_events = "
    SELECT events.event_name, events.date, events.location, registrations.registration_date
    FROM registrations
    INNER JOIN events ON registrations.event_id = events.event_id
    WHERE registrations.user_id = $user_id";
$result_events = $conn->query($sql_events);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            background-color: #2c3e50;
            width: 250px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 2rem;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #34495e;
            border-radius: 8px;
            text-align: center;
            font-size: 1.1rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #1abc9c;
            transform: translateX(10px);
        }

        .sidebar .logout {
            background-color: #e74c3c;
        }

        .sidebar .logout:hover {
            background-color: #c0392b;
            transform: translateX(10px);
        }

        /* Main content styling */
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
            background-color: #ecf0f1;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #34495e;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        h2 {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 15px;
            border-bottom: 2px solid #3498db;
            display: inline-block;
            padding-bottom: 5px;
        }

        p {
            font-size: 1.2rem;
            color: #34495e;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: center;
            font-size: 1rem;
        }

        th {
            background-color: #2980b9;
            color: white;
            font-size: 1.1rem;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #2980b9;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #1abc9c;
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <h2>User Menu</h2>
<a href="welcome.php">Welcome Page</a>
            <a href="user_dashboard.php">Dashboard</a>
            <a href="edit_profile.php">Edit Profile</a>
        </div>
        <div>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Your Profile</h1>

        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
    </div>

</body>
</html>

<?php $conn->close(); ?>
