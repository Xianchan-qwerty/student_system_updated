<?php
// Include the database connection file
include "db_conn.php";

// Get the student ID from the URL parameter
// It's good practice to sanitize input, even for IDs
$id = intval($_GET['id']); // Ensure ID is an integer

// Prepare SQL DELETE statement
// Use a prepared statement for security
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id); // 'i' indicates integer type for ID

// Execute the statement
if ($stmt->execute()) {
    // Redirect back to the index page after successful deletion
    header("Location: index.php");
    exit(); // Always exit after a header redirect
} else {
    // Display error if deletion fails
    echo "<div class='alert alert-danger'>Error deleting record: " . htmlspecialchars($stmt->error) . "</div>";
}
$stmt->close(); // Close the prepared statement

// IMPORTANT NOTE ON ID RENUMBBERING:
// The request mentioned re-ordering IDs (1, 2, 3 ascending) after deletion.
// In a relational database, primary keys (like 'id') are unique identifiers
// and are generally not re-ordered or reset after deletion. This can lead to
// significant data integrity issues, especially if other tables reference these IDs.
//
// The 'index.php' file now displays a sequential "No." for table rows,
// which gives the visual effect of re-ordering without modifying the actual
// database IDs. This is the recommended and safest approach.
//
// If you absolutely need to re-sequence database IDs (which is strongly discouraged),
// it would involve much more complex and potentially destructive database operations
// (e.g., recreating the table, re-inserting data), which are outside the scope
// of a simple delete script and generally not advisable for production systems.
?>
