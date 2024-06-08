<?php
session_start();
?>
<?php if (isset($_SESSION["username"]) && $_SESSION["privilage"] === "customer") { ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Prescription status</title>



        <link rel="stylesheet" href="style.css">


        <!-- bootstarp start -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <!-- bootstrap end -->

        <!-- online fonts start -->
        <link href="https://db.onlinewebfonts.com/c/1f182a2cd2b60d5a6ac9667a629fbaae?family=PF+Din+Stencil+W01+Bold" rel="stylesheet">
        <!-- online fonts end -->

        <!-- Goolge fonts start -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Protest+Riot&display=swap" rel="stylesheet">
        <!-- Goolge fonts end -->

        <!-- AOS  start-->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <!-- AOS  end-->

        <!-- Font Awesome start-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Font Awesome end-->





    </head>

    <body>

        <div class="home">

            <!-- Navbar start -->
            <?php
            include '../../components/navbar/navbar.php';
            ?>
            <!-- Navbar end -->

            <!-- admin-dashbord-start -->
            <div class="admin-dashbord container">

                <!-- tittle start -->
                <div class="admin-dashbord-tittle mb-4" style="position: relative;">
                    <P class="" style=" margin-bottom: 0 !important;">Prescription Status</P>
                    <div class="time" style=" position: absolute; right: 0%;">
                        <?php
                        // Set the default timezone
                        date_default_timezone_set('Asia/Colombo');

                        // Get the current date and time
                        $currentDateTime = date('Y-m-d H:i:s');

                        // Display the current date and time
                        echo $currentDateTime;
                        ?>
                    </div>
                </div>
                <!-- Tittle end -->

                <!-- AOS script start -->
                <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
                <script>
                    AOS.init();
                </script>
                <!-- AOS script end-->





                <!-- update payment status. start -->
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["member_id"]) && isset($_POST["status"])) {
                    include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-2/db_conn.php"; // Include database connection file
                    $prescriptionId = $_POST["prescription_id"];
                    $status = $_POST["status"];

                    // Prepare and execute SQL statement to update payment status
                    $sql = "UPDATE quotation SET user_acceptence = '$status' WHERE prescription_id = '$prescriptionId'";

                    if ($conn->query($sql) === TRUE) {
                        $_SESSION['response'] = "Payment status updated successfully."; // Store success message in session
                    } else {
                        $_SESSION['response'] = "Error updating payment status: " . $conn->error; // Store error message in session
                    }

                    exit();
                }
                ?>
                <!-- update payment status. end -->

                <!-- scroll to same position when reload. start -->
                <script>
                    // Function to save scroll positions
                    function saveScrollPositions() {
                        var childScrollPosTop = document.getElementById('childScroll').scrollTop;
                        var childScrollPosLeft = document.getElementById('childScroll').scrollLeft;

                        localStorage.setItem('childScrollPosTop', childScrollPosTop);
                        localStorage.setItem('childScrollPosLeft', childScrollPosLeft);
                    }

                    // Function to restore scroll positions
                    function restoreScrollPositions() {
                        console.log("Restoring scroll positions...");
                        var childScrollPosTop = localStorage.getItem('childScrollPosTop');
                        var childScrollPosLeft = localStorage.getItem('childScrollPosLeft');

                        console.log("Retrieved child scroll positions - Top:", childScrollPosTop, "Left:", childScrollPosLeft);

                        if (childScrollPosTop !== null && childScrollPosLeft !== null) {
                            document.getElementById('childScroll').scrollTop = childScrollPosTop;
                            document.getElementById('childScroll').scrollLeft = childScrollPosLeft;
                            console.log("Scroll positions restored successfully.");
                        } else {
                            console.log("No scroll positions found in localStorage.");
                        }
                    }

                    // Call the restoreScrollPositions function when the page loads
                    window.onload = function() {
                        restoreScrollPositions();
                    };
                </script>
                <!-- scroll to same position when reload. end -->


                <div class="container-2" id="childScroll" onscroll="saveScrollPositions()">




                    <table class="table table-hover text-center ">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Prescription ID</th>
                                <th scope="col">Pharmacy Name</th>
                                <th scope="col">Note</th>
                                <th scope="col">Delivery address</th>
                                <th scope="col">Delivery Time 1</th>
                                <th scope="col">Delivery Time 2</th>
                                <th scope="col">View Quotation</th>
                                <th scope="col">Accept/Reject</th>

                                <!-- <th scope="col" class="col-remove">Edit</th>
                                <th scope="col" class="col-remove">Delete</th> -->
                            </tr>
                        </thead>

                        <!-- php database start -->

                        <tbody>
                            <?php
                            include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";
                            $email = $_SESSION['email'];
                            
                            $sql = "SELECT p.*, d.name
                                    FROM `prescription_and_address` p
                                    JOIN `pharmacy_details` d ON p.pharmacy_id = d.pharmacy_id
                                    WHERE p.email = '$email'
                                    ORDER BY p.prescription_id DESC";

                            $result = mysqli_query($conn, $sql);

                         


                            while ($row = mysqli_fetch_assoc($result)) {
                                $prescriptionId = $row["prescription_id"];
                                $images = $row["images"];

                                // Fetch user_acceptence for the current prescription_id
                                $sql = "SELECT user_acceptence
                                        FROM quotation
                                        WHERE prescription_id = ? 
                                        LIMIT 1";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $prescriptionId);
                                $stmt->execute();
                                $userAcceptanceResult = $stmt->get_result();

                                // Check if user_acceptence is found
                                if ($userAcceptanceRow = $userAcceptanceResult->fetch_assoc()) {
                                    $userAcceptance = $userAcceptanceRow["user_acceptence"];
                                } else {
                                    $userAcceptance = "Not Available"; // Or any default value you prefer
                                }

                            ?>
                                <tr>
                                    <td>
                                        <?php echo $row["prescription_id"]; ?>
                                    </td>

                                    <td>
                                        <?php echo $row["name"] ?>
                                    </td>
                                    <td>
                                        <?php echo $row["note"] ?>
                                    </td>
                                    <td>
                                        <?php echo $row["delivery_address"] ?>
                                    </td>
                                    <td>
                                        <?php echo $row["delivery_time_1"] ?>
                                    </td>
                                    <td>
                                        <?php echo $row["delivery_time_2"] ?>
                                    </td>
                                    <td>
                                        <a href="quotation.php?prescription_id=<?= $row["prescription_id"] ?>&images=<?= $row["images"] ?>">View</a>
                                    </td>

                                    <td>
                                        <?=$userAcceptance?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- php database end -->


                </div>





            </div>
            <!-- admin-dashbord-end -->


            <!-- Bootstrap js start -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
            <!-- Bootstrap js end-->

        </div>


    </body>

    </html>
<?php } else {
    header("Location: ../../../index.php");
} ?>