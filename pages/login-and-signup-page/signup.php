<?php
session_start();
// Include config file
include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";

// Define variables and initialize with empty values
$username = $email = $password = "";
$username_err = $email_err = $password_err = "";
$pri = $_POST["privilage"];

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate name
    if (empty(trim($_POST["name"]))) {
        $username_err = "Please enter your name.<br>";
        $_SESSION['response'] = $username_err;
        header("location:index.php");
        exit;
    } else {
        $username = trim($_POST["name"]);
    }

    // Validate email format
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.<br>";
        $_SESSION['response'] = $email_err;
        header("location:index.php");
        exit;
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.<br>";
        $_SESSION['response'] = $email_err;
        header("location:index.php");
        exit;
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.<br>";
        $_SESSION['response'] = $password_err;
        header("location:index.php");
        exit;
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.<br>";
        $_SESSION['response'] = $password_err;
        header("location:index.php");
        exit;
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($email_err) && empty($password_err)) {

        // Prepare a select statement to check if email is already registered
        $sql = "SELECT email FROM user_login WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $email_err = "This email is already registered.<br>";
                    $_SESSION['response'] = $email_err;
                    header("location:index.php");
                    exit;
                }
            } else {
                $_SESSION['response'] = "Oops! Something went wrong. Please try again later.<br>";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        // Prepare a select statement to check if username is already registered
        $sql1 = "SELECT username FROM user_login WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql1)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $username_err = "This username is already registered.<br>";
                    $_SESSION['response'] = $username_err;
                    header("location:index.php");
                    exit;
                }
            } else {
                $_SESSION['response'] = "Oops! Something went wrong. Please try again later.<br>";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }



        // If no email conflict, proceed with inserting into database
        if (empty($email_err) && empty($username_err)) {
            // Prepare an insert statement
            $sql = "INSERT INTO user_login (username, password, email, privilage) VALUES (?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_email, $param_privilage);

                // Set parameters
                $param_username = $username;
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                $param_privilage = $pri;
                


                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {

                    // Redirect to login page
                    header("location: index.php?signup=1");
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }

    // Close connection
    mysqli_close($conn);
}
