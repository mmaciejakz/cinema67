<?php

session_start();
include 'connect.php';

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['seans_id'])) {
    header("Location: movies.php");
    exit;
}

$seans_id = mysqli_real_escape_string($conn, $_GET['seans_id']);


$sql = "SELECT s.*, f.tytul, f.zdjecie, sa.sala, sa.liczba_miejsc 
        FROM seanse s
        JOIN filmy f ON s.id_filmu = f.id_filmu
        JOIN sale sa ON s.id_sali = sa.id_sali
        WHERE s.id_seansu = '$seans_id' AND s.data >= NOW()";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0) {
    header("Location: movies.php");
    exit;
}

$seans = mysqli_fetch_assoc($result);

$sql_reserved = "SELECT rm.numer_miejsca 
                 FROM rezerwacje_miejsc rm
                 JOIN rezerwacje r ON rm.id_rezerwacji = r.id_rezerwacji
                 WHERE rm.id_seansu = '$seans_id' AND r.status = 'active'";
$result_reserved = mysqli_query($conn, $sql_reserved);
$reserved_seats = [];
while($seat = mysqli_fetch_assoc($result_reserved)) {
    $reserved_seats[] = $seat['numer_miejsca'];
}

$error = "";
$success = "";
$reservation_code = "";

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reserve'])) {
    if(!isset($_POST['seats']) || empty($_POST['seats'])) {
        $error = "Wybierz co najmniej jedno miejsce";
    } else {
        $selected_seats = $_POST['seats'];
        
        if(count($selected_seats) > 6) {
            $error = "Możesz zarezerwować maksymalnie 6 miejsc";
        } else {
     
            $all_available = true;
            $unavailable_seats = [];
            
            foreach($selected_seats as $seat) {
                if(in_array($seat, $reserved_seats)) {
                    $all_available = false;
                    $unavailable_seats[] = $seat;
                }
            }
            
            if(!$all_available) {
                $error = "Następujące miejsca są już zajęte: " . implode(', ', $unavailable_seats);
            } else {
          
                $seats_string = implode(',', $selected_seats);
                $total_price = count($selected_seats) * $seans['cena_biletu'];
                
            
                $reservation_code = strtoupper(substr(md5(uniqid()), 0, 8));
                
              
                mysqli_begin_transaction($conn);
                
                try {
               
                    $sql_insert = "INSERT INTO rezerwacje (id_user, id_seansu, miejsca, status, kod_rezerwacji, cena_laczna) 
                                   VALUES ('{$_SESSION['user_id']}', '$seans_id', '$seats_string', 'active', '$reservation_code', '$total_price')";
                    
                    if(!mysqli_query($conn, $sql_insert)) {
                        throw new Exception("Błąd tworzenia rezerwacji");
                    }
                    
                    $reservation_id = mysqli_insert_id($conn);
                    
             
                    foreach($selected_seats as $seat) {
                        $sql_seat = "INSERT INTO rezerwacje_miejsc (id_rezerwacji, id_seansu, numer_miejsca) 
                                     VALUES ('$reservation_id', '$seans_id', '$seat')";
                        
                        if(!mysqli_query($conn, $sql_seat)) {
                            throw new Exception("Błąd rezerwacji miejsca");
                        }
                    }
                    
          
                    foreach($selected_seats as $seat) {
                        $sql_update = "UPDATE miejsca_w_salach 
                                       SET status = 'zarezerwowane'
                                       WHERE id_sali = (SELECT id_sali FROM seanse WHERE id_seansu = '$seans_id')
                                       AND numer = '$seat'";
                        
                        mysqli_query($conn, $sql_update);
                    }
                    
         
                    mysqli_commit($conn);
                    
                    $success = "Rezerwacja zakończona pomyślnie!";
                    
                } catch (Exception $e) {
         
                    mysqli_rollback($conn);
                    $error = "Wystąpił błąd podczas rezerwacji: " . $e->getMessage();
                }
            }
        }
    }
}


