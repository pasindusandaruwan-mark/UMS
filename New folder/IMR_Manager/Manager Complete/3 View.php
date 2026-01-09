<?php
include 'db_connection.php';

$meters = [];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show'])) {
    $sql = "SELECT m.*, c.name AS customer_name, c.nic, s.service_name 
            FROM Meters m 
            LEFT JOIN Customers c ON m.customer_id = c.customer_id 
            LEFT JOIN Services s ON m.service_id = s.service_id 
            ORDER BY m.meter_id DESC";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $meters[] = $row;
        }
    } else {
        $error = "No meters found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Meters</title>
    <link rel="stylesheet" href="manager_styles.css" />
    <style>
        .meter-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .meter-table th, .meter-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: white;
        }
        .meter-table th {
            background: rgba(255,255,255,0.2);
            font-weight: bold;
        }
        .meter-table tr:hover {
            background: rgba(255,255,255,0.05);
        }
    </style>
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
            <div class="menu-item" onclick="location.href='2 View.php'">User Information</div>
            <div class="menu-item active" onclick="location.href='3 View.php'">All Meters</div>
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
                <button type="submit" name="show" class="show-btn">SHOW ALL METERS</button>
            </form>

            <?php if ($error): ?>
                <div style="color: red; margin-top: 20px; text-align: center;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($meters)): ?>
                <table class="meter-table">
                    <thead>
                        <tr>
                            <th>Meter ID</th>
                            <th>Meter Number</th>
                            <th>Customer</th>
                            <th>NIC</th>
                            <th>Service</th>
                            <th>Install Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($meters as $meter): ?>
                            <tr>
                                <td><?php echo $meter['meter_id']; ?></td>
                                <td><?php echo $meter['meter_number']; ?></td>
                                <td><?php echo $meter['customer_name']; ?></td>
                                <td><?php echo $meter['nic']; ?></td>
                                <td><?php echo $meter['service_name']; ?></td>
                                <td><?php echo $meter['install_date']; ?></td>
                                <td><?php echo $meter['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script src="manager_script.js"></script>
</body>
</html>
