<?php
session_start();
include 'connect.php';

if(!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("Location: index.php");
    exit;
}

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "DELETE FROM filmy WHERE id_filmu = '$id'";
    
    if(mysqli_query($conn, $sql)) {
        header("Location: admin.php?message=Film+usunięty+pomyślnie&type=success");
    } else {
        header("Location: admin.php?message=Błąd+przy+usuwaniu+filmu&type=error");
    }
    exit;
}
?>