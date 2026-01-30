<?php 
// login.php - NAPRAWIONY
// SESJA MUSI BYĆ NA SAMYM CZUBKU!
session_start();
include 'connect.php';

// Jeśli użytkownik jest już zalogowany, przekieruj
if (isset($_SESSION["logged_in"]) && $_SESSION['logged_in'] === true) {
    if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
        header("Location: admin.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$error = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $sql = "SELECT * FROM `users` WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user["password"])) {
            // USTAW SESJĘ POPRAWNIE
            $_SESSION["logged_in"] = true;
            $_SESSION["username"] = $user['username'];
            $_SESSION["user_id"] = $user['id'];
            $_SESSION["email"] = $user['email'];
            $_SESSION["admin"] = $user['admin'];
            
            // Debug - pokaż co jest w sesji
            // echo "<pre>"; print_r($_SESSION); echo "</pre>";
            
            if($user["admin"] == 1) {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Hasło niepoprawne";
        }
    } else {
        $error = "Nie znaleziono użytkownika o podanej nazwie";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie - Kino 67</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .auth-container {
            min-height: calc(100vh - 300px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .auth-card {
            background: var(--secondary-dark);
            border-radius: 12px;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid #333;
        }
        
        .auth-card h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--secondary-blue);
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
        
        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-gray);
        }
        
        .auth-links a {
            color: var(--secondary-blue);
            text-decoration: none;
        }
        
        .auth-links a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid #ef4444;
            color: #ef4444;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Wstawiamy header.php bez session_start -->
    <?php 
    // Usuwamy session_start z include, bo już jest na górze
    $show_header = true;
    include 'header.php'; 
    ?>
    
    <div class="auth-container">
        <div class="auth-card">
            <h1><i class="fas fa-sign-in-alt"></i> Logowanie</h1>
            
            <?php if(!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="POST">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Nazwa użytkownika</label>
                    <input type="text" name="username" id="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Hasło</label>
                    <input type="password" name="password" id="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem;">
                    <i class="fas fa-sign-in-alt"></i> Zaloguj się
                </button>
            </form>
            
            <div class="auth-links">
                <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
                <p><a href="index.php"><i class="fas fa-arrow-left"></i> Powrót do strony głównej</a></p>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>