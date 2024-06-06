<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings

    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'mail.adtennis.lk';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'admin@adtennis.lk';                     //SMTP username
    $mail->Password   = '2l01xVKb:EO.9p';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable explicit TLS encryption
    $mail->Port       = 465;

    // Set custom CA certificates to trust the self-signed certificate
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-2/db_conn.php";

    // Get current month and year
    $current_month = date('m');
    $current_year = date('Y');

    // SQL query to select members who haven't paid for the current month and year
    $sql = "SELECT DISTINCT m.email, m.first_name
    FROM members m
    LEFT JOIN member_fees mf ON m.member_id = mf.member_id
    WHERE (m.payment_status <> 'rejected' AND m.payment_status <> 'pending')
          AND (
                (mf.member_id NOT IN (
                    SELECT mf2.member_id
                    FROM member_fees mf2
                    WHERE mf2.month = $current_month AND mf2.year = $current_year
                ))
                OR (mf.month = $current_month AND mf.year = $current_year AND mf.payment_status = 'Not yet')
                OR (mf.member_id IS NULL)
              )";


    $result = $conn->query($sql);//

    echo "<table border='1'>";
    echo "<tr><th>Email</th><th>First Name</th></tr>";

    if ($result->num_rows > 0) {
        // Loop through the results and send emails
        while ($row = $result->fetch_assoc()) {
            $mail->isHTML(false);
            $mail->clearAddresses();
            $mail->addAddress($row['email'], $row['first_name']);
            $first_name = $row['first_name'];
            $mail->setFrom('admin@adtennis.lk', 'AD tennis admin');
            $mail->Subject = "Reminder: Payment Due";
            $mail->Body = "Dear $first_name,\n\n" .
                "This is a reminder that your membership fee for the current month is due.\n\n" .
                "We value your participation in our club and want to ensure you continue to enjoy all the benefits without any interruptions. Please make sure to pay your dues for the current month before the month ends.\n\n" .
                "By staying current with your payments, you help us maintain the high-quality activities and services that you love. Let's keep the momentum going and enjoy another fantastic month together!\n\n" .
                "Thank you for your prompt attention to this matter.\n\n".
                "Note: If you paid but see this message probably your payment not approved by admin due to a reason contact admin@adtennis.lk.\n\n".
                "Best regards,\n" .
                "ADTC";



            // Send email
            $mail->send();
        
            echo "<tr>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['first_name'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "No members found.";
    }
     // End HTML table
     echo "</table>";

    // Close connection
    $conn->close();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
