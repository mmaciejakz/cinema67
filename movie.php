<?php 
// movie.php
session_start();
include 'connect.php';

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$movie_id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT f.*, k.kategoria FROM filmy f 
        LEFT JOIN kategorie k ON f.id_kategorii = k.id_kategorii 
        WHERE f.id_filmu = '$movie_id'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit;
}

$movie = mysqli_fetch_assoc($result);

// Pobierz seanse dla tego filmu
$sql_seanse = "SELECT s.*, sa.sala, sa.liczba_miejsc,
               (sa.liczba_miejsc - COUNT(DISTINCT rm.numer_miejsca)) as wolne_miejsca
               FROM seanse s 
               LEFT JOIN sale sa ON s.id_sali = sa.id_sali 
               LEFT JOIN rezerwacje_miejsc rm ON s.id_seansu = rm.id_seansu
               WHERE s.id_filmu = '$movie_id' AND s.data >= NOW() 
               GROUP BY s.id_seansu
               ORDER BY s.data";
$seanse = mysqli_query($conn, $sql_seanse);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $movie['tytul']; ?> - Kino 67</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .movie-detail {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 3rem;
            padding: 3rem 0;
        }
        
        @media (max-width: 768px) {
            .movie-detail {
                grid-template-columns: 1fr;
            }
        }
        
        .movie-poster-large {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }
        
        .movie-header {
            margin-bottom: 2rem;
        }
        
        .movie-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }
        
        .meta-item {
            background: var(--secondary-dark);
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid var(--primary-blue);
        }
        
        .seans-card {
            background: var(--secondary-dark);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #333;
            transition: all 0.3s ease;
        }
        
        .seans-card:hover {
            border-color: var(--primary-blue);
            transform: translateY(-3px);
        }
        
        .seans-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .seats-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .seat-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.8rem;
            background: var(--primary-dark);
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .seat-available { color: #22c55e; }
        .seat-low { color: #f59e0b; }
        .seat-sold-out { color: #ef4444; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="movie-detail">
            <div>
                <img src="<?php echo $movie['zdjecie']; ?>" alt="<?php echo $movie['tytul']; ?>" 
                     class="movie-poster-large"
                     onerror="this.src='https://via.placeholder.com/400x600?text=Brak+plakatu'">
                
                <div style="margin-top: 1.5rem; text-align: center;">
                    <div style="display: inline-block; background: var(--primary-dark); padding: 0.5rem 1.5rem; border-radius: 20px;">
                        <span style="color: var(--accent-blue); font-weight: bold;">
                            <?php echo $movie['ograniczenie_wiekowe']; ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div>
                <div class="movie-header">
                    <h1 style="color: var(--secondary-blue); margin-bottom: 0.5rem;"><?php echo $movie['tytul']; ?></h1>
                    <p style="color: var(--text-gray); font-size: 1.2rem;">
                        <i class="fas fa-user"></i> Reżyseria: <?php echo $movie['autor']; ?>
                    </p>
                </div>
                
                <div class="movie-meta-grid">
                    <div class="meta-item">
                        <div style="color: var(--text-gray); font-size: 0.9rem;">Kategoria</div>
                        <div style="color: var(--accent-blue); font-weight: bold;"><?php echo $movie['kategoria']; ?></div>
                    </div>
                    
                    <div class="meta-item">
                        <div style="color: var(--text-gray); font-size: 0.9rem;">Czas trwania</div>
                        <div style="color: var(--accent-blue); font-weight: bold;">
                            <i class="fas fa-clock"></i> <?php echo $movie['czas_trwania']; ?> min
                        </div>
                    </div>
                    
                    <div class="meta-item">
                        <div style="color: var(--text-gray); font-size: 0.9rem;">Dostępne seanse</div>
                        <div style="color: var(--accent-blue); font-weight: bold;">
                            <?php echo mysqli_num_rows($seanse); ?>
                        </div>
                    </div>
                </div>
                
                <div style="margin: 2rem 0;">
                    <h3 style="color: var(--text-light); margin-bottom: 1rem;">Opis filmu</h3>
                    <p style="color: var(--text-gray); line-height: 1.8; font-size: 1.1rem;">
                        <?php echo $movie['opis']; ?>
                    </p>
                </div>
                
                <h2 style="color: var(--secondary-blue); margin: 2rem 0 1rem;">
                    <i class="fas fa-calendar-alt"></i> Dostępne seanse
                </h2>
                
                <div class="seanse-list">
                    <?php if(mysqli_num_rows($seanse) > 0): ?>
                        <?php while($seans = mysqli_fetch_assoc($seanse)): 
                            $wolne_miejsca = $seans['wolne_miejsca'] ?? $seans['liczba_miejsc'];
                            $percent_available = ($wolne_miejsca / $seans['liczba_miejsc']) * 100;
                            
                            // Określ status dostępności miejsc
                            if ($wolne_miejsca <= 0) {
                                $seat_class = "seat-sold-out";
                                $seat_text = "Wyprzedane";
                            } elseif ($wolne_miejsca <= 5) {
                                $seat_class = "seat-low";
                                $seat_text = "Ostatnie miejsca";
                            } else {
                                $seat_class = "seat-available";
                                $seat_text = "Dostępne";
                            }
                        ?>
                            <div class="seans-card">
                                <div class="seans-header">
                                    <div>
                                        <h3 style="color: var(--text-light); margin-bottom: 0.3rem;">
                                            <?php echo date('d.m.Y', strtotime($seans['data'])); ?>
                                        </h3>
                                        <p style="color: var(--text-gray);">
                                            <i class="fas fa-clock"></i> <?php echo date('H:i', strtotime($seans['data'])); ?>
                                            &nbsp;|&nbsp;
                                            <i class="fas fa-door-closed"></i> <?php echo $seans['sala']; ?>
                                            &nbsp;|&nbsp;
                                            <i class="fas fa-money-bill-wave"></i> <?php echo $seans['cena_biletu']; ?> zł
                                        </p>
                                    </div>
                                    
                                    <div class="seat-indicator <?php echo $seat_class; ?>">
                                        <i class="fas fa-chair"></i>
                                        <?php echo $wolne_miejsca; ?> / <?php echo $seans['liczba_miejsc']; ?> miejsc
                                    </div>
                                </div>
                                
                                <div class="seats-info">
                                    <span class="seat-indicator <?php echo $seat_class; ?>">
                                        <i class="fas fa-info-circle"></i> <?php echo $seat_text; ?>
                                    </span>
                                    
                                    <?php if($wolne_miejsca > 0): ?>
                                        <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                                            <a href="reservation.php?seans_id=<?php echo $seans['id_seansu']; ?>" 
                                               class="btn btn-primary">
                                                <i class="fas fa-ticket-alt"></i> Wybierz miejsca
                                            </a>
                                        <?php else: ?>
                                            <a href="login.php" class="btn btn-secondary">
                                                <i class="fas fa-sign-in-alt"></i> Zaloguj się aby rezerwować
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fas fa-times-circle"></i> Brak miejsc
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="background: var(--secondary-dark); padding: 2rem; border-radius: 8px; text-align: center;">
                            <i class="fas fa-calendar-times fa-3x" style="color: var(--text-gray); margin-bottom: 1rem;"></i>
                            <h3 style="color: var(--text-gray);">Brak dostępnych seansów</h3>
                            <p style="color: var(--text-gray); margin-top: 1rem;">Sprawdź inne filmy w repertuarze</p>
                            <a href="movies.php" class="btn btn-primary" style="margin-top: 1rem;">
                                <i class="fas fa-film"></i> Zobacz wszystkie filmy
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>