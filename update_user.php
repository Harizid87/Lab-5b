<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'Lab_5b');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$matric = $_GET['matric'];

// Fetch user data
$sql = "SELECT matric, name, role FROM users WHERE matric='$matric'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newMatric = $_POST['matric'];  // Capturing new matric value from form
    $name = $_POST['name'];
    $role = $_POST['role'];

    // Update user data
    $update_sql = "UPDATE users SET matric='$newMatric', name='$name', role='$role' WHERE matric='$matric'";
    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['success_message'] = "User updated successfully!";
        header('Location: update_user.php?matric=' . $newMatric); // Redirect to this page to display the success message
        exit();
    } else {
        $error_message = "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Update User</h2>

        <!-- Show success message if available -->
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
            unset($_SESSION['success_message']);
        }

        // Show error message if available
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="matric">Matric:</label>
                <input type="text" class="form-control" name="matric" value="<?php echo $user['matric']; ?>" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" class="form-control" required>
                    <option value="Lecturer" <?php echo ($user['role'] == 'Lecturer') ? 'selected' : ''; ?>>Lecturer</option>
                    <option value="Student" <?php echo ($user['role'] == 'Student') ? 'selected' : ''; ?>>Student</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
        <br>
        <a href="display.php" class="btn btn-secondary">Back to Users List</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.5/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
