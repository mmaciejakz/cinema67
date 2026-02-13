<?php
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kino 67</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <style>
        .nav-links{
            display: flex;
            align-items: center;
            gap: 20px;
            white-space: nowrap;
        }
    </style>
</head>
<body>

<header>
    <div class="container">
        <nav>
            <a href="index.php" class="logo">KINO<span>67</span></a>
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> Strona główna</a></li>
                <li><a href="movies.php"><i class="fas fa-film"></i> Repertuar</a></li>
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <li><a href="user_reservations.php"><i class="fas fa-ticket-alt"></i> Moje rezerwacje</a></li>
                    <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1): ?>
                        <li><a href="admin.php"><i class="fas fa-cog"></i> Panel admina</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                <li><a href="#kontakt"><i class="fas fa-phone"></i> Kontakt</a></li>
            </ul>
            <div class="auth-buttons">
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <span class="user-welcome" style="display: flex; align-items: center; gap: 5px; margin-right: 10px;">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <a href="logout.php" class="btn btn-secondary">
                        <i class="fas fa-sign-out-alt"></i> Wyloguj
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-secondary">
                        <i class="fas fa-sign-in-alt"></i> Zaloguj
                    </a>
                    <a href="register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Zarejestruj
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
