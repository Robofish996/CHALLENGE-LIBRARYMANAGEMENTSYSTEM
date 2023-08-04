<?php
session_start();

// Check if the user is logged in as a librarian
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header("Location: ../index.php");
    exit();
}

// Database connection
$connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch user orders along with their names
$query = "SELECT rentals.*, members.name
          FROM rentals
          JOIN members ON rentals.user_id = members.id
          ORDER BY rentals.user_id, rentals.order_date DESC";

// Execute the query
$result = mysqli_query($connection, $query);

// Check for errors
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}
// Query to fetch top 3 ordered books
$query_top_books = "SELECT books.book_title, COUNT(rentals.book_id) AS order_count
                    FROM books
                    LEFT JOIN rentals ON books.book_id = rentals.book_id
                    GROUP BY books.book_id
                    ORDER BY order_count DESC
                    LIMIT 3";

// Execute the query for top ordered books
$result_top_books = mysqli_query($connection, $query_top_books);

// Check for errors
if (!$result_top_books) {
    die("Query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Orders</title>
    <link rel="stylesheet" type="text/css" href="../styling/css/usersOrders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
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
    <h1 class="heading">User Orders</h1>

    <!--Top ordered books -->
    <div class="top-books">
        <h2 class="heading">Top 3 Ordered Books</h2>
        <ul>
            <?php
            while ($top_book = mysqli_fetch_assoc($result_top_books)) {
                echo '<li>' . $top_book['book_title'] . ' (' . $top_book['order_count'] . ' orders)</li>';
            }
            ?>
        </ul>
    </div>

    <div >
        <h1 class="heading">User Orders</h1>

        <?php
        
        $currentUserID = null;

        // Display user orders along with their names
        while ($order = mysqli_fetch_assoc($result)) {
            if ($order['user_id'] != $currentUserID) {
                // Display user information if it's a new user
                echo '<h2 class="heading">User Name: ' . $order['name'] . '</h2>';
                $currentUserID = $order['user_id'];
            }

            echo '<div class="order-cards-container">';
            echo '<p>Order Number: ' . $order['order_number'] . '</p>';
            echo '<p>Order Date: ' . $order['order_date'] . '</p>';
            echo '<p>Book ID: ' . $order['book_id'] . '</p>';
            echo '<p>Price: R' . $order['price'] . '</p>';
            echo '<p>End Date: ' . $order['end_date'] . '</p>';
            echo '</div>';
        }

        // If no orders found
        if (mysqli_num_rows($result) === 0) {
            echo '<p>No orders found.</p>';
        }

        // Close the database connection
        mysqli_close($connection);
        ?>
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
