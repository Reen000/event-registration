<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil list event yang statusnya 'open'
$sql = "SELECT event_id, event_name, date, location, banner FROM events WHERE status = 'open' AND date >= CURDATE()";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Available Events</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Raleway", sans-serif;
        }
    </style>
</head>

<body class="w3-light-grey w3-content" style="max-width:1600px">

    <!-- Sidebar/menu -->
    <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
        <div class="w3-container">
            <a href="#" onclick="w3_close()" class="w3-hide-large w3-right w3-jumbo w3-padding w3-hover-grey"
                title="close menu">
                <i class="fa fa-remove"></i>
            </a>
            <h4><b>User Menu</b></h4>
        </div>
        <div class="w3-bar-block">
            <a href="" onclick="w3_close()" class="w3-bar-item w3-button w3-padding w3-text-teal">
                <i class="fa fa-th-large fa-fw w3-margin-right"></i>Welcome Page</a>
            <a href="user_dashboard.php" onclick="w3_close()" class="w3-bar-item w3-button w3-padding w3">
                <i class="fa fa-th-large fa-fw w3-margin-right"></i>User Dashboard</a>
        </div>
    </nav>

    <!-- Overlay effect when opening sidebar on small screens -->
    <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer"
        title="close side menu" id="myOverlay"></div>

    <!-- !PAGE CONTENT! -->
    <div class="w3-main" style="margin-left:300px">

        <!-- Header -->
        <header id="portfolio">
            <div class="w3-container">
                <h1><b>Welcome, <?php echo $_SESSION['name'] . " !"; ?></b></h1>
                <div class="w3-section w3-bottombar w3-padding-16">
                    <span>Available Events</span>
                </div>
            </div>
        </header>

        <!-- Event List -->
        <div class="w3-row-padding">
            <?php
            if ($result->num_rows > 0) {
                // Loop melalui setiap event yang tersedia
                while ($row = $result->fetch_assoc()) {
                    // Jika tidak ada banner, tampilkan gambar default
                    $banner = !empty($row['banner']) ? htmlspecialchars($row['banner']) : 'default_event_image.jpg';
                    echo '
                    <div class="w3-third w3-container w3-margin-bottom">
                        <div class="w3-container w3-white w3-padding">
                            <img src="uploads/' . $banner . '" alt="' . htmlspecialchars($row['event_name']) . '" style="width:100%" class="w3-hover-opacity">
                            <h3><b>' . htmlspecialchars($row['event_name']) . '</b></h3>
                            <p><i class="fa fa-calendar"></i> ' . htmlspecialchars($row['date']) . '</p>
                            <p><i class="fa fa-map-marker"></i> ' . htmlspecialchars($row['location']) . '</p>
                            <a href="event_details.php?event_id=' . $row['event_id'] . '" class="w3-button w3-teal w3-block">View Details</a>
                        </div>
                    </div>';
                }
            } else {
                echo "<p>No events available at the moment.</p>";
            }
            ?>
        </div>

        <!-- Footer -->
        <div class="w3-black w3-center w3-padding-24"></div>

    </div>

    <script>
        // Script to open and close sidebar
        function w3_open() {
            document.getElementById("mySidebar").style.display = "block";
            document.getElementById("myOverlay").style.display = "block";
        }

        function w3_close() {
            document.getElementById("mySidebar").style.display = "none";
            document.getElementById("myOverlay").style.display = "none";
        }
    </script>

</body>

</html>

<?php
$conn->close();
?>
