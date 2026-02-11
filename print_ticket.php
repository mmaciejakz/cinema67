<?php
// print_ticket.php
session_start();
include 'connect.php';

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])) {
    header("Location: user_reservations.php");
    exit;
}

$reservation_id = mysqli_real_escape_string($conn, $_GET['id']);
$user_id = $_SESSION['user_id'];

// Pobierz szczegóły rezerwacji
$sql = "SELECT r.*, f.tytul, f.zdjecie, f.czas_trwania, 
               s.data, s.cena_biletu, sa.sala,
               u.username, u.email
        FROM rezerwacje r
        JOIN seanse s ON r.id_seansu = s.id_seansu
        JOIN filmy f ON s.id_filmu = f.id_filmu
        JOIN sale sa ON s.id_sali = sa.id_sali
        JOIN users u ON r.id_user = u.id
        WHERE r.id_rezerwacji = '$reservation_id' AND r.id_user = '$user_id'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0) {
    header("Location: user_reservations.php");
    exit;
}

$ticket = mysqli_fetch_assoc($sql);
$seats = json_decode($ticket['miejsca'], true);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilet - Kino 67</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .ticket, .ticket * {
                visibility: visible;
            }
            .ticket {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .ticket {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .ticket-content {
            padding: 2rem;
        }
        
        .ticket-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .info-item {
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #1e40af;
        }
        
        .info-label {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 0.3rem;
        }
        
        .info-value {
            font-size: 1.1rem;
            font-weight: bold;
            color: #1e293b;
        }
        
        .seats-section {
            margin-bottom: 2rem;
        }
        
        .seat-badge {
            display: inline-block;
            background: #1e40af;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            margin: 0.3rem;
            font-weight: bold;
        }
        
        .barcode {
            text-align: center;
            margin: 2rem 0;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .ticket-footer {
            text-align: center;
            padding: 1.5rem;
            background: #f8fafc;
            color: #64748b;
            font-size: 0.9rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #1e40af;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
            z-index: 1000;
        }
        
        .print-btn:hover {
            background: #3b82f6;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h1 style="margin: 0 0 0.5rem; font-size: 2rem;">KINO 67</h1>
            <p style="margin: 0; opacity: 0.9;">Bilet kinowy</p>
        </div>
        
        <div class="ticket-content">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="color: #1e40af; margin-bottom: 0.5rem;"><?php echo $ticket['tytul']; ?></h2>
                <p style="color: #64748b;"><?php echo date('d.m.Y H:i', strtotime($ticket['data'])); ?></p>
            </div>
            
            <div class="ticket-info">
                <div class="info-item">
                    <div class="info-label">Kod rezerwacji</div>
                    <div class="info-value"><?php echo $ticket['kod_rezerwacji']; ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Sala</div>
                    <div class="info-value"><?php echo $ticket['sala']; ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Czas trwania</div>
                    <div class="info-value"><?php echo $ticket['czas_trwania']; ?> min</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Cena</div>
                    <div class="info-value"><?php echo $ticket['cena_laczna']; ?> zł</div>
                </div>
            </div>
            
            <div class="seats-section">
                <h3 style="color: #1e293b; margin-bottom: 1rem;">Miejsca</h3>
                <div>
                    <?php if(is_array($seats)): ?>
                        <?php foreach($seats as $seat): ?>
                            <span class="seat-badge">Miejsce <?php echo $seat; ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #64748b;">Brak informacji o miejscach</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="seats-section">
                <h3 style="color: #1e293b; margin-bottom: 1rem;">Informacje dla widza</h3>
                <div style="background: #fef3c7; padding: 1rem; border-radius: 8px; border-left: 4px solid #f59e0b;">
                    <p style="margin: 0; color: #92400e;">
                        <strong>Prosimy o przybycie co najmniej 15 minut przed rozpoczęciem seansu.</strong><br>
                        Bilet należy okazać przy wejściu do sali.<br>
                        Rezerwacja ważna jest do rozpoczęcia seansu.
                    </p>
                </div>
            </div>
            
            <div class="barcode">
                <div style="font-family: monospace; font-size: 1.5rem; letter-spacing: 5px; margin-bottom: 1rem;">
                    <?php echo $ticket['kod_rezerwacji']; ?>
                </div>
                <div style="background: black; height: 3px; width: 80%; margin: 0 auto;"></div>
                <p style="margin: 1rem 0 0; color: #64748b; font-size: 0.9rem;">Kod kreskowy rezerwacji</p>
            </div>
            
            <div style="text-align: center; margin-top: 2rem; color: #64748b;">
                <p>Wydrukowano: <?php echo date('d.m.Y H:i'); ?></p>
                <p>Użytkownik: <?php echo $ticket['username']; ?> (<?php echo $ticket['email']; ?>)</p>
            </div>
        </div>
        
        <div class="ticket-footer">
            <p>Kino 67 &copy; <?php echo date('Y'); ?> | ul. Kinowa 67, 00-000 Miasto</p>
            <p>tel: 123 456 789 | email: kontakt@kino67.pl</p>
        </div>
    </div>
    
    <button class="print-btn no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Drukuj bilet
    </button>
    <button class="print-btn no-print" onclick="window.close()" style="right: 180px; background: #64748b;">
        <i class="fas fa-times"></i> Zamknij
    </button>
    
    <script>
        // Automatycznie uruchom drukowanie po załadowaniu strony
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>
</body>
</html>