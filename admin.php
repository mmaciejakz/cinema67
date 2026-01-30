<?php 
// admin.php - NAPRAWIONY
session_start();

// NAJPIERW sprawdź sesję, POTEM połącz z bazą
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if(!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("Location: index.php");
    exit;
}

// Teraz dopiero łączymy z bazą
include 'connect.php';

$message = "";
$message_type = "";

// Dodawanie filmu
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_movie'])) {
    $tytul = mysqli_real_escape_string($conn, $_POST['tytul']);
    $autor = mysqli_real_escape_string($conn, $_POST['autor']);
    $kategoria = mysqli_real_escape_string($conn, $_POST['kategoria']);
    $zdjecie = mysqli_real_escape_string($conn, $_POST['zdjecie']);
    $opis = mysqli_real_escape_string($conn, $_POST['opis']);
    $czas_trwania = mysqli_real_escape_string($conn, $_POST['czas_trwania']);
    $ograniczenie_wiekowe = mysqli_real_escape_string($conn, $_POST['ograniczenie_wiekowe']);
    
    $sql = "INSERT INTO filmy (tytul, autor, id_kategorii, zdjecie, opis, czas_trwania, ograniczenie_wiekowe) 
            VALUES ('$tytul', '$autor', '$kategoria', '$zdjecie', '$opis', '$czas_trwania', '$ograniczenie_wiekowe')";
    
    if(mysqli_query($conn, $sql)) {
        $message = "Film dodany pomyślnie!";
        $message_type = "success";
    } else {
        $message = "Błąd podczas dodawania filmu: " . mysqli_error($conn);
        $message_type = "error";
    }
}

// Dodawanie seansu
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_seans'])) {
    $id_filmu = mysqli_real_escape_string($conn, $_POST['id_filmu']);
    $id_sali = mysqli_real_escape_string($conn, $_POST['id_sali']);
    $data = mysqli_real_escape_string($conn, $_POST['data']);
    $cena_biletu = mysqli_real_escape_string($conn, $_POST['cena_biletu']);
    
    $sql = "INSERT INTO seanse (id_filmu, id_sali, data, cena_biletu) 
            VALUES ('$id_filmu', '$id_sali', '$data', '$cena_biletu')";
    
    if(mysqli_query($conn, $sql)) {
        $message = "Seans dodany pomyślnie!";
        $message_type = "success";
    } else {
        $message = "Błąd podczas dodawania seansu: " . mysqli_error($conn);
        $message_type = "error";
    }
}

// Proste usuwanie filmu (bez procedur)
if(isset($_GET['delete_movie'])) {
    $movie_id = mysqli_real_escape_string($conn, $_GET['delete_movie']);
    $sql = "DELETE FROM filmy WHERE id_filmu = '$movie_id'";
    
    if(mysqli_query($conn, $sql)) {
        $message = "Film usunięty pomyślnie!";
        $message_type = "success";
        header("Location: admin.php?message=" . urlencode($message) . "&type=" . $message_type);
        exit;
    } else {
        $message = "Błąd podczas usuwania filmu";
        $message_type = "error";
    }
}

// Pobierz statystyki
$sql_filmy = "SELECT COUNT(*) as count FROM filmy";
$result = mysqli_query($conn, $sql_filmy);
$filmy_count = mysqli_fetch_assoc($result)['count'];

$sql_seanse = "SELECT COUNT(*) as count FROM seanse WHERE data >= NOW()";
$result = mysqli_query($conn, $sql_seanse);
$seanse_count = mysqli_fetch_assoc($result)['count'];

$sql_users = "SELECT COUNT(*) as count FROM users";
$result = mysqli_query($conn, $sql_users);
$users_count = mysqli_fetch_assoc($result)['count'];

