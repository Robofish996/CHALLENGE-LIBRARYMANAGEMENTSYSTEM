<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "INSERT INTO librarians (name, email, password, role) VALUES ('$name', '$email', '$hashed_password', 'librarian')";
    $result = mysqli_query($connection, $query);

    if ($result) {
        // Redirect with a success message
        header("Location: ../pages/addLibrarian.php?success=true");
        exit();
    } else {
        // Redirect with an error message
        header("Location: ../pages/addLibrarian.php?success=false");
        exit();
    }

    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styling/css/settings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
    <title>Add New Librarian</title>
</head>
<body>
    <!-- Nav Bar -->
    <nav class="navbar">
        <div class="logo">
            <a href="#">Crest Library</a>
        </div>
        <ul class="nav-links">
            <li><a href="../pages/admin.php">Home</a></li>
        </ul>
        <div class="user-dropdown">
            <i class="fas fa-user"></i>
            <div class="dropdown-content">
                <a href="../index.php?logout=true">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1>Add Librarian</h1>

        <div class="update-form">
            <h2>Add Information</h2>
            <p>Please fillout all fields</p>
            <form method="post">
                <label for="new_name">New Name:</label>
                <input type="text" id="new_name" name="name" required>
                <br>
                <label for="new_email">New Email:</label>
                <input type="email" id="new_email" name="email" required>
                <br>
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <br>
                <button type="submit">Add User</button>
            </form>
        </div>
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