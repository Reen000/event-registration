<!-- event_edit.php -->
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

$sql = "SELECT * FROM events WHERE event_id = $event_id";
$result = $conn->query($sql);
$event = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
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

form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    max-width: 600px;
    margin: 0 auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

label {
    font-size: 1.1rem;
    color: #34495e;
    margin-bottom: 5px;
}

input[type="text"],
input[type="date"],
input[type="time"],
input[type="number"],
textarea,
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus,
input[type="date"]:focus,
input[type="time"]:focus,
input[type="number"]:focus,
textarea:focus,
select:focus {
    border-color: #3498db;
}

textarea {
    height: 100px;
}

input[type="file"] {
    border: none;
}

input[type="submit"] {
    background-color: #3498db;
    color: white;
    padding: 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.1rem;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #2980b9;
    transform: translateY(-3px);
}


    </style>
    <script>
        // Validasi form sebelum dikirim
        function validateForm() {
            var eventName = document.getElementById('event_name').value;
            var description = document.getElementById('description').value;
            var date = document.getElementById('date').value;
            var time = document.getElementById('time').value;
            var location = document.getElementById('location').value;
            var maxParticipants = document.getElementById('max_participants').value;

            if (eventName === "" || description === "" || date === "" || time === "" || location === "" || maxParticipants === "") {
                alert("Please fill out all fields.");
                return false;
            }

            // Validasi jumlah peserta maksimum tidak boleh kurang dari 1
            if (maxParticipants < 1) {
                alert("Maximum participants must be at least 1.");
                return false;
            }

            return true;
        }
    </script>
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
        <h1>Edit Event</h1>

        <form action="event_edit_process.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
            
            <label for="event_name">Event Name:</label>
            <input type="text" id="event_name" name="event_name" value="<?= htmlspecialchars($event['event_name']) ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($event['description']) ?></textarea>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?= htmlspecialchars($event['date']) ?>" required>

            <label for="time">Time:</label>
            <input type="time" id="time" name="time" value="<?= htmlspecialchars($event['time']) ?>" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>

            <label for="max_participants">Maximum Participants:</label>
            <input type="number" id="max_participants" name="max_participants" value="<?= htmlspecialchars($event['max_participants']) ?>" required>

            <label for="status">Event Status:</label>
            <select name="status">
                <option value="open" <?= $event['status'] == 'open' ? 'selected' : '' ?>>Open</option>
                <option value="closed" <?= $event['status'] == 'closed' ? 'selected' : '' ?>>Closed</option>
                <option value="canceled" <?= $event['status'] == 'canceled' ? 'selected' : '' ?>>Canceled</option>
            </select>

            <label for="banner">Change Event Banner:</label>
            <input type="file" id="banner" name="banner" accept="image/*">

            <input type="submit" value="Update Event">
        </form>
    </div>

</body>
</html>

<?php $conn->close(); ?>
