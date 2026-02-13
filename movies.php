<?php
session_start();
include 'connect.php';

$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$sql = "SELECT f.*, k.kategoria FROM filmy f 
        LEFT JOIN kategorie k ON f.id_kategorii = k.id_kategorii 
        WHERE 1=1";

if($category_filter) {
    $sql .= " AND f.id_kategorii = '$category_filter'";
}

if($search_query) {
    $sql .= " AND (f.tytul LIKE '%$search_query%' OR f.autor LIKE '%$search_query%' OR f.opis LIKE '%$search_query%')";
}

$sql .= " ORDER BY f.tytul";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repertuar filmów - Kino 67</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <style>
        .movies-header {
            padding: 3rem 0 2rem;
            text-align: center;
        }
        
        .search-container {
            max-width: 600px;
            margin: 0 auto 2rem;
            display: flex;
            gap: 1rem;
        }
        
        .search-input {
            flex: 1;
            padding: 0.8rem 1.2rem;
            background: var(--secondary-dark);
            border: 1px solid #333;
            border-radius: 6px;
            color: white;
            font-size: 1rem;
        }
        
        .categories-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .category-btn {
            padding: 0.5rem 1.2rem;
            background: var(--secondary-dark);
            border: 1px solid #333;
            border-radius: 20px;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .category-btn:hover,
        .category-btn.active {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <div class="movies-header">
            <h1 style="color: var(--secondary-blue); margin-bottom: 1rem;">
                <i class="fas fa-film"></i> Repertuar filmów
            </h1>
            <p style="color: var(--text-gray); max-width: 600px; margin: 0 auto;">
                Wybierz film, który Cię interesuje i zarezerwuj bilety online
            </p>
        </div>
        
        
        <div class="categories-filter">
            <a href="movies.php" class="category-btn <?php echo !$category_filter ? 'active' : ''; ?>">
                Wszystkie
            </a>
            <?php
            $sql_categories = "SELECT * FROM kategorie ORDER BY kategoria";
            $result_categories = mysqli_query($conn, $sql_categories);
            while($cat = mysqli_fetch_assoc($result_categories)) {
                $active = $category_filter == $cat['id_kategorii'] ? 'active' : '';
                echo "<a href='movies.php?category={$cat['id_kategorii']}' class='category-btn $active'>
                        {$cat['kategoria']}
                      </a>";
            }
            ?>
        </div>
        
        <div style="margin-bottom: 2rem; color: var(--text-gray);">
            <?php
            $count = mysqli_num_rows($result);
            echo "Znaleziono: <strong>$count</strong> filmów";
            if($search_query) {
                echo " dla frazy: <strong>" . htmlspecialchars($search_query) . "</strong>";
            }
            ?>
        </div>
        

        <div class="movies-grid">
            <?php
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
         
                    $sql_seanse = "SELECT COUNT(*) as liczba_seansow FROM seanse 
                                   WHERE id_filmu = '{$row['id_filmu']}' AND data >= NOW()";
                    $result_seanse = mysqli_query($conn, $sql_seanse);
                    $seanse_data = mysqli_fetch_assoc($result_seanse);
                    $has_seanse = $seanse_data['liczba_seansow'] > 0;
                    
                    echo "
                    <div class='movie-card'>
                        <div class='movie-poster-container'>
                            <img src='{$row['zdjecie']}' alt='{$row['tytul']}' class='movie-poster' 
                                 onerror=\"this.src='https://via.placeholder.com/300x450?text=Brak+plakatu'\">
                            <div class='movie-age-restriction'>
                                {$row['ograniczenie_wiekowe']}
                            </div>
                        </div>
                        <div class='movie-info'>
                            <h3 class='movie-title'>{$row['tytul']}</h3>
                            <div class='movie-meta'>
                                <span><i class='fas fa-user'></i> {$row['autor']}</span>
                                <span class='category-badge'>{$row['kategoria']}</span>
                            </div>
                            <div class='movie-details'>
                                <span><i class='fas fa-clock'></i> {$row['czas_trwania']} min</span>
                            </div>
                            <p class='movie-description'>
                                " . substr($row['opis'], 0, 100) . "..."
                            . "</p>
                            <div style='display: flex; gap: 0.5rem;'>
                                <a href='movie.php?id={$row['id_filmu']}' class='btn btn-primary' style='flex: 1;'>
                                    " . ($has_seanse ? 'Rezerwuj' : 'Szczegóły') . "
                                </a>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<div style='grid-column: 1/-1; text-align: center; padding: 4rem;'>
                        <i class='fas fa-film fa-3x' style='color: var(--text-gray); margin-bottom: 1rem;'></i>
                        <h3 style='color: var(--text-gray);'>Nie znaleziono filmów</h3>
                        <p style='color: var(--text-gray); margin-top: 1rem;'>Spróbuj zmienić kryteria wyszukiwania</p>
                        <a href='movies.php' class='btn btn-primary' style='margin-top: 1rem;'>
                            <i class='fas fa-undo'></i> Wyczyść filtry
                        </a>
                      </div>";
            }
            ?>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
