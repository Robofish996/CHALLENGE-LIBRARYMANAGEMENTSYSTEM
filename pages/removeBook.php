<?php
session_start();

// Check if the user is logged in and has admin role, if not, redirect to login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'librarian') {
    header("Location: login.php");
    exit;
}

//database connection
$connection = mysqli_connect('localhost', 'Mathew', 'mysql@123', 'crestlibrary');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

class Librarian
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getAllBooks()
    {
        $query = "SELECT * FROM books";
        $result = mysqli_query($this->connection, $query);

        $books = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }

        return $books;
    }

    public function removeBook($bookId)
    {
        $query = "UPDATE books SET status = 'Unavailable' WHERE book_id = $bookId";
        mysqli_query($this->connection, $query);
    }

    public function publishBook($bookId)
    {
        $query = "UPDATE books SET status = 'Available' WHERE book_id = $bookId";
        mysqli_query($this->connection, $query);
    }
}

$librarian = new Librarian($connection);

// Handle book removal and publishing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookId = $_POST['book_id'];

    if (isset($_POST['remove_book'])) {
        $librarian->removeBook($bookId);
        // Redirect back to the same page after removing the book
        header("Location: removeBook.php");
        exit;
    } elseif (isset($_POST['publish_book'])) {
        $librarian->publishBook($bookId);
        // Redirect back to the same page after publishing the book
        header("Location: removeBook.php");
        exit;
    }
}

// Get all books using the getAllBooks method
$allBooks = $librarian->getAllBooks();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove and Publish Books</title>
    <link rel="stylesheet" type="text/css" href="../styling/css/removeBook.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <div class="books-list">
    <?php foreach ($allBooks as $book) : ?>
        <div class="book-item">
            <p>ID: <?php echo $book['book_id']; ?></p>
            <p>Title: <?php echo $book['book_title']; ?></p>
            <p>Author: <?php echo $book['author']; ?></p>
            <p class="book-status <?php echo strtolower($book['status']); ?>">
                Status: <?php echo $book['status']; ?>
            </p>
            <form method="post">
                <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                <button type="submit" name="remove_book">Remove Book</button>
                <button type="submit" name="publish_book">Publish Book</button>
            </form>
        </div>
    <?php endforeach; ?>
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