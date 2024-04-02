<?php 
$success = false;
require 'config.php';

if (isset($_POST['submit'])&& $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['pseudo']) && !empty($_POST['password']) && !empty($_POST['password-confirm'])) {
        $email = $_POST['email'];
        $username = htmlspecialchars($_POST['pseudo']);
        $password = $_POST['password'];
        $confirm = $_POST['password-confirm'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "L'email n'est pas au bon format";
        } elseif ($password != $confirm) {
            $error = "Les mots de passe doivent être identiques";
        } else {
            if (!preg_match('/[A-Z]/', $password)) {
                $error = "Le mot de passe doit contenir au moins une majuscule";
            } elseif (!preg_match('/[a-z]/', $password)) {
                $error = "Le mot de passe doit contenir au moins une minuscule";
            } elseif (!preg_match('/[0-9]/', $password)) {
                $error = "Le mot de passe doit contenir au moins un chiffre";
            } elseif (!preg_match('/[#?!@$ %^&*-]/', $password)) {
                $error = "Le mot de passe doit contenir au moins un caractère spécial";
            } elseif (strlen($password) < 8) {
                $error = "Le mot de passe doit contenir au moins 8 caractères";
            } else {
                // Hacher le mot de passe
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insérez les informations dans la base de données
                $stmt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
                $stmt->execute([$email, $username, $hashed_password]);
                $success = true;

                if ($success) {
                    // Démarrer la session et stocker le nom d'utilisateur
                    session_start();
                    $_SESSION['username'] = $username;

                    // Redirection vers la page d'accueil
                    header('Location: index.php');
                    exit();
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    <h1><a href="index.php" style="text-decoration: none; color: inherit; background: none;">Bibliothèque</a></h1>
    </header>
    <div class="signup-form">
        <form method="POST">
            <div>
                <label for="email">Email address</label><br>
                <input id="email" name="email" type="text" autocomplete="email" required>
            </div>
            <div>
                <label for="pseudo">Pseudo</label><br>
                <input id="pseudo" name="pseudo" type="text" autocomplete="pseudo" required>
            </div>
            <div>
                <label for="password">Password</label><br>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
            <div>
                <label for="password-confirm">Password Confirmation</label><br>
                <input id="password-confirm" name="password-confirm" type="password" autocomplete="current-password" required>
            </div>
            <?php if (isset($error)) : ?>
                <p style='color:red;'><?= $error ?></p>
            <?php endif ?>
            <div>
                <input type="submit" name="submit" value="Signup">
            </div>
        </form>
    </div>
</body>
</html>