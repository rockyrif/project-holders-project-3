<?php
session_start();

// php mailing header
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ((!isset($_SESSION["id"]) && isset($_SESSION["username"])) || $_SESSION["privilage"] == "customer") {

   include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";

   // The value you are looking for
   $search_value = $_SESSION["email"];

   // The SQL query to check for the specific data
   $sql = "SELECT * FROM customer_details WHERE email = ?";

   // Prepare and bind
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("s", $search_value);

   // Execute the statement
   $stmt->execute();

   // Store the result
   $stmt->store_result();

   if ($stmt->num_rows > 0) {

      include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";
      if (isset($_POST["submit"])) {


         $pharmacy_id = $_POST['pharmacy_id'];
         $customer_id = $_POST["customer_id"];
         $note = $_POST['note'];
         $address = $_POST['address'];
         $delivery_time_1 = $_POST['delivery_time_1'];
         $delivery_time_2 = $_POST['delivery_time_2'];
         $email = $_SESSION['email'];
         $timestamp = date("YmdHis");
         $directory = "../../images/prescriptions/" . $email . "-" . $timestamp;

         // Create the directory
         if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
         }

         // Loop through uploaded files
         $fileCount = count($_FILES['prescription']['name']);
         for ($i = 0; $i < $fileCount; $i++) {
            $fileTmpPath = $_FILES['prescription']['tmp_name'][$i];
            $fileName = ($i + 1) . '.' . pathinfo($_FILES['prescription']['name'][$i], PATHINFO_EXTENSION);
            $destPath = $directory . "/" . $fileName;

            // Move the file to the target directory
            if (move_uploaded_file($fileTmpPath, $destPath)) {
               echo "upload success";
            } else {
               echo "Error moving file " . $_FILES['prescription']['name'][$i];
            }
         }

         // Prepare the SQL statement
         $sql = "INSERT INTO prescription_and_address (pharmacy_id, customer_id, email, images, note, delivery_address, delivery_time_1, delivery_time_2) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

         // Prepare and bind
         $stmt = $conn->prepare($sql);
         $stmt->bind_param("iissssss", $pharmacy_id, $customer_id, $email, $directory, $note, $address, $delivery_time_1, $delivery_time_2);

         // Execute the statement
         if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
         }

         // Close the statement
         $stmt->close();


         include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";
         // Select data from member_fees table
         $sql = "SELECT `email`, `name` FROM `pharmacy_details` WHERE pharmacy_id = '$pharmacy_id'";
         $result = mysqli_query($conn, $sql);

         if ($result && mysqli_num_rows($result) > 0) {
            // Loop through query results
            while ($row = mysqli_fetch_assoc($result)) {

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
                  $mail->addAddress($row['email'], $row['name']);

                  $mail->isHTML(true);
                  $mail->Subject = 'New prescription submission';
                  $mail->Body    = "New prescription submission from $email";
                  $mail->AltBody = "New prescription submission from $email";

                  $mail->send();
                  echo 'Message has been sent';
               } catch (Exception $e) {
                  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
               }
            }
         } else {

            echo "no records"; 
     
         }
         $conn->close();




         $_SESSION['response'] = "Files and datas uploaded successfully.";
         header("location:new-prescription.php");
         exit;
      }

      // Close the connection
      $conn->close();
      ?>

      <!DOCTYPE html>
      <html lang="en">

      <head>
         <meta charset="UTF-8">
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">

         <!-- Bootstrap -->
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

         <!-- Font Awesome -->
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

         <!-- online fonts start -->
         <link href="https://db.onlinewebfonts.com/c/1f182a2cd2b60d5a6ac9667a629fbaae?family=PF+Din+Stencil+W01+Bold" rel="stylesheet">
         <!-- online fonts end -->

         <title>Customer apply</title>
      </head>

      <body>
         <?php
         include '../../components/navbar/navbar.php';
         ?>

         <div class="container" style="margin-top:93px;">



            <div class="text-center mb-4">
               <h3>Upload Prescription</h3>

            </div>

            <div class="container d-flex justify-content-center">
               <form method="post" id="uploadForm" enctype="multipart/form-data" style="width:50vw; min-width:300px;">

                  <div class="mb-3">
                     <label class="form-label">Select pharmacy:</label>
                     <select class="form-select" name="pharmacy_id">
                        <option value=""> Select </option>
                        <?php
                        include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";
                        // Select data from member_fees table
                        $sql = "SELECT `name`, `pharmacy_id` FROM `pharmacy_details` ORDER BY `pharmacy_id` DESC";
                        $result = mysqli_query($conn, $sql);

                        if ($result && mysqli_num_rows($result) > 0) {
                           // Loop through query results
                           while ($row = mysqli_fetch_assoc($result)) {

                        ?>
                              <option value="<?= $row['pharmacy_id']; ?>"> <?= $row['name']; ?> </option>

                           <?php
                           }
                        } else {
                           ?>
                           <option value="">no records</option>
                        <?php

                        }
                        $conn->close();
                        ?>
                     </select>
                  </div>

                  <div class="mb-3">
                     <label class="form-label">Upload prescription:</label>
                     <input class="form-control" type="file" id="prescription" name="prescription[]" accept=".jpg, .jpeg, .png" multiple required>
                  </div>

                  <script>
                     document.getElementById('uploadForm').addEventListener('submit', function(event) {
                        var fileInput = document.getElementById('picture');
                        var files = fileInput.files;

                        if (files.length > 5) {
                           alert("You can only upload a maximum of 5 files.");
                           event.preventDefault(); // Prevent the form from submitting
                        }
                     });
                  </script>

                  <div class="mb-3" style="display:none;">
                     <?php
                     include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";
                     // Select data from member_fees table
                     $email = $_SESSION["email"];
                     echo $email;
                     $sql = "SELECT `customer_id` FROM `customer_details` WHERE email = '$email'";
                     $result = mysqli_query($conn, $sql);

                     if ($result && mysqli_num_rows($result) > 0) {
                        // Loop through query results
                        while ($row = mysqli_fetch_assoc($result)) {

                     ?>
                           <input type="text" class="form-control" name="customer_id" placeholder="eg:name@example.com" value="<?= $row["customer_id"] ?>">

                     <?php
                        }
                     } else {

                        echo "no value found";
                     }
                     $conn->close();
                     ?>
                  </div>

                  <div class="mb-3">
                     <label class="form-label">Note:</label>
                     <textarea class="form-control" name="note" placeholder="eg: Need extra drug" required></textarea>
                  </div>



                  <div class="mb-3">
                     <label class="form-label">Address:</label>
                     <input type="text" class="form-control" name="address" placeholder="eg:no 3 sahivu road kalmunai-4" required>
                  </div>

                  <div class="mb-3">
                     <label class="form-label">Delivery time 1:</label>
                     <input type="datetime-local" class="form-control" name="delivery_time_1" required>
                  </div>

                  <div class="mb-3">
                     <label class="form-label">Delivery time 2:</label>
                     <input type="datetime-local" class="form-control" name="delivery_time_2" required>
                  </div>

                  

                  <div class="mb-3">
                     <button id="submitButton" type="submit" class="btn btn-success" name="submit">Apply</button>
                     <a href="admin-dashbord.php" class="btn btn-danger ">Cancel</a>
                  </div>
               </form>

            </div>
         </div>


         <!-- Bootstrap -->
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

      </body>

      </html>
<?php
   } else {
      header("Location: ../customer-dashbord/customer-dashbord.php");
   }
} else {
   header("Location: ../../index.php");
}
?>