<?php
session_start();

// SQL Server connection settings
$serverName = "LAPTOP-1K01JOU2\SQLEXPRESS02"; 
$connectionOptions = [
    "Database" => "UMS",
    "Uid" => "sa",
    "PWD" => "Pasi@1234"
];

// Connect to SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);

// If connection fails
if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

// Get email + password from form
$email = $_POST['email'];
$password = $_POST['password'];

// Check user in database
$sql = "SELECT * FROM users WHERE email = ? AND password = ?";
$params = array($email, $password);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($user) {

    // Save email in session
    $_SESSION['email'] = $email;

    // Read role from database
    $role = $user['role'];   // <-- your column name

    // Redirect based on role
    if ($role == "Customer") {
        header("Location: fist.html");
        exit();
    }
    else if ($role == "Admin") {
        header("Location: user.html");
        exit();
    }
    else if ($role == "officer") {
        header("Location: staff.html");
        exit();
    }
    else if ($role == "manager") {
        header("Location: manager.html");
        exit();
    }
    else {
        echo "<script>alert('Unknown Role'); window.location='index.html';</script>";
    }

} else {
    echo "<script>alert('Incorrect Email or Password'); window.location='index.html';</script>";
}
?>
