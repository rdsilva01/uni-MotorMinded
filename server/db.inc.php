<?php
$server = "localhost:8889";
$user = "root";
$pwd = "root";
$db = "mm_db";

$conn = new mysqli($server, $user, $pwd, $db);

 header("Content-Type: text/html");
if($conn->connect_errno)
{http_response_code(400);
    echo  $conn->connect_error; exit();}