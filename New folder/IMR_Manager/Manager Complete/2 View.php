<?php
include 'db_connection.php';

$customer_data = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show'])) {
    $nic = mysqli_real_escape_string($conn, $_POST['nic']);
    
    if (!empty($nic)) {
        $sql = "SELECT * FROM Customers WHERE nic = '$nic'";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $customer_data = mysqli_fetch_assoc($result);
        } else {
            $error = "No customer found with this NIC number!";
        }
    } else {
        $error = "Please enter a NIC number!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="manager_styles.css" />
</head>
<body>
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
                <span>Air 💨</span>
                <div class="toggle"></div>
            </div>
        </div>

        <div class="menu-section">
            <div class="menu-item active" onclick="location.href='2 View.php'">User Information</div>
            <div class="menu-item" onclick="location.href='3 View.php'">All Meters</div>
            <div class="menu-item" onclick="location.href='4 View.php'">Complain</div>
            <div class="menu-item" onclick="location.href='5 View.php'">Reply</div>
            <div class="menu-item" onclick="location.href='6 View.php'">Admin Information</div>
            <div class="menu-item" onclick="location.href='7 View.php'">Officer Information</div>
        </div>

        <button class="back-btn" onclick="location.href='1 View.php'">Back</button>
    </div>

    <div class="main-content">
        <div class="card">
            <form method="POST" action="">
                <div class="form-group">
                    <label>NIC :</label>
                    <input type="text" name="nic" placeholder="Enter NIC Number" required>
                </div>
                
                <button type="submit" name="show" class="show-btn">SHOW</button>
            </form>

            <?php if ($error): ?>
                <div style="color: red; margin-top: 20px; text-align: center;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($customer_data): ?>
                <div style="margin-top: 30px; padding: 20px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                    <h3 style="color: white; margin-bottom: 15px;">Customer Information</h3>
                    <div style="color: white; line-height: 2;">
                        <p><strong>Customer ID:</strong> <?php echo $customer_data['customer_id']; ?></p>
                        <p><strong>Name:</strong> <?php echo $customer_data['name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $customer_data['email']; ?></p>
                        <p><strong>Phone:</strong> <?php echo $customer_data['phone']; ?></p>
                        <p><strong>NIC:</strong> <?php echo $customer_data['nic']; ?></p>
                        <p><strong>Address:</strong> <?php echo $customer_data['address']; ?></p>
                        <p><strong>Status:</strong> <?php echo $customer_data['status']; ?></p>
                        <p><strong>Registered:</strong> <?php echo $customer_data['created_at']; ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="manager_script.js"></script>
</body>
</html>
