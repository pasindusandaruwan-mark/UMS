<?php

// 1) CONNECT TO SQL SERVER
$server = "LAPTOP-1K01JOU2\SQLEXPRESS02";
$connectionInfo = array(
    "Database" => "UMS",
    "UID" => "sa",
    "PWD" => "Pasi@1234",
);
$conn = sqlsrv_connect($server, $connectionInfo);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

// 2) GET VALUES FROM FORM
$role     = $_POST['role'];
$name     = $_POST['fname'];
$address  = $_POST['address'];
$phone    = $_POST['pnumber'];  // FIXED
$email    = $_POST['email'];
$nic      = $_POST['NIC'];      // OK
$password = $_POST['password1'];
$confirm  = $_POST['password2'];

// 3) PASSWORD MATCH CHECK
if ($password != $confirm) {
    die("Password mismatch!");
}

// 4) INSERT INTO USERS TABLE
// FIXED: NIC_umber (your DB spelling)
$sqlUsers = "INSERT INTO Users (name, address, phone_number, email, NIC_umber, password, role)
             VALUES (?, ?, ?, ?, ?, ?, ?)";

$paramsUsers = array($name, $address, $phone, $email, $nic, $password, $role);

$stmt = sqlsrv_query($conn, $sqlUsers, $paramsUsers);

if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));
}

// GET user_id
$getID = sqlsrv_query($conn, "SELECT SCOPE_IDENTITY() AS id");
$row = sqlsrv_fetch_array($getID, SQLSRV_FETCH_ASSOC);
$user_id = $row['id'];

// 5) INSERT INTO ROLE TABLE
switch ($role) {

    case "officer":
        $sqlRole = "INSERT INTO officer (user_id, name, address, phone_number, email, NIC_umber, password)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
        break;

    case "Manager":
        $sqlRole = "INSERT INTO  manager (user_id, name, address, phone_number, email, NIC_umber, password)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
        break;

    case "Admin":
        $sqlRole = "INSERT INTO addmin (user_id, name, address, phone_number, email, NIC_umber, password)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
        break;

    case "Customer":
        $sqlRole = "INSERT INTO Customer (user_id, name, address, phone_number, email, NIC_umber, password)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
        break;
}

$paramsRole = array($user_id, $name, $address, $phone, $email, $nic, $password);
sqlsrv_query($conn, $sqlRole, $paramsRole);

// SUCCESS
echo "Account Created Successfully!";

?>
