<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'Lab_5b');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$matric = $_GET['matric'];

// Delete user data
$sql = "DELETE FROM users WHERE matric='$matric'";

if ($conn->query($sql) === TRUE) {
    // Redirect to users list page after successful deletion
    header('Location: display.php?message=User+deleted+successfully');
    exit();
} else {
    echo "<div class='alert alert-danger'>Error deleting record: " . $conn->error . "</div>";
}

$conn->close();
?>
