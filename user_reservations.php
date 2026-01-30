<?php
// user_reservations.php - UPROSZCZONY
session_start();
include 'connect.php';

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Anulowanie rezerwacji
if(isset($_GET['cancel'])) {
    $reservation_id = mysqli_real_escape_string($conn, $_GET['cancel']);
    
    // Sprawdź czy rezerwacja należy do użytkownika
    $sql_check = "SELECT r.*, s.data FROM rezerwacje r
                  JOIN seanse s ON r.id_seansu = s.id_seansu
                  WHERE r.id_rezerwacji = '$reservation_id' AND r.id_user = '$user_id'";
    $result_check = mysqli_query($conn, $sql_check);
    
    if(mysqli_num_rows($result_check) > 0) {
        $reservation = mysqli_fetch_assoc($result_check);
        
        // Sprawdź czy seans jeszcze się nie odbył
        if(strtotime($reservation['data']) > time()) {
            mysqli_begin_transaction($conn);
            
            try {
                // 1. Zaktualizuj status rezerwacji
                $sql_update = "UPDATE rezerwacje SET status = 'cancelled' WHERE id_rezerwacji = '$reservation_id'";
                mysqli_query($conn, $sql_update);
                
                // 2. Zwolnij miejsca
                $seats = explode(',', $reservation['miejsca']);
                foreach($seats as $seat) {
                    $sql_free = "UPDATE miejsca_w_salach 
                                SET status = 'wolne'
                                WHERE id_sali = (SELECT id_sali FROM seanse WHERE id_seansu = '{$reservation['id_seansu']}')
                                AND numer = '$seat'";
                    mysqli_query($conn, $sql_free);
                }
                
                // 3. Usuń rezerwacje miejsc
                $sql_delete = "DELETE FROM rezerwacje_miejsc WHERE id_rezerwacji = '$reservation_id'";
                mysqli_query($conn, $sql_delete);
                
                mysqli_commit($conn);
                $success = "Rezerwacja została anulowana.";
                
            } catch (Exception $e) {
                mysqli_rollback($conn);
                $error = "Wystąpił błąd podczas anulowania rezerwacji.";
            }
        } else {
            $error = "Nie można anulować rezerwacji na seans, który się już odbył.";
        }
    } else {
        $error = "Rezerwacja nie istnieje lub nie masz do niej dostępu.";
    }
}