$sql_layout = "SELECT * FROM miejsca_w_salach 
               WHERE id_sali = (SELECT id_sali FROM seanse WHERE id_seansu = '$seans_id')
               ORDER BY rzad, numer";
$result_layout = mysqli_query($conn, $sql_layout);
$seats_layout = [];
while($seat = mysqli_fetch_assoc($result_layout)) {
    $seats_layout[$seat['rzad']][] = $seat;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezerwacja - Kino 67</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <style>
        .reservation-page {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .reservation-header {
            background: #1a1a1a;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .reservation-header img {
            width: 150px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .movie-info h2 {
            color: #3b82f6;
            margin: 0 0 10px 0;
        }
        
        .movie-details {
            color: #94a3b8;
            margin: 5px 0;
        }
        
        .seat-map-container {
            background: #1a1a1a;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .screen {
            width: 80%;
            height: 30px;
            background: linear-gradient(90deg, #333, #666, #333);
            margin: 0 auto 30px;
            text-align: center;
            color: white;
            padding: 10px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .seat-row {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-bottom: 10px;
        }
        
        .row-label {
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-weight: bold;
        }
        
        .seat {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
            transition: all 0.2s;
        }
        
        .seat.available {
            background: #3b82f6;
            border: 2px solid #1e40af;
        }
        
        .seat.available:hover {
            background: #60a5fa;
            transform: scale(1.1);
        }
        
        .seat.selected {
            background: #10b981;
            border: 2px solid #059669;
        }
        
        .seat.reserved {
            background: #ef4444;
            border: 2px solid #dc2626;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .selected-seats {
            background: #1a1a1a;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .selected-seats-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 10px 0;
        }
        
        .seat-badge {
            background: #3b82f6;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .summary {
            background: #1a1a1a;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #333;
        }
        
        .summary-total {
            font-size: 1.2em;
            font-weight: bold;
            color: #3b82f6;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid #22c55e;
            color: #22c55e;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid #ef4444;
            color: #ef4444;
        }
        
        .seat-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            color: #94a3b8;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <?php 
    $show_header = true;
    include 'header.php'; 
    ?>
    
    <div class="reservation-page">
        <?php if(!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if(!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                <p>Twój kod rezerwacji: <strong><?php echo $reservation_code; ?></strong></p>
                <p>Możesz go zobaczyć w zakładce "Moje rezerwacje".</p>
                <div style="margin-top: 15px;">
                    <a href="user_reservations.php" class="btn btn-primary">
                        <i class="fas fa-ticket-alt"></i> Moje rezerwacje
                    </a>
                    <a href="movies.php" class="btn btn-secondary" style="margin-left: 10px;">
                        <i class="fas fa-film"></i> Zobacz inne filmy
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if(empty($success)): ?>

        <div class="reservation-header">
            <img src="<?php echo $seans['zdjecie']; ?>" alt="<?php echo $seans['tytul']; ?>"
                 onerror="this.src='https://via.placeholder.com/150x200?text=Brak+plakatu'">
            <div class="movie-info">
                <h2><?php echo $seans['tytul']; ?></h2>
                <div class="movie-details">
                    <p><i class="fas fa-calendar"></i> Data: <?php echo date('d.m.Y', strtotime($seans['data'])); ?></p>
                    <p><i class="fas fa-clock"></i> Godzina: <?php echo date('H:i', strtotime($seans['data'])); ?></p>
                    <p><i class="fas fa-door-closed"></i> Sala: <?php echo $seans['sala']; ?></p>
                    <p><i class="fas fa-money-bill-wave"></i> Cena: <?php echo $seans['cena_biletu']; ?> zł / miejsce</p>
                </div>
            </div>
        </div>
        

        <div class="seat-map-container">
            <h2 style="text-align: center; color: #3b82f6; margin-bottom: 20px;">
                Wybierz miejsca
            </h2>
            
            <div class="screen">EKRAN</div>
            
            <form method="POST" action="" id="reservationForm">
                <div class="seat-map">
                    <?php foreach($seats_layout as $row_number => $seats): ?>
                        <div class="seat-row">
                            <div class="row-label">Rząd <?php echo $row_number; ?></div>
                            <?php foreach($seats as $seat): 
                                $is_reserved = in_array($seat['numer'], $reserved_seats);
                                $seat_class = $is_reserved ? 'reserved' : 'available';
                            ?>
                                <div class="seat <?php echo $seat_class; ?>" 
                                     data-seat="<?php echo $seat['numer']; ?>"
                                     onclick="<?php echo $is_reserved ? '' : 'toggleSeat(this)'; ?>">
                                    <?php echo $seat['numer']; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div id="selectedSeatsInputs"></div>
                

                <div class="seat-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background: #3b82f6;"></div>
                        <span>Dostępne</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #10b981;"></div>
                        <span>Wybrane</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #ef4444;"></div>
                        <span>Zajęte</span>
                    </div>
                </div>
                

                <div class="selected-seats">
                    <h3>Wybrane miejsca: <span id="selectedCount">0</span></h3>
                    <div class="selected-seats-list" id="selectedSeatsList">
                        <span style="color: #94a3b8;">Brak wybranych miejsc</span>
                    </div>
                </div>
                

                <div class="summary">
                    <h3>Podsumowanie</h3>
                    <div class="summary-item">
                        <span>Cena za miejsce:</span>
                        <span><?php echo $seans['cena_biletu']; ?> zł</span>
                    </div>
                    <div class="summary-item">
                        <span>Liczba miejsc:</span>
                        <span id="seatCount">0</span>
                    </div>
                    <div class="summary-item summary-total">
                        <span>Łączna cena:</span>
                        <span id="totalPrice">0.00 zł</span>
                    </div>
                    
                    <button type="submit" name="reserve" class="btn btn-primary" 
                            style="width: 100%; padding: 15px; margin-top: 20px; font-size: 1.1em;">
                        <i class="fas fa-ticket-alt"></i> Potwierdź rezerwację
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script>
        let selectedSeats = [];
        const maxSeats = 6;
        const pricePerSeat = <?php echo $seans['cena_biletu']; ?>;
        
        function toggleSeat(element) {
            const seatNumber = element.getAttribute('data-seat');
            const index = selectedSeats.indexOf(seatNumber);
            
            if(index === -1) {
                
                if(selectedSeats.length < maxSeats) {
                    selectedSeats.push(seatNumber);
                    element.classList.remove('available');
                    element.classList.add('selected');
                } else {
                    alert(`Możesz wybrać maksymalnie ${maxSeats} miejsc.`);
                }
            } else {
   
                selectedSeats.splice(index, 1);
                element.classList.remove('selected');
                element.classList.add('available');
            }
            
            updateSelectedSeats();
        }
        
        function updateSelectedSeats() {
    
            const inputsContainer = document.getElementById('selectedSeatsInputs');
            inputsContainer.innerHTML = '';
            selectedSeats.forEach(seat => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'seats[]';
                input.value = seat;
                inputsContainer.appendChild(input);
            });
            
  
            const selectedList = document.getElementById('selectedSeatsList');
            const selectedCount = document.getElementById('selectedCount');
            const seatCount = document.getElementById('seatCount');
            const totalPrice = document.getElementById('totalPrice');
            
            if(selectedSeats.length === 0) {
                selectedList.innerHTML = '<span style="color: #94a3b8;">Brak wybranych miejsc</span>';
            } else {
                selectedList.innerHTML = selectedSeats.map(seat => 
                    `<span class="seat-badge">Miejsce ${seat}</span>`
                ).join('');
            }
            
            selectedCount.textContent = selectedSeats.length;
            seatCount.textContent = selectedSeats.length;
            totalPrice.textContent = (selectedSeats.length * pricePerSeat).toFixed(2) + ' zł';
        }
        

        document.getElementById('reservationForm').addEventListener('submit', function(e) {
            if(selectedSeats.length === 0) {
                e.preventDefault();
                alert('Wybierz co najmniej jedno miejsce przed potwierdzeniem rezerwacji.');
                return false;
            }
        });
    </script>
</body>
</html>
