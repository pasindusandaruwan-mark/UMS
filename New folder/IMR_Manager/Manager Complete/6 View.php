<?php
include 'db_connection.php';

// Initialize variables
$admin_data = null;
$all_admins = [];
$error = "";

// Handle form submission to search admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show'])) {
    $nic = mysqli_real_escape_string($conn, $_POST['nic']);
    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    
    if (!empty($nic) || !empty($admin_id)) {
        // Build query based on what's provided
        $sql = "SELECT * FROM Users WHERE role = 'Admin'";
        
        if (!empty($nic) && !empty($admin_id)) {
            $sql .= " AND NIC_umber = '$nic' AND user_id = $admin_id";
        } elseif (!empty($nic)) {
            $sql .= " AND NIC_umber = '$nic'";
        } elseif (!empty($admin_id)) {
            $sql .= " AND user_id = $admin_id";
        }
        
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $admin_data = mysqli_fetch_assoc($result);
        } else {
            $error = "No admin found with the provided details!";
        }
    } else {
        $error = "Please enter at least NIC or Admin ID!";
    }
}

// Handle show all admins
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['click'])) {
    $sql = "SELECT * FROM Users WHERE role = 'Admin' ORDER BY user_id DESC";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $all_admins[] = $row;
        }
    } else {
        $error = "No admins found in the system!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills Management - Admin Information</title>
    <link rel="stylesheet" href="manager_styles.css" />
    <style>
        .info-display {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            color: white;
        }
        .info-display h3 {
            color: white;
            margin-bottom: 15px;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding-bottom: 10px;
        }
        .info-display p {
            line-height: 2;
            margin: 10px 0;
        }
        .admin-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .admin-table th, .admin-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: white;
        }
        .admin-table th {
            background: rgba(255,255,255,0.2);
            font-weight: bold;
        }
        .admin-table tr:hover {
            background: rgba(255,255,255,0.05);
        }
        .status-active { color: #28a745; }
        .status-inactive { color: #dc3545; }
        .status-suspended { color: #ffc107; }
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
            <div class="menu-item" onclick="location.href='4 View.php'">Complain</div>
            <div class="menu-item" onclick="location.href='5 View.php'">Reply</div>
            <div class="menu-item active" onclick="location.href='6 View.php'">Admin Information</div>
            <div class="menu-item" onclick="location.href='7 View.php'">Officer Information</div>
        </div>

        <button class="back-btn" onclick="location.href='1 View.php'">Back</button>
    </div>

    <div class="main-content">
        <div class="card">
            <form method="POST" action="">
                <div class="form-group">
                    <label>NIC :</label>
                    <input type="text" name="nic" placeholder="Enter NIC (Optional)">
                </div>
                <div class="form-group">
                    <label>Admin ID :</label>
                    <input type="text" name="admin_id" placeholder="Enter Admin ID (Optional)">
                </div>
                
                <button type="submit" name="show" class="show-btn">SHOW</button>
            </form>

            <?php if ($error): ?>
                <div style="color: red; margin-top: 20px; text-align: center;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($admin_data): ?>
                <div class="info-display">
                    <h3>Admin Information</h3>
                    <p><strong>User ID:</strong> <?php echo $admin_data['user_id']; ?></p>
                    <p><strong>Name:</strong> <?php echo $admin_data['name']; ?></p>
                    <p><strong>Address:</strong> <?php echo $admin_data['address']; ?></p>
                    <p><strong>Phone Number:</strong> <?php echo $admin_data['phone_number']; ?></p>
                    <p><strong>Email:</strong> <?php echo $admin_data['email']; ?></p>
                    <p><strong>NIC Number:</strong> <?php echo $admin_data['NIC_umber']; ?></p>
                    <p><strong>Role:</strong> <?php echo $admin_data['role']; ?></p>
                    <p><strong>Status:</strong> 
                        <span class="status-<?php echo strtolower($admin_data['status']); ?>">
                            <?php echo $admin_data['status']; ?>
                        </span>
                    </p>
                    <p><strong>Created Date:</strong> <?php echo $admin_data['created_date']; ?></p>
                </div>
            <?php endif; ?>

            <div class="complaints-section">
                <div class="complaints-header">Show All Admin Informations</div>
                <form method="POST" action="">
                    <button type="submit" name="click" class="click-btn">Click</button>
                </form>
            </div>

            <?php if (!empty($all_admins)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>NIC</th>
                            <th>Status</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_admins as $admin): ?>
                            <tr>
                                <td><?php echo $admin['user_id']; ?></td>
                                <td><?php echo $admin['name']; ?></td>
                                <td><?php echo $admin['phone_number']; ?></td>
                                <td><?php echo $admin['email']; ?></td>
                                <td><?php echo $admin['NIC_umber']; ?></td>
                                <td class="status-<?php echo strtolower($admin['status']); ?>">
                                    <?php echo $admin['status']; ?>
                                </td>
                                <td><?php echo $admin['created_date']; ?></td>
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
