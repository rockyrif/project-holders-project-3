<?php
include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";

// Check if the quotation_id is provided via GET
if (isset($_GET['quotation-id'])) {
    // Sanitize and validate the input
    $quotation_id = $_GET['quotation-id'];

    // Set the user acceptance value
    $user_acceptance = 'Accept';

    // Prepare and execute the update statement
    $sql = "UPDATE `quotation` SET user_acceptence = ? WHERE quotation_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("si", $user_acceptance, $quotation_id);

        if ($stmt->execute()) {
            // Update successful
            echo "Quotation ID $quotation_id updated successfully with user acceptance value: $user_acceptance";
        } else {
            // Update failed
            echo "Error updating quotation ID $quotation_id";
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error in preparing the statement
        echo "Error preparing SQL statement: " . $conn->error;
    }
} else {
    // Handle case when quotation-id is not provided
    echo "Quotation ID not provided";
}

// Close the database connection
$conn->close();
?>
