<?php
session_start(); // Start the session

$prescription_id = $_GET['prescription_id'] ?? null;


// Image handling
$images_param = $_GET['images'] ?? null;
$location = $images_param . '/';

$images = [];
for ($i = 1; $i <= 5; $i++) {
    $file = $location . $i . '.jpg';
    if (file_exists($file)) {
        $images[] = $file;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Quotation</title>
    <link rel="stylesheet" href="style.css">
</head>


<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f4f4f9;
    }

    .container1 {
        display: flex;
        flex-direction: column;
        width: 90%;
        max-width: 1200px;
        background-color: #ffffff;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        padding: 20px;
    }

    .prescription-section,
    .quotation-section {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .prescription-image {
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 10px;
        margin-bottom: 10px;
        padding: 10px;
    }

    .prescription-image img {
        max-width: 40%;
        border-radius: 10px;
    }

    .thumbnail-images {
        display: flex;
        justify-content: space-around;
    }

    .thumbnail-images img {
        width: 50px;
        height: 50px;
        cursor: pointer;
        border: 1px solid #ddd;
        border-radius: 5px;
        transition: transform 0.2s;
    }

    .thumbnail-images img:hover {
        transform: scale(1.1);
    }

    .quotation-section table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .quotation-section th,
    .quotation-section td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    .quotation-section th {
        background-color: #f4f4f9;
    }

    .add-drug-form {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .add-drug-form input {
        width: 30%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .add-drug-form button {
        padding: 10px;
        border: none;
        background-color: #007bff;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .add-drug-form button:hover {
        background-color: #0056b3;
    }

    .send-quotation {
        padding: 10px;
        border: none;
        background-color: #28a745;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .send-quotation:hover {
        background-color: #218838;
    }

    .remove-drug-button {
        padding: 5px 10px;
        border: none;
        background-color: #dc3545;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .remove-drug-button:hover {
        background-color: #c82333;
    }
</style>


<body>
    <div class="container1">
        <?php if (!empty($images)) : ?>
            <div class="prescription-section">
                <div class="prescription-image">
                    <img src="<?php echo $images[0]; ?>" alt="Prescription" id="mainImage">
                </div>
                <div class="thumbnail-images">
                    <?php foreach ($images as $image) : ?>
                        <img src="<?php echo $image; ?>" alt="Image" onclick="changeImage('<?php echo $image; ?>')">
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="quotation-section">
            <table id="quotationTable">
                <thead>
                    <tr>
                        <th>Drug</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Amount</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-3/db_conn.php";

                    if ($prescription_id) {
                        $sql = "SELECT * FROM `quotation` WHERE prescription_id = ? ORDER BY quotation_id ASC";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $prescription_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                    ?>

                                <tr>
                                    <td><?= $row['drug_name'] ?></td>
                                    <td><?= $row['quantity'] ?></td>
                                    <td><?= $row['unit_price'] ?></td>
                                    <td><?= $row['amount'] ?></td>

                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4">No quotation yet</td>
                                
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total</td>
                        <td id="totalAmount">0.00</td>
                    </tr>
                </tfoot>
            </table>
            
            <?php 
            $sql = "SELECT * FROM `quotation` WHERE prescription_id = ? ORDER BY quotation_id ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $prescription_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $conn->close();
            $stmt->close();
            ?>
            <button class="send-quotation" onclick="window.location.href='accept.php?quotation-id=<?=$row['quotation_id']?>'">Accept</button><br>
            
            <button class="send-quotation" onclick="window.location.href='reject.php?quotation-id=<?=$row['quotation_id']?>'">Reject</button>
        </div>
    </div>
    <script>
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
        }
        // Function to calculate the total amount
        function calculateTotalAmount() {
            const rows = document.querySelectorAll('#quotationTable tbody tr');
            let total = 0;

            rows.forEach(row => {
                const quantity = parseFloat(row.cells[1].textContent);
                const unitPrice = parseFloat(row.cells[2].textContent);
                const amount = quantity * unitPrice;
                row.cells[3].textContent = amount.toFixed(2); // Update amount cell with calculated amount
                total += amount;
            });

            document.getElementById('totalAmount').textContent = total.toFixed(2);
        }

        // Call the function to calculate the total amount on page load
        document.addEventListener('DOMContentLoaded', calculateTotalAmount);
    </script>
</body>

</html>