<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = ''; // Inisialisasi pesan kosong

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    // Cek apakah email sudah digunakan
    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        $message = "<p style='color:red;'>Email already registered. Please use a different email.</p>";
    } else {
        // Query untuk menambahkan user baru
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'user')";

        if ($conn->query($sql) === TRUE) {
            $message = "<span style='color:green;'>Registration successful. <a href='login.php'>Login here</a></span>";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sign Up</title>
    <style>
        /* Reset default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: linear-gradient(120deg, #3498db, #8e44ad);
        }

        form {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            border: none;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        a {
            color: #8e44ad;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        h2 {
            margin-bottom: 1rem;
            color: #333;
        }

        p {
            margin-top: 1rem;
            color: #555;
        }

        /* Styling untuk pesan error atau success */
        .message {
            margin-top: 1rem;
            font-size: 1rem;
        }
    </style>
    <script>
        // JavaScript form validation
        function validateForm() {
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

            if (name == "" || email == "" || password == "") {
                alert("All fields are required.");
                return false;
            }

            // Validate password length
            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <form method="POST" action="" onsubmit="return validateForm()">
        <h2>Sign Up</h2>

        <!-- Tampilkan pesan setelah h2 -->
        <div class="message">
            <?php echo $message; ?>
        </div>

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" placeholder="Enter your name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" placeholder="Enter your email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Enter your password" required><br><br>

        <input type="submit" value="Sign Up">
        <p><a href="login.php">Already have an account? Login here</a></p>
    </form>
</body>
</html>