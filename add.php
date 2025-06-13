<?php include "db_conn.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/js/scripts.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Student Management</a>
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



    <div class="container mt-5">
        <h2 class="text-center">Add New Student</h2>
        <form action="" method="POST" class="form-group">
            <input type="text" name="full_name" class="form-control mb-3" placeholder="Full Name" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="text" name="course" class="form-control mb-3" placeholder="Course">
            <button type="submit" name="submit" class="btn btn-primary btn-block">Add Student</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $course = trim($_POST['course']);

            // Basic validation
            if (empty($name) || empty($email)) {
                echo "<div class='alert alert-danger'>Name and Email are required.</div>";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<div class='alert alert-danger'>Invalid email format.</div>";
            } else {
                $sql = "INSERT INTO students (full_name, email, course) VALUES ('$name', '$email', '$course')";
                if ($conn->query($sql)) {
                    echo "<div class='alert alert-success'>Student added successfully.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
                }
            }
        }
        ?>
    </div>
</body>
</html>
