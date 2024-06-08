<?php
session_start(); // Start the session

$prescription_id = $_GET['prescription_id'] ?? null;
$pharmacy_id = $_GET['pharmacy_id'] ?? null;
$customer_id = $_GET['customer_id'] ?? null;

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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Items will be added dynamically here -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Total</td>
                        <td id="totalAmount">0.00</td>
                    </tr>
                </tfoot>
            </table>
            <div class="add-drug-form">
                <input type="text" id="drugInput" placeholder="Drug">
                <input type="number" id="quantityInput" placeholder="Quantity">
                <input type="number" id="unitPriceInput" placeholder="Unit Price">
                <button onclick="addDrug()">Add</button>
            </div>
            <button class="send-quotation" onclick="sendQuotation()">Send Quotation</button>
        </div>
    </div>
    <script>
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
        }

        function addDrug() {
            const drugInput = document.getElementById('drugInput').value;
            const quantityInput = document.getElementById('quantityInput').value;
            const unitPriceInput = document.getElementById('unitPriceInput').value;

            if (drugInput && quantityInput && unitPriceInput) {
                const tableBody = document.querySelector('#quotationTable tbody');
                const row = document.createElement('tr');

                const drugCell = document.createElement('td');
                drugCell.textContent = drugInput;
                row.appendChild(drugCell);

                const quantityCell = document.createElement('td');
                quantityCell.textContent = quantityInput;
                row.appendChild(quantityCell);

                const unitPriceCell = document.createElement('td');
                unitPriceCell.textContent = parseFloat(unitPriceInput).toFixed(2);
                row.appendChild(unitPriceCell);

                const amountCell = document.createElement('td');
                const amount = calculateAmount(quantityInput, unitPriceInput);
                amountCell.textContent = amount.toFixed(2);
                row.appendChild(amountCell);

                const actionCell = document.createElement('td');
                const removeButton = document.createElement('button');
                removeButton.textContent = 'Remove';
                removeButton.className = 'remove-drug-button';
                removeButton.onclick = function() {
                    row.remove();
                    updateTotalAmount();
                };
                actionCell.appendChild(removeButton);
                row.appendChild(actionCell);

                tableBody.appendChild(row);

                updateTotalAmount();
            } else {
                alert('Please enter drug, quantity, and unit price.');
            }
        }

        function calculateAmount(quantity, unitPrice) {
            return parseFloat(unitPrice) * parseInt(quantity);
        }

        function updateTotalAmount() {
            const amounts = document.querySelectorAll('#quotationTable tbody tr td:nth-child(4)');
            let total = 0;
            amounts.forEach(amount => {
                total += parseFloat(amount.textContent);
            });
            document.getElementById('totalAmount').textContent = total.toFixed(2);
        }

        function sendQuotation() {
            // Create a form element
            const form = document.createElement('form');
            form.setAttribute('method', 'post');
            form.setAttribute('action', 'new-quotation.php');

            // Add prescription_id, pharmacy_id, and customer_id as hidden input fields
            const prescriptionInput = document.createElement('input');
            prescriptionInput.setAttribute('type', 'hidden');
            prescriptionInput.setAttribute('name', 'prescription_id');
            prescriptionInput.value = <?php echo $prescription_id; ?>;
            form.appendChild(prescriptionInput);

            const pharmacyInput = document.createElement('input');
            pharmacyInput.setAttribute('type', 'hidden');
            pharmacyInput.setAttribute('name', 'pharmacy_id');
            pharmacyInput.value = <?php echo $pharmacy_id; ?>;
            form.appendChild(pharmacyInput);

            const customerInput = document.createElement('input');
            customerInput.setAttribute('type', 'hidden');
            customerInput.setAttribute('name', 'customer_id');
            customerInput.value = <?php echo $customer_id; ?>;
            form.appendChild(customerInput);

            // Append each drug data as hidden input fields
            const rows = document.querySelectorAll('#quotationTable tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const drugName = cells[0].textContent;
                const quantity = cells[1].textContent;
                const unitPrice = cells[2].textContent;
                const amount = cells[3].textContent;

                const drugInput = document.createElement('input');
                drugInput.setAttribute('type', 'hidden');
                drugInput.setAttribute('name', 'drug_name[]');
                drugInput.value = drugName;
                form.appendChild(drugInput);

                const quantityInput = document.createElement('input');
                quantityInput.setAttribute('type', 'hidden');
                quantityInput.setAttribute('name', 'quantity[]');
                quantityInput.value = quantity;
                form.appendChild(quantityInput);

                const unitPriceInput = document.createElement('input');
                unitPriceInput.setAttribute('type', 'hidden');
                unitPriceInput.setAttribute('name', 'unit_price[]');
                unitPriceInput.value = unitPrice;
                form.appendChild(unitPriceInput);

                const amountInput = document.createElement('input');
                amountInput.setAttribute('type', 'hidden');
                amountInput.setAttribute('name', 'amount[]');
                amountInput.value = amount;
                form.appendChild(amountInput);
            });

            // Append the form to the document body and submit it
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html>