<?php
session_start();

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Clear all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect back to index.php
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="./styling/css/index.css">
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <h1>Welcome to Crest Library</h1>
            <div class="login-register">
                <form action="pages/login.php">
                    <button type="submit">Login</button>
                </form>
                <form action="pages/register.php">
                    <button type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
