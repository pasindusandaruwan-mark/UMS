<?php
include 'db_connection.php';

// Initialize variables
$complain_data = null;
$all_complains = [];
$error = "";
$success = "";

// Handle search by NIC and Meter Number
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show'])) {
    $nic = mysqli_real_escape_string($conn, $_POST['nic']);
    $meter_number = mysqli_real_escape_string($conn, $_POST['meter_number']);
    
    if (!empty($nic) && !empty($meter_number)) {
        // Query to get complains for specific customer and meter
        $sql = "SELECT comp.*, c.name AS customer_name, m.meter_number, m.utility_type 
                FROM Complaints comp
                LEFT JOIN Customer c ON comp.customer_id = c.customer_id
                LEFT JOIN Meter m ON comp.meter_id = m.meter_id
                WHERE c.NIC_umber = '$nic' AND m.meter_number = '$meter_number'
                ORDER BY comp.complain_date DESC";
        
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $complain_data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $complain_data[] = $row;
            }
        } else {
            $error = "No complains found for this customer and meter!";
        }
    } else {
        $error = "Please enter both NIC and Meter Number!";
    }
}

// Handle show all complains by date
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['click'])) {
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    
    if (!empty($date)) {
        // Query to get all complains for a specific date
        $sql = "SELECT comp.*, c.name AS customer_name, c.NIC_umber, m.meter_number, m.utility_type 
                FROM Complaints comp
                LEFT JOIN Customer c ON comp.customer_id = c.customer_id
                LEFT JOIN Meter m ON comp.meter_id = m.meter_id
                WHERE comp.complain_date = '$date'
                ORDER BY comp.complain_id DESC";
    } else {
        // Query to get all complains
        $sql = "SELECT comp.*, c.name AS customer_name, c.NIC_umber, m.meter_number, m.utility_type 
                FROM Complaints comp
                LEFT JOIN Customer c ON comp.customer_id = c.customer_id
                LEFT JOIN Meter m ON comp.meter_id = m.meter_id
                ORDER BY comp.complain_date DESC";
    }
    
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $all_complains[] = $row;
        }
    } else {
        $error = "No complains found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills Management - Complains</title>
    <link rel="stylesheet" href="manager_styles.css" />
    <style>
        .complain-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .complain-table th, .complain-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: white;
        }
        .complain-table th {
            background: rgba(255,255,255,0.2);
            font-weight: bold;
        }
        .complain-table tr:hover {
            background: rgba(255,255,255,0.05);
        }
        .status-pending { color: #ffc107; }
        .status-resolved { color: #28a745; }
        .status-inprogress { color: #17a2b8; }
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
            <div class="menu-item" onclick="location.href='3 View.php'">All Meters</div>
            <div class="menu-item active" onclick="location.href='4 View.php'">Complain</div>
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
                    <input type="text" name="nic" placeholder="Enter NIC">
                </div>
                <div class="form-group">
                    <label>Meter Number :</label>
                    <input type="text" name="meter_number" placeholder="Enter Meter Number">
                </div>
                
                <button type="submit" name="show" class="show-btn">SHOW</button>
            </form>

            <?php if ($complain_data): ?>
                <table class="complain-table">
                    <thead>
                        <tr>
                            <th>Complain ID</th>
                            <th>Customer</th>
                            <th>Meter</th>
                            <th>Utility Type</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($complain_data as $complain): ?>
                            <tr>
                                <td><?php echo $complain['complain_id']; ?></td>
                                <td><?php echo $complain['customer_name']; ?></td>
                                <td><?php echo $complain['meter_number']; ?></td>
                                <td><?php echo $complain['utility_type']; ?></td>
                                <td><?php echo $complain['complain_date']; ?></td>
                                <td><?php echo $complain['description']; ?></td>
                                <td class="status-<?php echo strtolower(str_replace(' ', '', $complain['status'])); ?>">
                                    <?php echo $complain['status']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <div class="complaints-section">
                <div class="complaints-header">Show All Complains</div>
                
                <form method="POST" action="">
                    <div class="date-group">
                        <label>Date :</label>
                        <input type="date" name="date" placeholder="Enter Date (Optional)">
                    </div>

                    <button type="submit" name="click" class="click-btn">Click</button>
                </form>
            </div>

            <?php if ($error): ?>
                <div style="color: red; margin-top: 20px; text-align: center;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($all_complains)): ?>
                <table class="complain-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>NIC</th>
                            <th>Meter</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_complains as $complain): ?>
                            <tr>
                                <td><?php echo $complain['complain_id']; ?></td>
                                <td><?php echo $complain['customer_name']; ?></td>
                                <td><?php echo $complain['NIC_umber']; ?></td>
                                <td><?php echo $complain['meter_number']; ?></td>
                                <td><?php echo $complain['utility_type']; ?></td>
                                <td><?php echo $complain['complain_date']; ?></td>
                                <td><?php echo $complain['description']; ?></td>
                                <td class="status-<?php echo strtolower(str_replace(' ', '', $complain['status'])); ?>">
                                    <?php echo $complain['status']; ?>
                                </td>
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
