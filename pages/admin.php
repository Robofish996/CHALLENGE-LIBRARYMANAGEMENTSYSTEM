<?php
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the user ID from the session
$userID = $_SESSION['user_id'];
$userRole = $_SESSION['role'];

$sessionData = $_SESSION;

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Database connection (include your connection code here)
    $connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert new librarian record
    $query = "INSERT INTO librarians (name, email, password, role) VALUES ('$name', '$email', '$password', 'librarian')";
    $result = mysqli_query($connection, $query);

    // Check for errors
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    // Close the database connection
    mysqli_close($connection);

    // Show an alert message
    echo "<script>alert('User added successfully!');</script>";
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../styling/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<nav class="navbar">
        <div class="logo">
            <a href="#">Crest Library</a>
        </div>
        <!--Nav Bar-->
        <div class="user-dropdown">
            <i class="fas fa-user"></i>
            <div class="dropdown-content">
                <a href="../index.php?logout=true">Logout</a>

            </div>
        </div>
    </nav>
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <!-- Admin Dashboard Tiles -->
    <div class="dashboard">
        <a class="tile" href="blockedUsers.php">
            <i class="fas fa-user-lock"></i>
            <h3>Block a Member</h3>
        </a>
        <a class="tile" href="removeBook.php">
            <i class="fas fa-book"></i>
            <h3>Remove/Add a Book</h3>
        </a>
        <a class="tile" href="usersOrders.php">
            <i class="fas fa-file-alt"></i>
            <h3>View Orders of a User</h3>
        </a>
        <a class="tile" href="addLibrarian.php">
            <i class="fas fa-user-plus"></i>
            <h3>Add a New Librarian</h3>
        </a>
    </div>
<script>
    // JavaScript code for dropdown 
        const dropdown = document.querySelector('.user-dropdown');
        dropdown.addEventListener('click', () => {
            dropdown.classList.toggle('active');
        });
</script>
</body>
</html>
