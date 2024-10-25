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

// Ambil daftar event beserta jumlah pendaftar untuk setiap event
$sql_events = "
    SELECT events.event_id, events.event_name, events.date, events.location, events.max_participants, events.status, 
    COUNT(registrations.event_id) as total_registrants 
    FROM events 
    LEFT JOIN registrations ON events.event_id = registrations.event_id 
    GROUP BY events.event_id";
$result_events = $conn->query($sql_events);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this event?");
        }
    </script>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <h2>Admin Menu</h2>
            <a href="event_create.php">Create New Event</a>
            <a href="user_management.php">Manage Users</a>
        </div>
        <div>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Admin Dashboard</h1>
        <h2>Available Events</h2>

        <table>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Max Participants</th>
                <th>Status</th>
                <th>Total Registrants</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result_events->num_rows > 0) {
                while ($event = $result_events->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($event['event_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($event['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($event['location']) . "</td>";
                    echo "<td>" . htmlspecialchars($event['max_participants']) . "</td>";
                    echo "<td>" . htmlspecialchars($event['status']) . "</td>";
                    echo "<td>" . htmlspecialchars($event['total_registrants']) . "</td>";
                    echo "<td class='actions'>";
                    echo "<a href='event_registrants.php?event_id=" . $event['event_id'] . "'>View Registrants</a> | ";
                    echo "<a href='event_edit.php?id=" . $event['event_id'] . "'>Edit</a> | ";
                    echo "<a href='event_delete.php?id=" . $event['event_id'] . "' onclick='return confirmDelete()'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No events found.</td></tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>

<?php $conn->close(); ?>
