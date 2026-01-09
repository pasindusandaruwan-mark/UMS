<?php

$serverName = "ASUS\\SQLEXPRESS";
$connectionInfo = [
    "Database" => "UMS",
    "UID" => "",
    "PWD" => "",
    "TrustServerCertificate" => true
];

$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    // Remove this echo in production
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
}
?>