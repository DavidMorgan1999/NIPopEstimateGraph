<?php

//This php file is used to test the connection to the database.
include 'dbinfo.php';
$conn = OpenCon();
echo "Connected Successfully";
CloseCon($conn);
?>