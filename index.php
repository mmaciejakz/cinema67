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
        
        .movie-card .btn {
            opacity: 0;
            transform: translateY(20px);
            transition: 0.3s ease;
}

        .movie-card:hover .btn {
            opacity: 1;
            transform: translateY(0);
        }
        .movie-card::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            pointer-events: none;
        }

        .movie-poster {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        
        .movie-info {
            padding: 15px;
            transform: translateZ(40px);
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
        .movies-carousel-section {
    padding: 60px 20px;
    background-color: #0f172a;
}

.carousel-container {
    position: relative;  
    overflow: hidden;
    padding: 20px 0;
}

.carousel-controls {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between; 
    padding: 0 10px;
    z-index: 5;
    pointer-events: none; 
}

.carousel-btn {
    pointer-events: auto; 
    background: rgba(0, 0, 0, 0.9);
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    font-size: 1.6em;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    transition: 0.3s ease;
    padding-bottom: 8.5px;
}

.carousel-btn:hover {
    background:#3b82f6;
    transform: scale(1.1);
}


.carousel-track {
    display: flex;
    gap: 20px;
    transition: transform 0.5s ease-in-out;
}

.carousel-slide {
    min-width: 280px;
    height: 700px; 
    perspective: 1000px; 
}

.movie-card {
    background: linear-gradient(145deg, #1e293b, #0f172a);
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    transform-style: preserve-3d;
    position: relative;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.movie-card:hover {
    transform: rotateY(6deg) rotateX(4deg) scale(1.05);
    box-shadow: 
        0 20px 40px rgba(0,0,0,0.7),
        0 0 20px rgba(59,130,246,0.4);
}


.movie-poster {
    width: 100%;
    height: 420px;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.movie-card:hover .movie-poster {
    transform: scale(1.08);
}

.movie-info {
    padding: 15px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.movie-title {
    min-height: 48px; /* rezerwuje miejsce na 2 linie tytułu */
}

.movie-meta {
    min-height: 40px;
}

.movie-info p {
    flex-grow: 1;   /* opis rozciąga przestrzeń */
}
.premiere-section {
    position: relative;
    margin-bottom: 60px;
    padding-top: 60px;
}

.premiere-banner {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    max-width: 1800px;
    margin: 0 auto;
}

.premiere-banner img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.premiere-banner:hover img {
    transform: scale(1.05);
}

.premiere-info {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    text-align: center;
    backdrop-filter: brightness(0.35);
    padding: 30px;
    border-radius: 15px;
}

.premiere-info h2 {
    font-size: 2.5em;
    margin-bottom: 15px;
    color: #3b82f6;
}

.premiere-info p {
    font-size: 1.2em;
    margin-bottom: 20px;
}

.countdown {
    display: flex;
    justify-content: center;
    gap: 15px;
    font-size: 1.2em;
    margin-bottom: 20px;
}

.countdown div {
    background: rgba(0,0,0,0.6);
    padding: 10px 15px;
    border-radius: 10px;
}

.pulsate {
    display: inline-block;
    padding: 12px 25px;
    font-size: 1.2em;
    border-radius: 50px;
    background: #3b82f6;
    color: #fff;
    text-decoration: none;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); box-shadow: 0 0 0 rgba(59,130,246,0.7); }
    50% { transform: scale(1.05); box-shadow: 0 0 15px rgba(59,130,246,0.9); }
    100% { transform: scale(1); box-shadow: 0 0 0 rgba(59,130,246,0.7); }
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
    <section class="movies-carousel-section">
    <h2 class="section-title">Aktualnie w kinie</h2>

    <?php
    $sql = "SELECT f.*, k.kategoria FROM filmy f 
            LEFT JOIN kategorie k ON f.id_kategorii = k.id_kategorii 
            ORDER BY f.id_filmu DESC LIMIT 6";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0):

        $movies = [];
        while($row = mysqli_fetch_assoc($result)) {
            $movies[] = $row;
        }
    ?>
    
    <div class="carousel-container">
        <div class="carousel-track" id="movieCarousel">
            <?php

            for ($i = 0; $i < 2; $i++):
                foreach($movies as $row):
            ?>
                <div class="carousel-slide">
                    <div class="movie-card">
                        <img src="<?= htmlspecialchars($row['zdjecie']) ?>"
                             alt="<?= htmlspecialchars($row['tytul']) ?>"
                             class="movie-poster"
                             onerror="this.src='https://via.placeholder.com/300x450?text=Brak+plakatu'">

                        <div class="movie-info">
                            <h3 class="movie-title"><?= htmlspecialchars($row['tytul']) ?></h3>

                            <div class="movie-meta">
                                <span><i class="fas fa-user"></i> <?= htmlspecialchars($row['autor']) ?></span>
                                <span class="category-badge"><?= htmlspecialchars($row['kategoria']) ?></span>
                            </div>

                            <p style="color: #94a3b8; font-size: 0.9em; margin-bottom: 15px;">
                                <?= substr($row['opis'], 0, 100) ?>...
                            </p>

                            <a href="movie.php?id=<?= $row['id_filmu'] ?>" class="btn btn-primary" style="width: 100%;">
                                <i class="fas fa-ticket-alt"></i> Zobacz seanse
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                endforeach;
            endfor;
            ?>
        </div>

        <div class="carousel-controls">
            <button class="carousel-btn prev-btn" onclick="moveCarousel(1)">‹</button>
            <button class="carousel-btn next-btn" onclick="moveCarousel(-1)">›</button>
        </div>
    </div>

    <?php else: ?>
        <p style="text-align:center; color:#94a3b8;">Brak filmów w repertuarze</p>
    <?php endif; ?>
    </section>
       <section class="premiere-section">
    <div class="premiere-banner">
        <img src="https://movienews.pl/wp-content/uploads/2022/03/Szybcy-i-wsciekli-po-kolei.jpg" 
             alt="Premiera tygodnia">
        <div class="premiere-info">
            <h2>Premiera tygodnia: <span>Super Film 2026</span></h2>
            <p>Nie przegap największego hitu roku w naszym kinie!</p>
            <div class="countdown">
                <div><span id="days">0</span>Dni</div>
                <div><span id="hours">0</span>Godz</div>
                <div><span id="minutes">0</span>Min</div>
                <div><span id="seconds">0</span>Sek</div>
            </div>
            <a href="movie.php?id=1" class="btn btn-primary pulsate">Kup bilet</a>
        </div>
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
<script>
let currentPosition = 0;
let slideWidth = 320;
let autoScrollInterval;
const carouselTrack = document.getElementById('movieCarousel');

function moveCarousel(direction) {
    stopAutoScroll();

    const maxPosition = -slideWidth * (carouselTrack.children.length / 2);
    currentPosition += direction * slideWidth;

    if (currentPosition > 0) {
        currentPosition = maxPosition;
    }

    if (currentPosition < maxPosition) {
        currentPosition = 0;
    }

    carouselTrack.style.transform = `translateX(${currentPosition}px)`;
    setTimeout(startAutoScroll, 5000);
}

function startAutoScroll() {
    if (autoScrollInterval) clearInterval(autoScrollInterval);
    autoScrollInterval = setInterval(() => {
        moveCarousel(-1);
    }, 3000);
}

function stopAutoScroll() {
    if (autoScrollInterval) {
        clearInterval(autoScrollInterval);
        autoScrollInterval = null;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    startAutoScroll();
});
</script>
<script>
// ustaw datę premiery
const premiereDate = new Date("2026-02-20T20:00:00").getTime();

const countdownInterval = setInterval(() => {
    const now = new Date().getTime();
    const distance = premiereDate - now;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("days").innerText = days;
    document.getElementById("hours").innerText = hours;
    document.getElementById("minutes").innerText = minutes;
    document.getElementById("seconds").innerText = seconds;

    if (distance < 0) {
        clearInterval(countdownInterval);
        document.querySelector(".countdown").innerHTML = "Premiera już trwa!";
    }
}, 1000);
</script>
</body>

</body>
</html>