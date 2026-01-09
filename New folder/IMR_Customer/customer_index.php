<?php
session_start();

if (!isset($_SESSION['user_nic']) || !isset($_SESSION['user_meter'])) {
    header("Location: login.php");
    exit;
}

$nic   = $_SESSION['user_nic'];
$meter = $_SESSION['user_meter'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>customer-Bills Management</title>
    <link rel="stylesheet" href="customer_styles.css" />
</head>

<body class="page_1">
    <div class="sidebar">
        <h1>Bills</h1>
        <div class="bills-section">
            <div class="bill-item">
                <span>Water 💧</span>
                <div class="toggle"></div>
            </div>
            <div class="bill-item">
                <span>Electricity ⚡</span>
                <div class="toggle"></div>
            </div>
            <div class="bill-item">
                <span>Gas 💨</span>
                <div class="toggle"></div>
            </div>
        </div>
<?php
session_start();

if (!isset($_SESSION['user_nic']) || !isset($_SESSION['user_meter'])) {
    header("Location: login.php");
    exit;
}

$nic   = $_SESSION['user_nic'];
$meter = $_SESSION['user_meter'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>customer-Bills Management</title>
    <link rel="stylesheet" href="customer_styles.css" />
</head>

<body class="page_1">
    <div class="sidebar">
        <h1>Bills</h1>
        <div class="bills-section">
            <div class="bill-item">
                <span>Water 💧</span>
                <div class="toggle" data-type="water"></div>
            </div>
            <div class="bill-item">
                <span>Electricity ⚡</span>
                <div class="toggle" data-type="electricity"></div>
            </div>
            <div class="bill-item">
                <span>Gas 💨</span>
                <div class="toggle" data-type="gas"></div>
            </div>
        </div>

        <button class="back-btn">Back</button>
    </div>

    <div class="main-content">
        <div class="card_1">
            <div class="center-buttons">
                <a href="customer_showbill.php?nic=<?php echo urlencode($nic); ?>&meter=<?php echo urlencode($meter); ?>" 
                   id="showBillBtn" 
                   class="btn">Show Bill</a>

                <a href="customer_payhis.php?nic=<?php echo urlencode($nic); ?>&meter=<?php echo urlencode($meter); ?>" class="btn">Payment History</a>
                <a href="customer_comprep.php?nic=<?php echo urlencode($nic); ?>&meter=<?php echo urlencode($meter); ?>" class="btn">Complaint Report</a>               
            </div>
        </div>
    </div>

</body>
</html>

        <button class="back-btn">Back</button>
    </div>

    <div class="main-content">
        <div class="card_1">
            <div class="center-buttons">
                <a href="customer_showbill.php?nic=<?php echo $_SESSION['nic']; ?>&meter=<?php echo $_SESSION['meter_no']; ?>" class="btn">Show Bill</a>
                <a href="customer_payhis.php?nic=<?php echo $_SESSION['nic']; ?>&meter=<?php echo $_SESSION['meter_no']; ?>" class="btn">Payment History</a>
                <a href="customer_comprep.php?nic=<?php echo $_SESSION['nic']; ?>&meter=<?php echo $_SESSION['meter_no']; ?>" class="btn">Complaint Report</a>               
            </div>
        </div>
    </div>

    <script src="customer_script.js"></script>
</body>
</html>