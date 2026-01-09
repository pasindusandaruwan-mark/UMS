<?php
$server = "LAPTOP-1K01JOU2\SQLEXPRESS02"; // your server name

$connectionInfo = array(
    "Database" => "UMS",   // your database name
    "UID" => "sa",            // SQL username
    "PWD" => "Pasi@1234",   // your password
);

$conn = sqlsrv_connect($server, $connectionInfo);

if ($conn) {
    echo "SQL Server Connected Successfully!";
} else {
    echo "Connection failed!<br>";
    die(print_r(sqlsrv_errors(), true));
}
?>
