<?php
    session_start();

    $errorMessage = "";

    include '../functions/connect_db.php';
    $pdo->exec('USE clinic');
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"]) && isset($_POST["password"]) & !empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = trim($_POST["email"]);
        $password = $_POST["password"];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            if (md5($password) === $user['user_password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_fio'] = $user['fio'];

                if ($user['admin'] == 1) {
                    $_SESSION['admin'] = true;
                    header("Location: ../views/admin.php");
                    exit();
                } else {
                    header("Location: ../views/personal_account.php");
                    exit();
                }
            } else {
                $errorMessage = "Неверный логин или пароль";
            }
        } else {
            $errorMessage = "Пользователь с таким email не найден";
        }
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="../assets/styles/login_register.css">
<body>
    <main class="page__content">
        <h1 class="page__title">Вход</h1>

        <?php if ($errorMessage) { ?>
            <div class="error">
                <p><?= htmlspecialchars($errorMessage); ?></p>
            </div>
        <?php } ?>

        <form class="form login__form" action="login.php" method="post">
            <div class="form__box">
                <label class="form__label" for="email">Логин</label><br>
                <input class="form__input" type="text" id="email" name="email" placeholder="email@example.com" required>
            </div>
            <div class="form__box">
                <label class="form__label" for="password">Пароль</label><br>
                <input class="form__input" type="password" id="password" name="password" placeholder="qwerty12345" required>
            </div>
            <div class="form__button">
                <button class="button--primary" type="submit">Войти</button>
            </div>
            <div class="form__button">
                <a href="../views/register.php" class="button--secondary" type="submit">Ещё не зарегистрированы?</a>
            </div>
        </form>
    </main>
</body>
</html>