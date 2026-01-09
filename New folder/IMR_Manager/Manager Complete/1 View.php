<?php
session_start();
include 'db_connection.php';

// Get dashboard statistics
$total_customers = 0;
$total_meters = 0;
$total_complaints = 0;
$pending_complaints = 0;

// Count customers
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM Customers");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_customers = $row['count'];
}

// Count meters
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM Meters");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_meters = $row['count'];
}

// Count total complaints
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM Complaints");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_complaints = $row['count'];
}

// Count pending complaints
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM Complaints WHERE status = 'Pending'");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $pending_complaints = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="manager_styles.css" />
    <style>
        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-box {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: white;
            border: 2px solid rgba(255,255,255,0.2);
        }
        .stat-box h3 {
            margin: 0;
            font-size: 36px;
            color: #4CAF50;
        }
        .stat-box p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.8;
        }
        .center-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        .btn {
            padding: 15px 25px;
            background: rgba(76, 175, 80, 0.8);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: rgba(76, 175, 80, 1);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
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

        <button class="back-btn" onclick="alert('You are on the main dashboard')">Back</button>
    </div>

    <div class="main-content">
        <div class="card">
            <h2 style="color: white; margin-bottom: 20px;">Manager Dashboard</h2>
            
            <!-- Dashboard Statistics -->
            <div class="stats-container">
                <div class="stat-box">
                    <h3><?php echo $total_customers; ?></h3>
                    <p>Total Customers</p>
                </div>
                <div class="stat-box">
                    <h3><?php echo $total_meters; ?></h3>
                    <p>Total Meters</p>
                </div>
                <div class="stat-box">
                    <h3><?php echo $total_complaints; ?></h3>
                    <p>Total Complaints</p>
                </div>
                <div class="stat-box">
                    <h3 style="color: #ffc107;"><?php echo $pending_complaints; ?></h3>
                    <p>Pending Complaints</p>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="center-buttons">
                <button class="btn" onclick="location.href='2 View.php'">User Information</button>
                <button class="btn" onclick="location.href='3 View.php'">All Meters</button>
                <button class="btn" onclick="location.href='4 View.php'">Complain</button>
                <button class="btn" onclick="location.href='5 View.php'">Reply</button>
                <button class="btn" onclick="location.href='6 View.php'">Admin Information</button>
                <button class="btn" onclick="location.href='7 View.php'">Officer Information</button>
            </div>
        </div>
    </div>

    <script src="manager_script.js"></script>
</body>
</html>
