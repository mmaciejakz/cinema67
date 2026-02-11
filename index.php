<?php 
// index.php - PROSTA WERSJA STARTU
session_start();
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kino 67 - Strona główna</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <style>
        .hero {
            background: linear-gradient(rgba(10, 10, 10, 0.9), rgba(10, 10, 10, 0.9)), 
                        url('https://images.unsplash.com/photo-1440404653325-ab127d49abc1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            padding: 80px 20px;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #3b82f6;
        }
        
        .hero p {
            font-size: 1.2em;
            color: #94a3b8;
            max-width: 600px;
            margin: 0 auto 30px;
        }
        
        .movies-section {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            color: #3b82f6;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .movies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .movie-card {
            background: #1a1a1a;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .movie-card:hover {
            transform: translateY(-5px);
        }
        
        .movie-poster {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        
        .movie-info {
            padding: 15px;
        }
        
        .movie-title {
            color: white;
            margin: 0 0 10px 0;
            font-size: 1.1em;
        }
        
        .movie-meta {
            color: #94a3b8;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        
        .category-badge {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.8em;
            margin-right: 5px;
        }
        
        .about-section {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .about-content {
            display: flex;
            gap: 40px;
            align-items: center;
        }
        
        .about-text {
            flex: 1;
        }
        
        .about-image {
            flex: 1;
        }
        
        .about-image img {
            width: 100%;
            border-radius: 10px;
        }
        
        @media (max-width: 768px) {
            .about-content {
                flex-direction: column;
            }
            
            .hero h1 {
                font-size: 2em;
            }
            
            .movies-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php 
    $show_header = true;
    include 'header.php'; 
    ?>
    
    <section class="hero">
        <h1>Doświadcz Magii Kina</h1>
        <p>Najnowsze premiery, komfortowe sale i niezapomniane wrażenia. Zarezerwuj bilety online już teraz!</p>
        <a href="movies.php" class="btn btn-primary">Zobacz repertuar</a>
    </section>
    
    <section class="movies-section">
        <h2 class="section-title">Aktualnie w kinie</h2>
        
        <div class="movies-grid">
            <?php
            $sql = "SELECT f.*, k.kategoria FROM filmy f 
                    LEFT JOIN kategorie k ON f.id_kategorii = k.id_kategorii 
                    ORDER BY f.id_filmu DESC LIMIT 6";
            $result = mysqli_query($conn, $sql);
            
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="movie-card">
                        <img src="' . $row['zdjecie'] . '" alt="' . $row['tytul'] . '" class="movie-poster"
                             onerror="this.src=\'https://via.placeholder.com/300x450?text=Brak+plakatu\'">
                        <div class="movie-info">
                            <h3 class="movie-title">' . $row['tytul'] . '</h3>
                            <div class="movie-meta">
                                <span><i class="fas fa-user"></i> ' . $row['autor'] . '</span>
                                <span class="category-badge">' . $row['kategoria'] . '</span>
                            </div>
                            <p style="color: #94a3b8; font-size: 0.9em; margin-bottom: 15px;">
                                ' . substr($row['opis'], 0, 100) . '...
                            </p>
                            <a href="movie.php?id=' . $row['id_filmu'] . '" class="btn btn-primary" style="width: 100%;">
                                <i class="fas fa-ticket-alt"></i> Zobacz seanse
                            </a>
                        </div>
                    </div>';
                }
            } else {
                echo '<p style="text-align: center; color: #94a3b8; grid-column: 1/-1;">Brak filmów w repertuarze</p>';
            }
            ?>
        </div>
        
        <div style="text-align: center;">
            <a href="movies.php" class="btn btn-secondary">
                <i class="fas fa-list"></i> Zobacz wszystkie filmy
            </a>
        </div>
    </section>
    
    <section class="about-section">
        <h2 class="section-title">O naszym kinie</h2>
        <div class="about-content">
            <div class="about-text">
                <p style="color: #94a3b8; line-height: 1.6; margin-bottom: 20px;">
                    Kino 67 to nowoczesne kino, które oferuje najlepsze doświadczenia filmowe. 
                    Dysponujemy komfortowymi salami wyposażonymi w najnowocześniejsze systemy dźwięku i obrazu.
                </p>
                <ul style="color: #94a3b8; list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;"><i class="fas fa-check" style="color: #3b82f6; margin-right: 10px;"></i> Najnowsze premiery filmowe</li>
                    <li style="margin-bottom: 10px;"><i class="fas fa-check" style="color: #3b82f6; margin-right: 10px;"></i> Komfortowe fotele</li>
                    <li style="margin-bottom: 10px;"><i class="fas fa-check" style="color: #3b82f6; margin-right: 10px;"></i> System rezerwacji online</li>
                    <li><i class="fas fa-check" style="color: #3b82f6; margin-right: 10px;"></i> Bar z przekąskami i napojami</li>
                </ul>
            </div>
            <div class="about-image">
                <img src="https://img.freepik.com/free-photo/3d-cinema-theatre-room-with-seating_23-2151005451.jpg" 
                     alt="Nasze kino">
            </div>
        </div>
    </section>
    
    <?php include 'footer.php'; ?>
</body>
</html>