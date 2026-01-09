<?php
include 'db_connection.php';

// Initialize variables
$complain_details = null;
$error = "";
$success = "";

// Handle form submission to show complain details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show'])) {
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    if (!empty($phone)) {
        // Query to get pending complains for customer with this phone number
        $sql = "SELECT comp.*, c.name AS customer_name, c.phone_number, m.meter_number, m.utility_type 
                FROM Complaints comp
                LEFT JOIN Customer c ON comp.customer_id = c.customer_id
                LEFT JOIN Meter m ON comp.meter_id = m.meter_id
                WHERE c.phone_number = '$phone' AND comp.status = 'Pending'
                ORDER BY comp.complain_date DESC";
        
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $complain_details = [];
            while ($row = mysqli_fetch_assoc($result)) {
                // Get replies for this complain
                $complain_id = $row['complain_id'];
                $reply_sql = "SELECT r.*, u.name AS replied_by_name 
                             FROM Replies r 
                             LEFT JOIN Users u ON r.replied_by = u.user_id 
                             WHERE r.complain_id = $complain_id
                             ORDER BY r.reply_date DESC";
                $reply_result = mysqli_query($conn, $reply_sql);
                
                $row['replies'] = [];
                if ($reply_result && mysqli_num_rows($reply_result) > 0) {
                    while ($reply = mysqli_fetch_assoc($reply_result)) {
                        $row['replies'][] = $reply;
                    }
                }
                
                $complain_details[] = $row;
            }
        } else {
            $error = "No pending complains found for this phone number!";
        }
    } else {
        $error = "Please enter a phone number!";
    }
}

// Handle reply submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_reply'])) {
    $complain_id = mysqli_real_escape_string($conn, $_POST['complain_id']);
    $reply_message = mysqli_real_escape_string($conn, $_POST['reply_message']);
    $manager_id = 1; // Assuming manager user_id is 1, you should get this from session
    
    if (!empty($complain_id) && !empty($reply_message)) {
        $reply_date = date('Y-m-d');
        
        // Insert reply
        $sql = "INSERT INTO Replies (complain_id, reply_date, reply_message, replied_by) 
                VALUES ($complain_id, '$reply_date', '$reply_message', $manager_id)";
        
        if (mysqli_query($conn, $sql)) {
            // Update complain status
            $update_sql = "UPDATE Complaints SET status = 'In Progress' WHERE complain_id = $complain_id";
            mysqli_query($conn, $update_sql);
            
            $success = "Reply submitted successfully!";
        } else {
            $error = "Error submitting reply: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills Management - Reply to Complains</title>
    <link rel="stylesheet" href="manager_styles.css" />
    <style>
        .complain-card {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            color: white;
        }
        .complain-header {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .reply-section {
            background: rgba(255,255,255,0.05);
            padding: 15px;
            margin-top: 15px;
            border-radius: 5px;
        }
        .reply-item {
            background: rgba(255,255,255,0.1);
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 3px solid #4CAF50;
        }
        .reply-form textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: white;
            margin: 10px 0;
            min-height: 80px;
        }
        .submit-reply-btn {
            background: #4CAF50;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-reply-btn:hover {
            background: #45a049;
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
            <div class="menu-item" onclick="location.href='3 View.php'">All Meters</div>
            <div class="menu-item" onclick="location.href='4 View.php'">Complain</div>
            <div class="menu-item active" onclick="location.href='5 View.php'">Reply</div>
            <div class="menu-item" onclick="location.href='6 View.php'">Admin Information</div>
            <div class="menu-item" onclick="location.href='7 View.php'">Officer Information</div>
        </div>

        <button class="back-btn" onclick="location.href='1 View.php'">Back</button>
    </div>

    <div class="main-content">
        <div class="card">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Phone Number :</label>
                    <input type="text" name="phone" placeholder="Enter Phone Number" required>
                </div>
                
                <button type="submit" name="show" class="show-btn">GO</button>
            </form>

            <?php if ($error): ?>
                <div style="color: red; margin-top: 20px; text-align: center;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div style="color: #4CAF50; margin-top: 20px; text-align: center;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if ($complain_details): ?>
                <?php foreach ($complain_details as $complain): ?>
                    <div class="complain-card">
                        <div class="complain-header">
                            <h3>Complain #<?php echo $complain['complain_id']; ?></h3>
                            <p><strong>Customer:</strong> <?php echo $complain['customer_name']; ?></p>
                            <p><strong>Phone:</strong> <?php echo $complain['phone_number']; ?></p>
                            <p><strong>Meter:</strong> <?php echo $complain['meter_number']; ?> (<?php echo $complain['utility_type']; ?>)</p>
                            <p><strong>Date:</strong> <?php echo $complain['complain_date']; ?></p>
                            <p><strong>Status:</strong> <?php echo $complain['status']; ?></p>
                        </div>
                        
                        <div>
                            <h4>Complain Description:</h4>
                            <p><?php echo $complain['description']; ?></p>
                        </div>

                        <?php if (!empty($complain['replies'])): ?>
                            <div class="reply-section">
                                <h4>Previous Replies:</h4>
                                <?php foreach ($complain['replies'] as $reply): ?>
                                    <div class="reply-item">
                                        <p><strong>Reply Date:</strong> <?php echo $reply['reply_date']; ?></p>
                                        <p><strong>Replied By:</strong> <?php echo $reply['replied_by_name']; ?></p>
                                        <p><?php echo $reply['reply_message']; ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="reply-form">
                            <h4>Submit Reply:</h4>
                            <form method="POST" action="">
                                <input type="hidden" name="complain_id" value="<?php echo $complain['complain_id']; ?>">
                                <textarea name="reply_message" placeholder="Enter your reply..." required></textarea>
                                <button type="submit" name="submit_reply" class="submit-reply-btn">Submit Reply</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="manager_script.js"></script>
</body>
</html>
