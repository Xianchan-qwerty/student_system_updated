<?php
// Include the database connection file
include "db_conn.php";

// Get the student ID from the URL parameter
$id = $_GET['id'];

// Fetch student data from the database based on ID
// Use a prepared statement for security
$stmt = $conn->prepare("SELECT id, full_name, email, course FROM students WHERE id = ?");
$stmt->bind_param("i", $id); // 'i' indicates integer type for ID
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc(); // Fetch the single row

// If no student found with the given ID, redirect back to index or show an error
if (!$row) {
    echo "<div class='alert alert-danger'>Student not found.</div>";
    // Optionally redirect: header("Location: index.php"); exit();
}
$stmt->close(); // Close the prepared statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS for styling and animations -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <!-- Navigation Bar -->
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Student Management</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add.php">Add Student</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container mt-5 fade-in-element">
        <h2 class="text-center">Edit Student</h2>
        <form method="POST" class="form-group fade-in-element">
            <!-- Display current student data in input fields -->
            <input type="text" name="full_name" class="form-control mb-3" value="<?= htmlspecialchars($row['full_name'] ?? '') ?>" required>
            <input type="email" name="email" class="form-control mb-3" value="<?= htmlspecialchars($row['email'] ?? '') ?>" required>
            <input type="text" name="course" class="form-control mb-3" value="<?= htmlspecialchars($row['course'] ?? '') ?>">
            <button type="submit" name="update" class="btn btn-warning btn-block">Update</button>
        </form>

        <?php
        // Process form submission if the 'update' button is clicked
        if (isset($_POST['update'])) {
            // Trim whitespace from input values
            $name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $course = trim($_POST['course']);

            // Basic server-side validation
            if (empty($name) || empty($email)) {
                echo "<div class='alert alert-danger'>Name and Email are required.</div>";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<div class='alert alert-danger'>Invalid email format.</div>";
            } else {
                // Prepare SQL UPDATE statement
                // Use prepared statements for security
                $stmt = $conn->prepare("UPDATE students SET full_name=?, email=?, course=? WHERE id=?");
                // 'sssi' indicates string, string, string, integer types for parameters
                $stmt->bind_param("sssi", $name, $email, $course, $id);

                // Execute the statement
                if ($stmt->execute()) {
                    // Redirect back to the index page after successful update
                    header("Location: index.php");
                    exit(); // Always exit after a header redirect
                } else {
                    // Display error if update fails
                    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
                }
                $stmt->close(); // Close the prepared statement
            }
        }
        ?>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="assets/js/scripts.js"></script>
</body>
</html>