// Pobierz rezerwacje użytkownika (bez anulowanych)
$sql = "SELECT r.*, f.tytul, f.zdjecie, s.data, s.cena_biletu, sa.sala 
        FROM rezerwacje r 
        JOIN seanse s ON r.id_seansu = s.id_seansu 
        JOIN filmy f ON s.id_filmu = f.id_filmu 
        JOIN sale sa ON s.id_sali = sa.id_sali 
        WHERE r.id_user = '$user_id' AND r.status != 'cancelled'
        ORDER BY s.data DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje rezerwacje - Kino 67</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .reservations-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-header h1 {
            color: #3b82f6;
            margin-bottom: 10px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
        
        .reservation-card {
            background: #1a1a1a;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid #3b82f6;
        }
        
        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .reservation-header h3 {
            color: white;
            margin: 0;
        }
        
        .reservation-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9em;
        }
        
        .status-active {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid #22c55e;
        }
        
        .status-cancelled {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid #ef4444;
        }
        
        .reservation-content {
            display: flex;
            gap: 20px;
        }
        
        .reservation-content img {
            width: 100px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .reservation-details {
            flex: 1;
        }
        
        .detail-row {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        
        .detail-item {
            background: #0a0a0a;
            padding: 10px 15px;
            border-radius: 5px;
            min-width: 200px;
        }
        
        .detail-label {
            color: #94a3b8;
            font-size: 0.9em;
            margin-bottom: 5px;
        }
        
        .detail-value {
            color: white;
            font-weight: bold;
        }
        
        .seats-section {
            margin: 15px 0;
        }
        
        .seats-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .seat-badge {
            background: #3b82f6;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .reservation-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #94a3b8;
        }
        
        .empty-state i {
            font-size: 3em;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php 
    $show_header = true;
    include 'header.php'; 
    ?>
    
    <div class="reservations-page">
        <div class="page-header">
            <h1><i class="fas fa-ticket-alt"></i> Moje rezerwacje</h1>
            <p style="color: #94a3b8;">Zarządzaj swoimi rezerwacjami biletów</p>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($reservation = mysqli_fetch_assoc($result)): 
                $seats = explode(',', $reservation['miejsca']);
                $is_past = strtotime($reservation['data']) < time();
                $status = $reservation['status'];
                
                if($is_past && $status == 'active') {
                    $status = 'used';
                    $status_class = 'status-cancelled';
                    $status_text = 'Wykorzystana';
                } elseif($status == 'active') {
                    $status_class = 'status-active';
                    $status_text = 'Aktywna';
                } elseif($status == 'cancelled') {
                    $status_class = 'status-cancelled';
                    $status_text = 'Anulowana';
                } else {
                    $status_class = 'status-cancelled';
                    $status_text = $status;
                }
            ?>
                <div class="reservation-card">
                    <div class="reservation-header">
                        <h3><?php echo $reservation['tytul']; ?></h3>
                        <span class="reservation-status <?php echo $status_class; ?>">
                            <?php echo $status_text; ?>
                        </span>
                    </div>
                    
                    <div class="reservation-content">
                        <img src="<?php echo $reservation['zdjecie']; ?>" 
                             alt="<?php echo $reservation['tytul']; ?>"
                             onerror="this.src='https://via.placeholder.com/100x150?text=Brak+plakatu'">
                        
                        <div class="reservation-details">
                            <div class="detail-row">
                                <div class="detail-item">
                                    <div class="detail-label">Data seansu</div>
                                    <div class="detail-value">
                                        <?php echo date('d.m.Y H:i', strtotime($reservation['data'])); ?>
                                    </div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="detail-label">Sala</div>
                                    <div class="detail-value"><?php echo $reservation['sala']; ?></div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="detail-label">Kod rezerwacji</div>
                                    <div class="detail-value"><?php echo $reservation['kod_rezerwacji']; ?></div>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-item">
                                    <div class="detail-label">Cena za bilet</div>
                                    <div class="detail-value"><?php echo $reservation['cena_biletu']; ?> zł</div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="detail-label">Łączna cena</div>
                                    <div class="detail-value"><?php echo $reservation['cena_laczna']; ?> zł</div>
                                </div>
                                
                                <div class="detail-item">
                                    <div class="detail-label">Data rezerwacji</div>
                                    <div class="detail-value">
                                        <?php echo date('d.m.Y H:i', strtotime($reservation['data_rezerwacji'])); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="seats-section">
                                <div class="detail-label">Zarezerwowane miejsca</div>
                                <div class="seats-list">
                                    <?php foreach($seats as $seat): ?>
                                        <span class="seat-badge">Miejsce <?php echo $seat; ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="reservation-actions">
                                <?php if($status == 'active' && !$is_past): ?>
                                    <a href="user_reservations.php?cancel=<?php echo $reservation['id_rezerwacji']; ?>" 
                                       class="btn btn-secondary"
                                       onclick="return confirm('Czy na pewno chcesz anulować tę rezerwację?')">
                                        <i class="fas fa-times"></i> Anuluj rezerwację
                                    </a>
                                <?php endif; ?>
                                
                                <button class="btn btn-primary" onclick="printTicket('<?php echo $reservation['kod_rezerwacji']; ?>')">
                                    <i class="fas fa-print"></i> Drukuj bilet
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-ticket-alt"></i>
                <h3>Brak rezerwacji</h3>
                <p>Nie masz jeszcze żadnych rezerwacji.</p>
                <a href="movies.php" class="btn btn-primary" style="margin-top: 20px;">
                    <i class="fas fa-film"></i> Zobacz repertuar
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script>
        function printTicket(reservationCode) {
            // Utwórz prosty bilet do druku
            const ticketContent = `
                <html>
                <head>
                    <title>Bilet - ${reservationCode}</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .ticket { border: 2px solid #333; padding: 20px; max-width: 600px; margin: 0 auto; }
                        .header { text-align: center; margin-bottom: 20px; }
                        .details { margin-bottom: 20px; }
                        .details div { margin-bottom: 10px; }
                        .barcode { text-align: center; font-family: monospace; font-size: 24px; letter-spacing: 5px; margin: 20px 0; }
                        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="ticket">
                        <div class="header">
                            <h1>KINO 67</h1>
                            <h2>Bilet kinowy</h2>
                        </div>
                        <div class="details">
                            <div><strong>Kod rezerwacji:</strong> ${reservationCode}</div>
                            <div><strong>Data wydruku:</strong> ${new Date().toLocaleString()}</div>
                        </div>
                        <div class="barcode">
                            ${reservationCode}
                        </div>
                        <div class="footer">
                            <p>Prosimy o przybycie 15 minut przed seansem</p>
                            <p>Bilet prosimy okazać przy wejściu do sali</p>
                        </div>
                    </div>
                </body>
                </html>
            `;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(ticketContent);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>
</html>