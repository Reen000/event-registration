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

// Ambil semua user
$sql_users = "SELECT * FROM users WHERE role='user'";
$result_users = $conn->query($sql_users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
        </div>
        <div>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>User Management</h1>

        <h2>Registered Users</h2>
        <table>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Registered Events</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result_users->num_rows > 0) {
                while ($user = $result_users->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($user['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($user['email']) . "</td>";

                    // Ambil event yang terdaftar oleh user ini
                    $user_id = $user['user_id'];
                    $sql_registrations = "
                        SELECT events.event_name 
                        FROM registrations 
                        INNER JOIN events ON registrations.event_id = events.event_id 
                        WHERE registrations.user_id = $user_id";
                    $result_registrations = $conn->query($sql_registrations);

                    echo "<td>";
                    if ($result_registrations->num_rows > 0) {
                        while ($registration = $result_registrations->fetch_assoc()) {
                            echo htmlspecialchars($registration['event_name']) . "<br>";
                        }
                    } else {
                        echo "No events registered";
                    }
                    echo "</td>";

                    echo "<td>";
                    echo "<a href='user_delete.php?id=" . $user['user_id'] . "' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found.</td></tr>";
            }
            ?>
        </table>

        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>

</body>
</html>

<?php $conn->close(); ?>
