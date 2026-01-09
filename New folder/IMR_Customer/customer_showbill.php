<?php
session_start();
include("includes/db_connect.php");

// Protect page – only logged-in customers can access
if (!isset($_SESSION['user_nic']) || !isset($_SESSION['user_meter'])) {
    header("Location: login.php");
    exit;
}

// Session values
$nic   = $_SESSION['user_nic'];
$meter = $_SESSION['user_meter'];

// Get type from URL (sent from customer_index.php)
$type = $_GET['type'] ?? '';

// Read month only after form submission
$monthInput = $_GET['month'] ?? null;

// If month is NOT submitted → show the form
if ($monthInput === null) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer-Bills Management</title>
    <link rel="stylesheet" href="customer_styles.css" />
</head>
<body class="page_2">

<div class="sidebar">
    <h1>Bills</h1>
    <div class="bills-section">
        <div class="bill-item"><span>Water 💧</span><div class="toggle"></div></div>
        <div class="bill-item"><span>Electricity ⚡</span><div class="toggle"></div></div>
        <div class="bill-item"><span>Gas 💨</span><div class="toggle"></div></div>
    </div>

    <div class="menu-section">
        <div class="menu-item active">Show Bill</div>
        <div class="menu-item" onclick="location.href='customer_payhis.php'">Payment History</div>
        <div class="menu-item" onclick="location.href='customer_comprep.php'">Complaint Report</div>
    </div>

    <button class="back-btn" onclick="location.href='customer_index.php'">Back</button>
</div>

<div class="main-content">
    <div class="card_2">
        <form method="GET" action="customer_showbill.php">
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">

            <div class="form-group">
                <label>Enter Month :</label>
                <input type="number" name="month" min="1" max="12" placeholder="1-12" required>
            </div>

            <button type="submit" class="show-btn">SHOW</button>
        </form>
    </div>
</div>

<script src="customer_script.js"></script>
</body>
</html>

<?php
exit;
}
?>

<?php
// ---------------------
// PART 2: SHOW THE BILL
// ---------------------

// Validate month number
$monthNumber = intval($monthInput);
if ($monthNumber < 1 || $monthNumber > 12) {
    die("<h2 style='color:white;'>Invalid month. Enter 1–12.</h2>");
}

// Choose table based on type
switch (strtolower($type)) {
    case 'water':
        $table    = 'WaterBills';
        $idColumn = 'wbill_id';
        break;
    case 'gas':
        $table    = 'AirBills';
        $idColumn = 'abill_id';
        break;
    case 'electricity':
        $table    = 'ElectricityBills';
        $idColumn = 'ebill_id';
        break;
    default:
        die("<h2 style='color:white;'>Invalid bill category selected.</h2>");
}

// Query bill
$sql = "
    SELECT TOP 1
        $idColumn AS bill_id,
        billing_month,
        due_date,
        total_amount,
        status,
        generated_date
    FROM $table
    WHERE meter_id = ?
      AND MONTH(billing_month) = ?
    ORDER BY billing_month DESC;
";

$params = [$meter, $monthNumber];
$stmt   = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$bill = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer-Bills Management</title>
    <link rel="stylesheet" href="customer_styles.css" />
</head>

<body class="page_2">
<div class="sidebar">
    <h1>Bills</h1>
</div>

<div class="main-content">
    <div class="card_2">

        <h2 style="color:white;">Bill Details</h2>
        <hr>

        <?php if ($bill) { ?>

            <p><strong>Category:</strong> <?php echo ucfirst($type); ?></p>
            <p><strong>Bill ID:</strong> <?php echo $bill['bill_id']; ?></p>

            <p><strong>Billing Month:</strong>
                <?php echo $bill['billing_month']->format('F Y'); ?>
            </p>

            <p><strong>Total Amount:</strong> Rs. <?php echo number_format($bill['total_amount'], 2); ?></p>
            <p><strong>Status:</strong> <?php echo $bill['status']; ?></p>

            <p><strong>Due Date:</strong>
                <?php echo $bill['due_date']->format('Y-m-d'); ?>
            </p>

            <p><strong>Generated Date:</strong>
                <?php echo $bill['generated_date']->format('Y-m-d'); ?>
            </p>

        <?php } else { ?>
            <p><strong>No bill found for this month and category.</strong></p>
        <?php } ?>

        <br>
        <button class="show-btn" onclick="location.href='customer_showbill.php?type=<?php echo urlencode($type); ?>'">Back</button>

    </div>
</div>

</body>
</html>
