<?php
    session_start();
    $errors = [];

    include '../functions/connect_db.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["fio"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm_password"]) 
        && !empty($_POST['fio']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        $fio = trim($_POST["fio"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if (!preg_match('/^[А-Яа-яЁё\s]+$/u', $fio)) {
            $errors['fio'] ='ФИО может содержать только кириллицу и пробелы';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный формат электронной почты';
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $errors['email'] = 'Электронная почта уже зарегистрирована';
            }
        }

        if (strlen($password) < 6 || !preg_match('/[A-Z]/', $password)) {
            $errors['password'] = 'Пароль должен содержать не менее 6 символов английской раскладки и включать заглавные буквы';
        }

        if ($password !== $confirm_password) {
            $errors['confirm_password'] = 'Пароли не совпадают';
        }

        if (empty($errors)) {
            $hashedPassword = md5($password);
            $stmt = $pdo->prepare("INSERT INTO users (fio, email, user_password) VALUES (:fio, :email, :user_password)");
            $stmt->execute([
                'fio' => $fio,
                'email' => $email,
                'user_password' => $hashedPassword
            ]);

            header("Location: ../views/login.php"); 
            exit();  
        }
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../assets/styles/login_register.css">
<body>
    <main class="page__content">
        <h1 class="page__title">Регистрация</h1>
        <form class="form register__form" action="../views/register.php" method="post">
            <div class="form__box">
                <label class="form__label" for="fio">ФИО</label><br>
                <input class="form__input" type="text" id="fio" name="fio" placeholder="Фамилия Имя Отчество" value="<?= isset($fio) ? htmlspecialchars($fio) : ''; ?>" required>
                <?php if (isset($errors['fio'])) { ?>
                    <p class="error"><?= htmlspecialchars($errors['fio']); ?></p>
                <?php } ?>
            </div>
            <div class="form__box">
                <label class="form__label" for="email">Электронная почта</label><br>
                <input class="form__input" type="email" id="email" name="email" placeholder="email@example.com" value="<?= isset($email) ? htmlspecialchars($email) : ''; ?>" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" required>
                <?php if (isset($errors['email'])) { ?>
                    <p class="error"><?= htmlspecialchars($errors['email']); ?></p>
                <?php } ?>
            </div>
            <div class="form__group">
                <div class="form__box">
                    <label class="form__label" for="password">Пароль</label><br>
                    <input class="form__input" type="password" id="password" name="password" placeholder="Минимум 6 символов" pattern="" required>
                    <?php if (isset($errors['password'])) { ?>
                        <p class="error"><?= htmlspecialchars($errors['password']); ?></p>
                    <?php } ?>
                </div>
                <div class="form__box">
                    <label class="form__label" for="confirm_password">Повторите пароль</label><br>
                    <input class="form__input" type="password" id="confirm_password" name="confirm_password" required>
                    <?php if (isset($errors['confirm_password'])) { ?>
                        <p class="error"><?= htmlspecialchars($errors['confirm_password']); ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="form__button">
                <button class="button--primary" type="submit">Зарегистрироваться</button>
            </div>
        </form>
    </main>
</body>
</html>