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

// Ambil informasi user dari database
$sql_user = "SELECT name, email FROM users WHERE user_id = $user_id";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Jika password diisi, maka update password
    if (!empty($password)) {
        $password_hash = md5($password); // Gunakan hash untuk password
        $sql_update = "UPDATE users SET name = '$name', email = '$email', password = '$password_hash' WHERE user_id = $user_id";
    } else {
        $sql_update = "UPDATE users SET name = '$name', email = '$email' WHERE user_id = $user_id";
    }

    if ($conn->query($sql_update) === TRUE) {
        echo "<p style='color:green;'>Profile updated successfully!</p>";
        header("Location: view_profile.php"); // Redirect kembali ke halaman profile
        exit;
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: linear-gradient(120deg, #3498db, #9b59b6);
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.6s ease-in-out;
        }

        h1 {
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
        }

        a {
            display: inline-block;
            margin-top: 1rem;
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        p {
            margin-bottom: 1.5rem;
            color: #555;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
    <script>
        // Validasi form dengan JavaScript
        function validateForm() {
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

            if (name === "" || email === "") {
                alert("Name and Email fields cannot be empty.");
                return false;
            }

            // Periksa panjang password jika ada input password baru
            if (password !== "" && password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

    <div class="container">
        <h1>Edit Profile</h1>

        <form method="POST" action="" onsubmit="return validateForm()">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <label for="password">Password (Leave blank if not changing):</label>
            <input type="password" id="password" name="password">

            <input type="submit" value="Update Profile">
        </form>

        <a href="view_profile.php">Back to Profile</a><br>
        <a href="user_dashboard.php">Back to Dashboard</a><br>
        <a href="logout.php">Logout</a>
    </div>

</body>
</html>

<?php $conn->close(); ?>
