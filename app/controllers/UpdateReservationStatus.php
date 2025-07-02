<?php
// controllers/UpdateReservationStatus.php

session_start(); // Start the session (still useful for general session management if needed elsewhere)

// Include the database connection file.
// This file is expected to provide $pdoHotel (and potentially $pdoKasir).
require_once '../../db_connection.php'; // Ensure this path is correct relative to this file

// IMPORTANT: Removed the admin login/role check as requested.
// In a production environment, this is a SECURITY VULNERABILITY.
// Anyone who can access this script can update reservation statuses.
// Re-implement authentication and authorization for production use!
//
// Original removed logic:
// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
//     header("Location: ../views/loginadmin.php?error=access_denied");
//     exit();
// }

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input
    $reservation_id = filter_input(INPUT_POST, 'reservation_id', FILTER_VALIDATE_INT);
    $new_status = filter_input(INPUT_POST, 'new_status', FILTER_SANITIZE_STRING);

    // Validate inputs
    $allowed_statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
    if ($reservation_id === false || $reservation_id === null || !in_array($new_status, $allowed_statuses)) {
        // Redirect with an error if input is invalid
        header("Location: ../views/admin.php?status=error&message=invalid_request");
        exit();
    }

    // Ensure the $pdoHotel database connection is valid before proceeding
    // This assumes db_connection.php sets $pdoHotel and handles its own errors
    if (!($pdoHotel instanceof PDO)) {
        error_log("UpdateReservationStatus: PDO Hotel connection is not established.");
        header("Location: ../views/admin.php?status=error&message=db_connection_error_hotel");
        exit();
    }

    try {
        // Prepare the SQL statement to update the reservation status
        $sql = "UPDATE reservations SET status = :new_status WHERE reservation_id = :reservation_id";
        $stmt = $pdoHotel->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':new_status', $new_status, PDO::PARAM_STR);
        $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            // Check if any row was affected by the update
            if ($stmt->rowCount() > 0) {
                // Success: Redirect back to admin page with success status
                header("Location: ../views/admin.php?status=success");
            } else {
                // No rows affected, possibly reservation_id not found
                header("Location: ../views/admin.php?status=error&message=not_found");
            }
        } else {
            // Execution failed for some reason
            header("Location: ../views/admin.php?status=error&message=db_error");
        }
    } catch (PDOException $e) {
        // Catch PDO exceptions (database errors)
        error_log("Error updating reservation status in hotel_db: " . $e->getMessage());
        header("Location: ../views/admin.php?status=error&message=db_error");
    }
} else {
    // If not a POST request, redirect with invalid request error
    header("Location: ../views/admin.php?status=error&message=invalid_request_method"); // More specific message
}
exit(); // Always exit after a header redirect
?>