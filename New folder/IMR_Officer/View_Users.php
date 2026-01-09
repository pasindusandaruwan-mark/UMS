<?php
header('Content-Type: application/json; charset=utf-8');
include __DIR__ . '/includes/db_connect.php';

$sql = "SELECT user_id, name, meter_number, address, nic, phone FROM users ORDER BY user_id DESC";
$stmt = sqlsrv_query($conn, $sql);
if($stmt === false){
    echo json_encode(['success'=>false,'error'=>print_r(sqlsrv_errors(),true)]);
    exit;
}
$rows = [];
while($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
    $rows[] = $r;
}
echo json_encode(['success'=>true,'data'=>$rows]);