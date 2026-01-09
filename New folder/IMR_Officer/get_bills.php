<?php
header('Content-Type: application/json; charset=utf-8');
include __DIR__ . '/includes/db_connect.php';

$meter_no = $_GET['meter_number'] ?? null;
$params = [];
$sql = "
SELECT b.bill_id, c.full_name, s.service_name, b.billing_month, b.amount, b.due_date, b.status
FROM Bill b
JOIN Meter m ON b.meter_id = m.meter_id
JOIN Customer c ON m.customer_id = c.customer_id
JOIN Service s ON m.service_id = s.service_id
";

if($meter_no){
    $sql .= " WHERE m.meter_number = ?";
    $params[] = $meter_no;
}

$stmt = sqlsrv_query($conn, $sql, $params);
if($stmt === false) { echo json_encode(['success'=>false,'error'=>print_r(sqlsrv_errors(),true)]); exit; }
$data = [];
while($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $data[] = $r;
echo json_encode(['success'=>true,'data'=>$data]);