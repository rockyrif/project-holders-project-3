<?php
session_start(); // Start the session

$prescription_id = $_POST['prescription_id'] ?? null;
$pharmacy_id = $_POST['pharmacy_id'] ?? null;
$customer_id = $_POST['customer_id'] ?? null;

// php mailing header
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";

    // Retrieve quotation data from POST
    $drug_names = $_POST['drug_name'] ?? [];
    $quantities = $_POST['quantity'] ?? [];
    $unit_prices = $_POST['unit_price'] ?? [];
    $amounts = $_POST['amount'] ?? [];

    if (!empty($drug_names) && !empty($quantities) && !empty($unit_prices) && !empty($amounts)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO quotation (prescription_id, pharmacy_id, customer_id, drug_name, quantity, unit_price, amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisidd", $prescription_id, $pharmacy_id, $customer_id, $drug_name, $quantity, $unit_price, $amount);

        // Insert each quotation item
        for ($i = 0; $i < count($drug_names); $i++) {
            $drug_name = $drug_names[$i];
            $quantity = $quantities[$i];
            $unit_price = $unit_prices[$i];
            $amount = $amounts[$i];
            $stmt->execute();
        }

        $stmt->close();
        

        // Respond with success message
        echo 'Quotation submitted successfully.';

        // Start output buffering
       

        // Retrieve customer name and email from the database
        $sql_customer = "SELECT name, email FROM `customer_details` WHERE customer_id = ?";
        $stmt_customer = $conn->prepare($sql_customer);
        $stmt_customer->bind_param("i", $customer_id);
        $stmt_customer->execute();
        $result_customer = $stmt_customer->get_result();
        $row_customer = $result_customer->fetch_assoc();
        $customer_name = $row_customer['name'];
        $customer_email = $row_customer['email'];
        $stmt_customer->close();

        // Retrieve pharmacy name from the database
        $sql_pharmacy = "SELECT name FROM `pharmacy_details` WHERE pharmacy_id = ?";
        $stmt_pharmacy = $conn->prepare($sql_pharmacy);
        $stmt_pharmacy->bind_param("i", $pharmacy_id);
        $stmt_pharmacy->execute();
        $result_pharmacy = $stmt_pharmacy->get_result();
        $row_pharmacy = $result_pharmacy->fetch_assoc();
        $pharmacy_name = $row_pharmacy['name'];
        $stmt_pharmacy->close();

        // Include PHPMailer autoload
        require '../../PHP-mailer/vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mohamedrifky22061999@gmail.com';
            $mail->Password = 'tlsrxqwrubhewucw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Set custom CA certificates to trust the self-signed certificate
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('mohamedrifky22061999@gmail.com', 'Auto mail generator');
            $mail->addAddress($customer_email, $customer_name);

            $mail->isHTML(true);
            $mail->Subject = 'New Quotation submission';
            $mail->Body    = "New Quotation submission from $pharmacy_name";
            $mail->AltBody = "New Quotation submission from $pharmacy_name";

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    
    } else {
        // Respond with error message
        echo 'Invalid data.';
    }
}
?>
