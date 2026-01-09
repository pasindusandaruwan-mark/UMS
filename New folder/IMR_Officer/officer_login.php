<?php
session_start();
include __DIR__ . '/includes/db_connect.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if(!$username || !$password){
    echo json_encode(['success'=>false,'error'=>'Missing username or password']);
    exit;
}


$sql = "SELECT officer_id, username, full_name FROM Officer WHERE username = ? AND password = ?";
$params = [$username, $password];
$stmt = sqlsrv_query($conn, $sql, $params);

if($stmt === false){
    echo json_encode(['success'=>false,'error'=>print_r(sqlsrv_errors(),true)]);
    exit;
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if($row){
    $_SESSION['officer_id'] = $row['officer_id'];
    $_SESSION['officer_username'] = $row['username'];
    $_SESSION['officer_name'] = $row['full_name'];
    echo json_encode(['success'=>true]);
}else{
    echo json_encode(['success'=>false,'error'=>'Invalid username or password']);
}