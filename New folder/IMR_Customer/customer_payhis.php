<?php
session_start();
include("includes/db_connect.php");

// Protect page – only logged-in customers can access
if (!isset($_SESSION['user_nic']) || !isset($_SESSION['user_meter'])) {
    header("Location: login.php");
    exit;
}

$billIdInput = $_GET['bill_id'] ?? '';
$payments    = [];
$error       = '';

if ($billIdInput !== '') {
    // basic validation
    $billId = (int)$billIdInput;

    if ($billId <= 0) {
        $error = "Please enter a valid bill number.";
    } else {
        // Get payment history for this bill, latest first
        $sql = "
            SELECT payment_id, bill_id, payment_date, amount_paid, payment_method
            FROM Payment
            WHERE bill_id = ?
            ORDER BY payment_date DESC, payment_id DESC;
        ";

        $stmt = sqlsrv_query($conn, $sql, [$billId]);

        if ($stmt === false) {
            $error = "Database error.";
            // You can uncomment this for debugging:
            // $error .= print_r(sqlsrv_errors(), true);
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $payments[] = $row;
            }
        }
    }
}
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
    <!--Left side bar-->
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

        <div class="menu-section">
            <div class="menu-item" onclick="location.href='customer_showbill.php'">Show Bill</div>
            <div class="menu-item active" onclick="location.href='customer_payhis.php'">Payment History</div>
            <div class="menu-item" onclick="location.href='customer_comprep.php'">Complaint Report</div>
        </div>

        <button class="back-btn" onclick="location.href='customer_index.php'">Back</button>
    </div>

    <!--Center main-->
    <div class="main-content">
        <div class="card_2">
            <!-- Input form -->
            <form method="GET" action="customer_payhis.php">
                <div class="form-group">
                    <label>Bill Number :</label>
                    <input 
                        type="number" 
                        name="bill_id" 
                        placeholder="Enter Bill num" 
                        required
                        value="<?php echo htmlspecialchars($billIdInput); ?>"
                    >
                </div>

                <button type="submit" class="show-btn">SHOW</button>
            </form>

            <!-- Error message -->
            <?php if ($error): ?>
                <p style="color: #ff8080; margin-top:15px;">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php endif; ?>

            <!-- Results table -->
            <?php if ($billIdInput !== '' && !$error): ?>
                <?php if (count($payments) > 0): ?>
                    <h3 style="color:white; margin-top:25px;">Payment History for Bill #<?php echo htmlspecialchars($billIdInput); ?></h3>
                    <table style="width:100%; margin-top:10px; border-collapse: collapse; color:white;">
                        <thead>
                            <tr>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid rgba(255,255,255,0.2);">Payment ID</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid rgba(255,255,255,0.2);">Date</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid rgba(255,255,255,0.2);">Amount Paid</th>
                                <th style="text-align:left; padding:8px; border-bottom:1px solid rgba(255,255,255,0.2);">Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $p): ?>
                                <tr>
                                    <td style="padding:6px 8px;">
                                        <?php echo htmlspecialchars($p['payment_id']); ?>
                                    </td>
                                    <td style="padding:6px 8px;">
                                        <?php
                                            if ($p['payment_date'] instanceof DateTime) {
                                                echo $p['payment_date']->format('Y-m-d');
                                            } else {
                                                echo htmlspecialchars($p['payment_date']);
                                            }
                                        ?>
                                    </td>
                                    <td style="padding:6px 8px;">
                                        Rs. <?php echo number_format($p['amount_paid'], 2); ?>
                                    </td>
                                    <td style="padding:6px 8px;">
                                        <?php echo htmlspecialchars($p['payment_method']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color:white; margin-top:20px;">
                        <strong>No payments found for this bill.</strong>
                    </p>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>

    <script src="customer_script.js"></script>
</body>
</html>
