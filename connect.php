<?php
// connect.php - NAPRAWIONY
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_base = "kino67";

// Tworzymy połączenie
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_base);

// Sprawdzamy czy połączenie działa
if (!$conn) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

// Ustawiamy kodowanie
mysqli_set_charset($conn, "utf8mb4");

// Ustawiamy strefę czasową
date_default_timezone_set('Europe/Warsaw');
?>