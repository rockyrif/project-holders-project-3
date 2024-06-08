<?php
session_start();


if ((!isset($_SESSION["id"]) && isset($_SESSION["username"])) || $_SESSION["privilage"] == "pharmacist") {
   include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";

   // The value you are looking for
   $search_value = $_SESSION["email"];

   // The SQL query to check for the specific data
   $sql = "SELECT * FROM pharmacy_details WHERE email = ?";

   // Prepare and bind
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("s", $search_value);

   // Execute the statement
   $stmt->execute();

   // Store the result
   $stmt->store_result();

   if (!$stmt->num_rows > 0) {
?>

      <?php
      include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";
      if (isset($_POST["submit"])) {


         $name = $_POST['name'];

         $email = $_SESSION["email"];

         $address = $_POST['address'];


         $sql = "INSERT INTO `pharmacy_details`(`name`, `email`, `address`) 
        VALUES (?, ?, ?)";

         $stmt = $conn->prepare($sql);

         if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
         }

         // Bind the parameters
         $stmt->bind_param('sss', $name, $email, $address);

         // Execute the statement
         if ($stmt->execute()) {

            $_SESSION['response'] = "Successfull";
            // header("Location: ../../index.php");
            exit;
         } else {
            $_SESSION['response'] = "Error: " . htmlspecialchars($stmt->error);
         }

         $stmt->close();
         $conn->close();
      }
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

         <title>Pharmacist apply</title>
      </head>

      <body>
         <?php
         include '../../components/navbar/navbar.php';
         ?>

         <div class="container" style="margin-top:93px;">



            <div class="text-center mb-4">
               <h3>Become a pharmacist</h3>
               <p class="text-muted">Complete the form below to become a pharmacist</p>
            </div>

            <div class="container d-flex justify-content-center">
               <form method="post" enctype="multipart/form-data" style="width:50vw; min-width:300px;">

                  <div class="mb-3">
                     <label class="form-label">Name of pharmacy:</label>
                     <input type="text" class="form-control" name="name" placeholder="Harcourts" required>
                  </div>

                  <div class="mb-3" style="display:none;">
                     <label class="form-label">Email:</label>
                     <input type="email" class="form-control" name="email" placeholder="name@example.com" value="<?= (isset($_SESSION['email'])) ?  $_SESSION["email"] : ''; ?>">
                  </div>

                  <div class="mb-3">
                     <label class="form-label">Pharmacy Adress:</label>
                     <input type="text" class="form-control" name="address" placeholder="no 3 sahivu road kalmunai-4" required>
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
      header("Location: ../pharmacist-dashbord/prescription-status.php");
   }
} else {
   header("Location: ../../index.php");
}
?>