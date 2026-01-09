<?php
session_start();
include("includes/db_connect.php");

// Protect page – only logged-in customers can access
if (!isset($_SESSION['user_nic']) || !isset($_SESSION['user_meter'])) {
    header("Location: login.php");
    exit;
}

// Session values
$nic   = $_SESSION['user_nic'];      // will be saved as customer_id
$meter = $_SESSION['user_meter'];    // will be saved as meter_id

// Type from customer_index.php (water/electricity/gas)
$type = $_GET['type'] ?? ($_POST['type'] ?? '');

// For messages
$success = '';
$error   = '';

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = trim($_POST['name'] ?? '');
    $contact   = trim($_POST['contact'] ?? '');
    $complaint = trim($_POST['complaint'] ?? '');

    if ($name === '' || $contact === '' || $complaint === '' || $type === '') {
        $error = "Please fill all fields.";
    } else {
        // Insert into Complaint table
        $sql = "
            INSERT INTO Complaint (meter_id, customer_id, name, contact_num, type, complaint_desc)
            VALUES (?, ?, ?, ?, ?, ?);
        ";

        $params = [$meter, $nic, $name, $contact, $type, $complaint];
        $stmt   = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            $error = "Database error while saving complaint.";
            // Uncomment for debugging:
            // $error .= print_r(sqlsrv_errors(), true);
        } else {
            $success = "Your complaint has been submitted successfully.";
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
            <div class="menu-item" onclick="location.href='customer_payhis.php'">Payment History</div>
            <div class="menu-item active" onclick="location.href='cu4.php'">Complaint Report</div>
        </div>

        <button class="back-btn" onclick="location.href='customer_index.php'">Back</button>
    </div>

    <!--Center main-->
    <div class="main-content">
        <div class="card_2">
            <form method="POST" action="">
                <!-- keep type so it survives POST -->
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">

                <div class="form-group">

                    <label>Name :</label>
                    <input 
                        type="text" 
                        name="name" 
                        placeholder="Enter Your Name"
                        value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                        required>
                    <br><br>

                    <label>Contact Number :</label>
                    <input 
                        type="text" 
                        name="contact" 
                        placeholder="Enter Number"
                        value="<?php echo htmlspecialchars($_POST['contact'] ?? ''); ?>"
                        required>
                    <br><br>

                    <!-- Complaint Message Box -->
                    <label>Complaint :</label>
                    <textarea 
                        name="complaint"
                        placeholder="Write your complaint here..." 
                        rows="5"
                        style="width: 100%; padding: 10px; border-radius: 5px; border: none; margin-top:10px;"
                        required><?php echo htmlspecialchars($_POST['complaint'] ?? ''); ?></textarea>

                </div>

                <button type="submit" class="show-btn">Submit</button>
            </form>

            <?php if ($success): ?>
                <p style="color:#90ee90; margin-top:15px;"><?php echo htmlspecialchars($success); ?></p>
            <?php elseif ($error): ?>
                <p style="color:#ff8080; margin-top:15px;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

        </div>
    </div>

    <script src="customer_script.js"></script>
</body>
</html>