$sql_reservations = "SELECT COUNT(*) as count FROM rezerwacje WHERE status = 'active'";
$result = mysqli_query($conn, $sql_reservations);
$reservations_count = mysqli_fetch_assoc($result)['count'];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administracyjny - Kino 67</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #0a0a0a;
            --secondary-dark: #1a1a1a;
            --primary-blue: #1e40af;
            --secondary-blue: #3b82f6;
            --accent-blue: #60a5fa;
            --text-light: #f8fafc;
            --text-gray: #94a3b8;
        }
        
        body {
            margin: 0;
            padding: 0;
            background: var(--primary-dark);
            color: var(--text-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: 250px;
            background: var(--secondary-dark);
            padding: 20px 0;
            border-right: 2px solid var(--primary-blue);
        }
        
        .admin-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #333;
            margin-bottom: 20px;
        }
        
        .sidebar-header h3 {
            color: var(--secondary-blue);
            margin: 0;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: var(--text-light);
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(30, 64, 175, 0.2);
            border-left: 4px solid var(--primary-blue);
        }
        
        .sidebar-menu i {
            width: 20px;
            text-align: center;
        }
        
        .content-header {
            margin-bottom: 30px;
        }
        
        .content-header h1 {
            color: var(--secondary-blue);
            margin-bottom: 10px;
        }
        
        .content-header p {
            color: var(--text-gray);
        }
        
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        
        .message.success {
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid #22c55e;
            color: #22c55e;
        }
        
        .message.error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid #ef4444;
            color: #ef4444;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: var(--secondary-dark);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #333;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-blue);
        }
        
        .stat-card i {
            font-size: 2rem;
            color: var(--accent-blue);
            margin-bottom: 10px;
        }
        
        .stat-card h3 {
            font-size: 2rem;
            color: var(--accent-blue);
            margin: 10px 0;
        }
        
        .stat-card p {
            color: var(--text-gray);
            margin: 0;
        }
        
        .section {
            background: var(--secondary-dark);
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #333;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .section-header h2 {
            color: var(--secondary-blue);
            margin: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--secondary-dark);
            border-radius: 8px;
            overflow: hidden;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #333;
        }
        
        th {
            background: var(--primary-dark);
            color: var(--secondary-blue);
            font-weight: 600;
        }
        
        tr:hover {
            background: rgba(30, 64, 175, 0.1);
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-gray);
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            background: var(--primary-dark);
            border: 1px solid #333;
            border-radius: 6px;
            color: white;
            font-size: 1rem;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-blue);
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .admin-sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 2px solid var(--primary-blue);
            }
            
            .sidebar-menu {
                display: flex;
                overflow-x: auto;
                white-space: nowrap;
            }
            
            .sidebar-menu li {
                margin-bottom: 0;
                margin-right: 10px;
            }
            
            .sidebar-menu a {
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-cog"></i> Panel Admina</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" onclick="showTab('dashboard')" class="active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a></li>
                <li><a href="#" onclick="showTab('movies')">
                    <i class="fas fa-film"></i> Filmy
                </a></li>
                <li><a href="#" onclick="showTab('seanse')">
                    <i class="fas fa-calendar-alt"></i> Seanse
                </a></li>
                <li><a href="#" onclick="showTab('categories')">
                    <i class="fas fa-tags"></i> Kategorie
                </a></li>
                <li><a href="#" onclick="showTab('users')">
                    <i class="fas fa-users"></i> Użytkownicy
                </a></li>
                <li><a href="#" onclick="showTab('reservations')">
                    <i class="fas fa-ticket-alt"></i> Rezerwacje
                </a></li>
                <li><a href="index.php" style="margin-top: 20px; color: var(--accent-blue);">
                    <i class="fas fa-arrow-left"></i> Powrót do kina
                </a></li>
            </ul>
        </div>
        
        <!-- Content -->
        <div class="admin-content">
            <div class="content-header">
                <h1><i class="fas fa-cog"></i> Panel administracyjny</h1>
                <p>Witaj, <?php echo htmlspecialchars($_SESSION['username']); ?>! Zarządzaj systemem kina.</p>
            </div>
            
            <?php if($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Dashboard -->
            <div id="dashboard" class="tab-content active">
                <div class="section">
                    <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <i class="fas fa-film"></i>
                            <h3><?php echo $filmy_count; ?></h3>
                            <p>Filmów w bazie</p>
                        </div>
                        
                        <div class="stat-card">
                            <i class="fas fa-calendar"></i>
                            <h3><?php echo $seanse_count; ?></h3>
                            <p>Nadchodzących seansów</p>
                        </div>
                        
                        <div class="stat-card">
                            <i class="fas fa-users"></i>
                            <h3><?php echo $users_count; ?></h3>
                            <p>Użytkowników</p>
                        </div>
                        
                        <div class="stat-card">
                            <i class="fas fa-ticket-alt"></i>
                            <h3><?php echo $reservations_count; ?></h3>
                            <p>Aktywnych rezerwacji</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filmy -->
            <div id="movies" class="tab-content">
                <div class="section">
                    <div class="section-header">
                        <h2><i class="fas fa-film"></i> Zarządzanie filmami</h2>
                        <button class="btn btn-primary" onclick="toggleMovieForm()">
                            <i class="fas fa-plus"></i> Dodaj film
                        </button>
                    </div>
                    
                    <!-- Formularz filmu -->
                    <div id="movieForm" style="display: none; margin-bottom: 30px; padding: 20px; background: var(--primary-dark); border-radius: 8px;">
                        <h3 style="color: var(--accent-blue); margin-bottom: 20px;">
                            <i class="fas fa-plus-circle"></i> Dodaj nowy film
                        </h3>
                        <form method="POST" action="">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="tytul">Tytuł *</label>
                                    <input type="text" name="tytul" id="tytul" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="autor">Reżyser *</label>
                                    <input type="text" name="autor" id="autor" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="kategoria">Kategoria *</label>
                                    <select name="kategoria" id="kategoria" required>
                                        <option value="">Wybierz kategorię</option>
                                        <?php
                                        $sql = "SELECT * FROM kategorie ORDER BY kategoria";
                                        $result = mysqli_query($conn, $sql);
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='{$row['id_kategorii']}'>{$row['kategoria']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="czas_trwania">Czas trwania (min) *</label>
                                    <input type="number" name="czas_trwania" id="czas_trwania" required min="60" max="240" value="120">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="zdjecie">Link do plakatu *</label>
                                <input type="text" name="zdjecie" id="zdjecie" required 
                                       placeholder="https://example.com/poster.jpg"
                                       value="https://via.placeholder.com/300x450?text=Brak+plakatu">
                            </div>
                            
                            <div class="form-group">
                                <label for="opis">Opis filmu *</label>
                                <textarea name="opis" id="opis" rows="5" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="ograniczenie_wiekowe">Ograniczenie wiekowe</label>
                                <select name="ograniczenie_wiekowe" id="ograniczenie_wiekowe">
                                    <option value="PG">PG</option>
                                    <option value="PG-13">PG-13</option>
                                    <option value="R">R</option>
                                    <option value="NC-17">NC-17</option>
                                    <option value="18+">18+</option>
                                </select>
                            </div>
                            
                            <input type="hidden" name="add_movie" value="1">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Dodaj film
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="toggleMovieForm()" style="margin-left: 10px;">
                                <i class="fas fa-times"></i> Anuluj
                            </button>
                        </form>
                    </div>
                    
                    <!-- Lista filmów -->
                    <h3 style="color: var(--accent-blue); margin-bottom: 20px;">Lista filmów</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tytuł</th>
                                <th>Reżyser</th>
                                <th>Kategoria</th>
                                <th>Czas</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT f.*, k.kategoria FROM filmy f 
                                    LEFT JOIN kategorie k ON f.id_kategorii = k.id_kategorii 
                                    ORDER BY f.id_filmu DESC";
                            $result = mysqli_query($conn, $sql);
                            
                            if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                        <td>{$row['id_filmu']}</td>
                                        <td><strong>{$row['tytul']}</strong></td>
                                        <td>{$row['autor']}</td>
                                        <td>{$row['kategoria']}</td>
                                        <td>{$row['czas_trwania']} min</td>
                                        <td class='action-buttons'>
                                            <a href='admin.php?delete_movie={$row['id_filmu']}' 
                                               class='btn btn-secondary btn-small'
                                               onclick='return confirm(\"Czy na pewno chcesz usunąć ten film?\")'>
                                                <i class='fas fa-trash'></i> Usuń
                                            </a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' style='text-align: center; padding: 20px; color: var(--text-gray);'>Brak filmów w bazie</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Seanse -->
            <div id="seanse" class="tab-content">
                <div class="section">
                    <div class="section-header">
                        <h2><i class="fas fa-calendar-alt"></i> Zarządzanie seansami</h2>
                        <button class="btn btn-primary" onclick="toggleSeansForm()">
                            <i class="fas fa-plus"></i> Dodaj seans
                        </button>
                    </div>
                    
                    <!-- Formularz seansu -->
                    <div id="seansForm" style="display: none; margin-bottom: 30px; padding: 20px; background: var(--primary-dark); border-radius: 8px;">
                        <h3 style="color: var(--accent-blue); margin-bottom: 20px;">
                            <i class="fas fa-plus-circle"></i> Dodaj nowy seans
                        </h3>
                        <form method="POST" action="">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="id_filmu">Film *</label>
                                    <select name="id_filmu" id="id_filmu" required>
                                        <option value="">Wybierz film</option>
                                        <?php
                                        $sql = "SELECT * FROM filmy ORDER BY tytul";
                                        $result = mysqli_query($conn, $sql);
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='{$row['id_filmu']}'>{$row['tytul']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="id_sali">Sala *</label>
                                    <select name="id_sali" id="id_sali" required>
                                        <option value="">Wybierz salę</option>
                                        <?php
                                        $sql = "SELECT * FROM sale ORDER BY sala";
                                        $result = mysqli_query($conn, $sql);
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='{$row['id_sali']}'>{$row['sala']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="data">Data i godzina *</label>
                                    <input type="datetime-local" name="data" id="data" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="cena_biletu">Cena biletu (zł) *</label>
                                    <input type="number" step="0.01" name="cena_biletu" id="cena_biletu" required min="10" max="100" value="25.00">
                                </div>
                            </div>
                            
                            <input type="hidden" name="add_seans" value="1">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Dodaj seans
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="toggleSeansForm()" style="margin-left: 10px;">
                                <i class="fas fa-times"></i> Anuluj
                            </button>
                        </form>
                    </div>
                    
                    <!-- Lista seansów -->
                    <h3 style="color: var(--accent-blue); margin-bottom: 20px;">Nadchodzące seanse</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Film</th>
                                <th>Sala</th>
                                <th>Data</th>
                                <th>Cena</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT s.*, f.tytul, sa.sala 
                                    FROM seanse s 
                                    LEFT JOIN filmy f ON s.id_filmu = f.id_filmu 
                                    LEFT JOIN sale sa ON s.id_sali = sa.id_sali 
                                    WHERE s.data >= NOW() 
                                    ORDER BY s.data";
                            $result = mysqli_query($conn, $sql);
                            
                            if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                        <td>{$row['id_seansu']}</td>
                                        <td><strong>{$row['tytul']}</strong></td>
                                        <td>{$row['sala']}</td>
                                        <td>" . date('d.m.Y H:i', strtotime($row['data'])) . "</td>
                                        <td>{$row['cena_biletu']} zł</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align: center; padding: 20px; color: var(--text-gray);'>Brak nadchodzących seansów</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Kategorie -->
            <div id="categories" class="tab-content">
                <div class="section">
                    <h2><i class="fas fa-tags"></i> Zarządzanie kategoriami</h2>
                    
                    <!-- Lista kategorii -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nazwa kategorii</th>
                                <th>Liczba filmów</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT k.*, COUNT(f.id_filmu) as liczba_filmow 
                                    FROM kategorie k 
                                    LEFT JOIN filmy f ON k.id_kategorii = f.id_kategorii 
                                    GROUP BY k.id_kategorii 
                                    ORDER BY k.kategoria";
                            $result = mysqli_query($conn, $sql);
                            
                            if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                        <td>{$row['id_kategorii']}</td>
                                        <td><strong>{$row['kategoria']}</strong></td>
                                        <td>{$row['liczba_filmow']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3' style='text-align: center; padding: 20px; color: var(--text-gray);'>Brak kategorii</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Użytkownicy -->
            <div id="users" class="tab-content">
                <div class="section">
                    <h2><i class="fas fa-users"></i> Zarządzanie użytkownikami</h2>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nazwa użytkownika</th>
                                <th>Email</th>
                                <th>Data rejestracji</th>
                                <th>Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM users ORDER BY id DESC";
                            $result = mysqli_query($conn, $sql);
                            
                            while($row = mysqli_fetch_assoc($result)) {
                                $admin_status = $row['admin'] == 1 ? 
                                    '<span style="color: #22c55e; font-weight: bold;">TAK</span>' : 
                                    '<span style="color: var(--text-gray);">NIE</span>';
                                
                                echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td><strong>{$row['username']}</strong></td>
                                    <td>{$row['email']}</td>
                                    <td>" . date('d.m.Y', strtotime($row['registration_date'])) . "</td>
                                    <td>{$admin_status}</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Rezerwacje -->
            <div id="reservations" class="tab-content">
                <div class="section">
                    <h2><i class="fas fa-ticket-alt"></i> Zarządzanie rezerwacjami</h2>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Użytkownik</th>
                                <th>Film</th>
                                <th>Data seansu</th>
                                <th>Kod</th>
                                <th>Cena</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT r.*, u.username, f.tytul, s.data 
                                    FROM rezerwacje r
                                    JOIN users u ON r.id_user = u.id
                                    JOIN seanse s ON r.id_seansu = s.id_seansu
                                    JOIN filmy f ON s.id_filmu = f.id_filmu
                                    ORDER BY r.data_rezerwacji DESC";
                            $result = mysqli_query($conn, $sql);
                            
                            if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    $status_color = $row['status'] == 'active' ? '#22c55e' : 
                                                   ($row['status'] == 'cancelled' ? '#ef4444' : '#f59e0b');
                                    $status_text = $row['status'] == 'active' ? 'Aktywna' : 
                                                  ($row['status'] == 'cancelled' ? 'Anulowana' : 'Wykorzystana');
                                    
                                    echo "<tr>
                                        <td>{$row['id_rezerwacji']}</td>
                                        <td>{$row['username']}</td>
                                        <td>{$row['tytul']}</td>
                                        <td>" . date('d.m.Y H:i', strtotime($row['data'])) . "</td>
                                        <td><code>{$row['kod_rezerwacji']}</code></td>
                                        <td>{$row['cena_laczna']} zł</td>
                                        <td><span style='color: $status_color; font-weight: bold;'>$status_text</span></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align: center; padding: 20px; color: var(--text-gray);'>Brak rezerwacji</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function showTab(tabId) {
            // Ukryj wszystkie zakładki
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Pokaż wybraną zakładkę
            document.getElementById(tabId).classList.add('active');
            
            // Aktualizuj aktywne linki
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.classList.remove('active');
            });
            event.target.classList.add('active');
        }
        
        function toggleMovieForm() {
            const form = document.getElementById('movieForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
        
        function toggleSeansForm() {
            const form = document.getElementById('seansForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
            
            // Ustaw domyślną datę (jutro o 18:00)
            if(form.style.display === 'block') {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                tomorrow.setHours(18, 0, 0, 0);
                
                const dateInput = document.getElementById('data');
                const year = tomorrow.getFullYear();
                const month = String(tomorrow.getMonth() + 1).padStart(2, '0');
                const day = String(tomorrow.getDate()).padStart(2, '0');
                const hours = String(tomorrow.getHours()).padStart(2, '0');
                const minutes = String(tomorrow.getMinutes()).padStart(2, '0');
                
                dateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
            }
        }
        
        // Ustaw pierwszą zakładkę jako aktywną
        document.addEventListener('DOMContentLoaded', function() {
            showTab('dashboard');
        });
    </script>
</body>
</html>