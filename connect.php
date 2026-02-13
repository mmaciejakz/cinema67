<?php

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_base = "kino67";


$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_base);


if (!$conn) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}


mysqli_set_charset($conn, "utf8mb4");


date_default_timezone_set('Europe/Warsaw');
?>
