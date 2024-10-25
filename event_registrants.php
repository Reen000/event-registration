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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrants for <?= htmlspecialchars($event['event_name']); ?></title>
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
    font-size: 1.8rem;
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

table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
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
            <h2>Admin Menu</h2>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="event_create.php">Create New Event</a>
            <a href="user_management.php">Manage Users</a>
        </div>
        <div>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Registrants for <?= htmlspecialchars($event['event_name']); ?></h1>

        <table>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Registration Date</th>
            </tr>
            <?php
            if ($result_registrants->num_rows > 0) {
                while ($registrant = $result_registrants->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($registrant['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($registrant['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($registrant['registration_date']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No registrants found for this event.</td></tr>";
            }
            ?>
        </table>

        <a href="export_registrants.php?event_id=<?= $event_id; ?>">Export to CSV</a><br>
        <a href="admin_dashboard.php">Back to Dashboard</a><br>
    </div>

</body>
</html>

<?php $conn->close(); ?>
