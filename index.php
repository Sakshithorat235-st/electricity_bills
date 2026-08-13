<?php
include "db.php";

$bill = 0;
$breakdown = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_name = $_POST["customer_name"];
    $units = $_POST["units"];

    if ($units <= 50) {

        $amount = $units * 3.50;
        $bill = $amount;

        $breakdown[] = ["First 50 units", $units, 3.50, $amount];

    } 
    elseif ($units <= 150) {

        $amount1 = 50 * 3.50;
        $amount2 = ($units - 50) * 4.00;

        $bill = $amount1 + $amount2;

        $breakdown[] = ["First 50 units", 50, 3.50, $amount1];
        $breakdown[] = ["Next 100 units", $units - 50, 4.00, $amount2];

    } 
    elseif ($units <= 250) {

        $amount1 = 50 * 3.50;
        $amount2 = 100 * 4.00;
        $amount3 = ($units - 150) * 5.20;

        $bill = $amount1 + $amount2 + $amount3;

        $breakdown[] = ["First 50 units", 50, 3.50, $amount1];
        $breakdown[] = ["Next 100 units", 100, 4.00, $amount2];
        $breakdown[] = ["Next 100 units", $units - 150, 5.20, $amount3];

    } 
    else {

        $amount1 = 50 * 3.50;
        $amount2 = 100 * 4.00;
        $amount3 = 100 * 5.20;
        $amount4 = ($units - 250) * 6.50;

        $bill = $amount1 + $amount2 + $amount3 + $amount4;

        $breakdown[] = ["First 50 units", 50, 3.50, $amount1];
        $breakdown[] = ["Next 100 units", 100, 4.00, $amount2];
        $breakdown[] = ["Next 100 units", 100, 5.20, $amount3];
        $breakdown[] = ["Above 250 units", $units - 250, 6.50, $amount4];
    }

    // Save into database
    $sql = "INSERT INTO bills
            (customer_name, units, bill_amount, payment_status)
            VALUES
            ('$customer_name', '$units', '$bill', 'Unpaid')";

    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Electricity Bill Calculator</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial;
    background: #eef2f7;
    padding: 30px;
}

.container {
    max-width: 750px;
    margin: auto;
    background: white;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 10px 30px #ccc;
}

h1 {
    text-align: center;
}

.subtitle {
    text-align: center;
    color: #666;
}

label {
    display: block;
    margin-top: 20px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 14px;
    margin-top: 8px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
}

button {
    width: 100%;
    padding: 14px;
    margin-top: 25px;
    border: none;
    border-radius: 8px;
    background: #2563eb;
    color: white;
    font-size: 17px;
    cursor: pointer;
}

button:hover {
    background: #1d4ed8;
}

.result {
    margin-top: 30px;
    padding: 25px;
    background: #f0fdf4;
    border-radius: 12px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
    background: #2563eb;
    color: white;
}

.total {
    text-align: right;
    font-size: 28px;
    font-weight: bold;
    margin-top: 20px;
}

.status {
    color: #dc2626;
    font-weight: bold;
}

@media(max-width:600px) {

    body {
        padding: 10px;
    }

    .container {
        padding: 20px;
    }

    table {
        font-size: 13px;
    }

}

</style>

</head>

<body>

<div class="container">

<h1>⚡ Electricity Bill Calculator</h1>

<p class="subtitle">
Calculate your electricity bill instantly
</p>

<form method="POST">

<label>Customer Name</label>

<input
type="text"
name="customer_name"
placeholder="Enter customer name"
required
>

<label>Units Consumed</label>

<input
type="number"
name="units"
placeholder="Enter units consumed"
min="1"
required
>

<button type="submit">
Calculate Bill
</button>

</form>


<?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>

<div class="result">

<h2>Bill Details</h2>

<p>
<strong>Customer:</strong>
<?php echo htmlspecialchars($customer_name); ?>
</p>

<p>
<strong>Units Consumed:</strong>
<?php echo $units; ?>
</p>

<table>

<tr>
<th>Slab</th>
<th>Units</th>
<th>Rate</th>
<th>Amount</th>
</tr>

<?php foreach ($breakdown as $row) { ?>

<tr>

<td><?php echo $row[0]; ?></td>

<td><?php echo $row[1]; ?></td>

<td>₹<?php echo number_format($row[2], 2); ?></td>

<td>₹<?php echo number_format($row[3], 2); ?></td>

</tr>

<?php } ?>

</table>

<div class="total">

Total: ₹<?php echo number_format($bill, 2); ?>

</div>

<p class="status">
Payment Status: UNPAID
</p>

</div>

<?php } ?>

</div>

</body>
</html>