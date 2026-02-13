<?php
session_start();
include 'connect.php';

header('Content-Type: application/json');

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Nie jesteś zalogowany']);
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $seans_id = mysqli_real_escape_string($conn, $_POST['seans_id']);
    $seats = json_decode($_POST['seats'], true);
    $user_id = $_SESSION['user_id']; 
    
    if(!is_array($seats) || empty($seats)) {
        echo json_encode(['success' => false, 'error' => 'Brak wybranych miejsc']);
        exit;
    }
    

    foreach($seats as $seat) {
        $seat = intval($seat);
        $sql = "SELECT * FROM rezerwacje_miejsc 
                WHERE id_seansu = '$seans_id' AND numer_miejsca = '$seat'";
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) > 0) {
            echo json_encode(['success' => false, 'error' => "Miejsce $seat jest już zajęte"]);
            exit;
        }
    }
    

    $seats_json = json_encode($seats);
    $sql = "INSERT INTO rezerwacje (id_user, id_seansu, miejsca, status, data_rezerwacji) 
            VALUES ('$user_id', '$seans_id', '$seats_json', 'active', NOW())";
    
    if(mysqli_query($conn, $sql)) {
        $reservation_id = mysqli_insert_id($conn);
        

        foreach($seats as $seat) {
            $sql = "INSERT INTO rezerwacje_miejsc (id_rezerwacji, id_seansu, numer_miejsca) 
                    VALUES ('$reservation_id', '$seans_id', '$seat')";
            mysqli_query($conn, $sql);
        }
        
        echo json_encode(['success' => true, 'reservation_id' => $reservation_id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Błąd bazy danych']);
    }
}
?>
