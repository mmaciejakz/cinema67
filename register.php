<?php 
// register.php - NAPRAWIONY
session_start();
include 'connect.php';

// Jeśli użytkownik jest już zalogowany, przekieruj
if (isset($_SESSION["logged_in"]) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = "";
$success = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST["confirmPassword"]);  
    
    // Walidacja
    if(empty($username) || empty($email) || empty($password)) {
        $error = "Wszystkie pola są wymagane";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Podaj poprawny adres email";
    } elseif(strlen($password) < 6) {
        $error = "Hasło musi mieć co najmniej 6 znaków";
    } elseif($password !== $confirmPassword){
        $error = "Hasła różnią się";  
    } else {
        // Sprawdź czy użytkownik już istnieje
        $sql = "SELECT * FROM `users` WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($conn, $sql);    
        
        if(mysqli_num_rows($result) > 0){
            $error = "Nazwa użytkownika lub email jest już zajęty";
        } else {
            // Rejestracja użytkownika
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` (`username`, `password`, `email`) 
                    VALUES ('$username','$hashedPassword','$email')";
            
            if(mysqli_query($conn, $sql)){
                $success = "Konto zostało utworzone pomyślnie! Za chwilę zostaniesz przekierowany...";
                
                // Pobierz ID nowego użytkownika
                $new_user_id = mysqli_insert_id($conn);
                
                // Automatyczne logowanie po rejestracji
                $_SESSION["logged_in"] = true;
                $_SESSION["username"] = $username;
                $_SESSION["user_id"] = $new_user_id;
                $_SESSION["email"] = $email;
                $_SESSION["admin"] = 0;
                
                // Przekierowanie po 2 sekundach
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "index.php";
                    }, 2000);
                </script>';
                
            } else {
                $error = "Nie udało się zarejestrować użytkownika: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja - Kino 67</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <style>
        body {
            background: var(--primary-dark);
            color: var(--text-light);
            min-height: 100vh;
        }
        
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }
        
        .register-card {
            background: var(--secondary-dark);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid #333;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-header h1 {
            color: var(--secondary-blue);
            margin-bottom: 0.5rem;
        }
        
        .register-header p {
            color: var(--text-gray);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-light);
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.8rem;
            background: var(--primary-dark);
            border: 1px solid #333;
            border-radius: 6px;
            color: white;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-blue);
        }
        
        .password-requirements {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
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
        
        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-gray);
        }
        
        .form-footer a {
            color: var(--secondary-blue);
            text-decoration: none;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php 
    // Używamy tej samej metody co w login.php
    $show_header = true;
    include 'header.php'; 
    ?>
    
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h1><i class="fas fa-user-plus"></i> Rejestracja</h1>
                <p>Utwórz konto, aby móc rezerwować bilety</p>
            </div>
            
            <?php if(!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if(empty($success)): ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" required 
                           placeholder="twoj@email.pl">
                </div>
                
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Nazwa użytkownika</label>
                    <input type="text" name="username" id="username" required 
                           placeholder="Wybierz nazwę użytkownika">
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Hasło</label>
                    <input type="password" name="password" id="password" required 
                           placeholder="Minimum 6 znaków">
                    <div class="password-requirements">
                        Hasło musi mieć co najmniej 6 znaków
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword"><i class="fas fa-lock"></i> Potwierdź hasło</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" required 
                           placeholder="Wpisz ponownie hasło">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem;">
                    <i class="fas fa-user-plus"></i> Zarejestruj się
                </button>
            </form>
            
            <div class="form-footer">
                <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
                <p><a href="index.php"><i class="fas fa-arrow-left"></i> Powrót do strony głównej</a></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>