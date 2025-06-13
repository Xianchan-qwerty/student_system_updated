<?php
// Include the database connection file
include "db_conn.php";

/**
 * Function to check if a table exists in the database.
 * @param mysqli $conn The database connection object.
 * @param string $tableName The name of the table to check.
 * @return bool True if the table exists, false otherwise.
 */
function tableExists($conn, $tableName) {
    $sql = "SHOW TABLES LIKE '$tableName'";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

/**
 * Function to check if a column exists in a given table.
 * @param mysqli $conn The database connection object.
 * @param string $tableName The name of the table.
 * @param string $columnName The name of the column to check.
 * @return bool True if the column exists, false otherwise.
 */
function columnExists($conn, $tableName, $columnName) {
    $sql = "SHOW COLUMNS FROM `$tableName` LIKE '$columnName'";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

// Check if the 'students' table exists
$studentsTableExists = tableExists($conn, 'students');

if (!$studentsTableExists) {
    // If table doesn't exist, create it with all necessary columns including 'created_at'
    $createTableSQL = "CREATE TABLE students (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        course VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if ($conn->query($createTableSQL)) {
        echo "<div class='alert alert-success'>'students' table created successfully.</div>";
        $studentsTableExists = true; // Set to true after creation
    } else {
        echo "<div class='alert alert-danger'>Error creating table: " . htmlspecialchars($conn->error) . "</div>";
    }
} else {
    // If table exists, check if 'created_at' column exists and add it if missing
    if (!columnExists($conn, 'students', 'created_at')) {
        $alterTableSQL = "ALTER TABLE students ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        if ($conn->query($alterTableSQL)) {
            echo "<div class='alert alert-success'>'created_at' column added to 'students' table successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error adding 'created_at' column: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student.Sys | Student Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-code"></i> Student Management System
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add.php">
                            <i class="fas fa-user-plus"></i> Add Student
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="main-container fade-in-element">
            <h1 class="page-title">
                <i class="fas fa-graduation-cap"></i> Student Dashboard
            </h1>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm fade-in-element" style="animation-delay: 0.2s;">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h4><?= $studentsTableExists ? $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'] : 0 ?></h4>
                            <p class="mb-0">Total Students</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm fade-in-element" style="animation-delay: 0.4s;">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-2x mb-2"></i>
                            <h4><?= $studentsTableExists ? $conn->query("SELECT COUNT(DISTINCT course) as count FROM students WHERE course != ''")->fetch_assoc()['count'] : 0 ?></h4>
                            <p class="mb-0">Courses</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm fade-in-element" style="animation-delay: 0.6s;">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <h4>Active</h4>
                            <p class="mb-0">System Status</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0 slide-in-left">
                    <i class="fas fa-list"></i> Student Registry
                </h3>
                <a href="add.php" class="btn btn-info slide-in-right sparkle">
                    <i class="fas fa-plus"></i> Add New Student
                </a>
            </div>

            <div class="search-container fade-in-element" style="animation-delay: 0.8s;">
                <form method="GET" action="" class="row">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control" placeholder="🔍 Search by name or email..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <?php if (isset($_GET['search']) && $_GET['search'] != '') : ?>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-container fade-in-element" style="animation-delay: 1s;">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i><br/>ID</th>
                                <th><i class="fas fa-user"></i><br/>Name</th>
                                <th><i class="fas fa-envelope"></i><br/>Email</th>
                                <th><i class="fas fa-graduation-cap"></i><br/>Course</th>
                                <th><i class="fas fa-clock"></i><br/>Added</th>
                                <th><i class="fas fa-cogs"></i><br/>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get search term from GET request, sanitize it
                            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
                            
                            // SQL query to select all students, ordered by ID for consistent display order
                            // Filter by search term if provided
                            $sql = "SELECT id, full_name, email, course, created_at FROM students";
                            if (!empty($search)) {
                                $sql .= " WHERE full_name LIKE '%$search%' OR email LIKE '%$search%'";
                            }
                            $sql .= " ORDER BY id ASC"; // Ensure results are ordered by actual ID for consistent display

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $counter = 1; // Initialize counter for sequential display ID
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr class='fade-in-element' style='animation-delay: " . (1.2 + ($counter * 0.1)) . "s;'>
                                        <td>{$counter}</td> <td>" . htmlspecialchars($row['full_name']) . "</td>
                                        <td>" . htmlspecialchars($row['email']) . "</td>
                                        <td>" . htmlspecialchars($row['course']) . "</td>
                                        <td>" . date("Y-m-d", strtotime($row['created_at'])) . "</td>
                                        <td>
                                            <div class='d-flex gap-1'>
                                                <a href='edit.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                                <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm delete-btn' data-student-name='" . htmlspecialchars($row['full_name']) . "'>Delete</a>
                                            </div>
                                        </td>
                                    </tr>";
                                    $counter++; // Increment counter for the next row
                                }
                            } else {
                                // Message if no students are found or search yields no results
                                echo "<tr><td colspan='6' class='text-center'>No students found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="customConfirmModal" class="modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modalCloseButton">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete "<span id="studentNameToDelete"></span>"?
                    <br><br>This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelDeleteBtn">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>
</html>
