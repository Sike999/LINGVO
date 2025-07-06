<?php

require_once 'db_connect.php';

function validateData($type, $data, $pdo) {
    $errors = [];

    switch ($type) {
        case 'book':
            if (!preg_match('/^[A-Za-z]{2}\d{3}$/', $data['code'])) {
                $errors[] = "Ошибка валидации кода книги. Он должен быть в формате 'XX123'.";
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM books WHERE code = ?");
                $stmt->execute([$data['code']]);
                if ($stmt->fetchColumn() > 0) {
                    $errors[] = "Код книги уже существует в базе данных.";
                }
            }

            if (empty($data['title'])) {
                $errors[] = "Название книги не может быть пустым.";
            } else {
                $title = $data['title'];
                if (preg_match('/[^\x20-\x7EА-Яа-яЁё]/u', $title)) {
                    $errors[] = "Название книги должно содержать только ASCII и русские символы.";
                }
            }

            if (empty($data['author'])) {
                $errors[] = "Имя автора не может быть пустым.";
            } else {
                if (empty($data['author']) || !preg_match('/^[A-Za-zА-Яа-яЁё\-]+$/u', $data['author'])) {
                    $errors[] = "Имя автора должно содержать только буквы (латиница или кириллица) и может включать дефис.";
                }
            
            }
            break;

        case 'client':
            if (!preg_match('/^[A-Za-z]\d{3}$/', $data['code'])) {
                $errors[] = "Ошибка валидации кода клиента. Он должен быть в формате 'X123'.";
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE code = ?");
                $stmt->execute([$data['code']]);
                if ($stmt->fetchColumn() > 0) {
                    $errors[] = "Код клиента уже существует в базе данных.";
                }
            }

            if (empty($data['surname']) || !preg_match('/^[A-Za-zА-Яа-яЁё\-]+$/u', $data['surname'])) {
                $errors[] = "Ошибка валидации фамилии клиента. Фамилия должна содержать только буквы (латиница или кириллица) и может включать дефис или апостроф.";
            }
            
            
            break;

        case 'loan':
            if ($data['issue_date'] > date('Y-m-d')) {
                $errors[] = "Дата выдачи не может быть больше текущей.";
            }
            if (!is_numeric($data['loan_term']) || $data['loan_term'] <= 0) {
                $errors[] = "Срок займа должен быть положительным числом.";
            }
            break;
    }

    return $errors;
}


$errors = [];
$book_data = $client_data = $loan_data = [];

if (isset($_POST['add_book'])) {
    $book_data = [
        'code' => $_POST['book_code'],
        'title' => $_POST['book_title'],
        'author' => $_POST['book_author']
    ];

    $errors = validateData('book', $book_data, $pdo);

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO books (code, title, author) VALUES (?, ?, ?)");
        $stmt->execute([$book_data['code'], $book_data['title'], $book_data['author']]);
    }
}

if (isset($_POST['add_loan'])) {
    $loan_data = [
        'book_id' => $_POST['loan_book_id'],
        'client_id' => $_POST['loan_client_id'],
        'issue_date' => $_POST['loan_issue_date'],
        'loan_term' => $_POST['loan_term']
    ];

    $errors = validateData('loan', $loan_data, $pdo);

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO loans (book_id, client_id, issue_date, loan_term) VALUES (?, ?, ?, ?)");
        $stmt->execute([$loan_data['book_id'], $loan_data['client_id'], $loan_data['issue_date'], $loan_data['loan_term']]);
    }
}

if (isset($_POST['add_client'])) {
    $client_data = [
        'code' => $_POST['client_code'],
        'surname' => $_POST['client_surname']
    ];

    $errors = validateData('client', $client_data, $pdo);

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO clients (code, surname) VALUES (?, ?)");
        $stmt->execute([$client_data['code'], $client_data['surname']]);
    }
}

if (isset($_POST['edit_book'])) {
    $id = $_POST['book_id'];
    $book_data = [
        'code' => $_POST['book_code'],
        'title' => $_POST['book_title'],
        'author' => $_POST['book_author']
    ];

    $errors = validateData('book', $book_data, $pdo);

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE books SET code = ?, title = ?, author = ? WHERE id = ?");
        $stmt->execute([$book_data['code'], $book_data['title'], $book_data['author'], $id]);
    }
}

if (isset($_POST['edit_client'])) {
    $id = $_POST['client_id'];
    $client_data = [
        'code' => $_POST['client_code'],
        'surname' => $_POST['client_surname']
    ];

    $errors = validateData('client', $client_data, $pdo);

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE clients SET code = ?, surname = ? WHERE id = ?");
        $stmt->execute([$client_data['code'], $client_data['surname'], $id]);
    }
}

if (isset($_POST['edit_loan'])) {
    $id = $_POST['loan_id'];
    $loan_term = $_POST['loan_term'];

    $errors = validateData('loan', ['loan_term' => $loan_term], $pdo);

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE loans SET loan_term = ? WHERE id = ?");
        $stmt->execute([$loan_term, $id]);
    }
}

if (isset($_GET['delete_book'])) {
    $id = $_GET['delete_book'];
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);
}

if (isset($_GET['delete_client'])) {
    $id = $_GET['delete_client'];
    $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->execute([$id]);
}

if (isset($_GET['delete_loan'])) {
    $id = $_GET['delete_loan'];
    $stmt = $pdo->prepare("DELETE FROM loans WHERE id = ?");
    $stmt->execute([$id]);
}

$books = $pdo->query("SELECT * FROM books")->fetchAll(PDO::FETCH_ASSOC);

$clients = $pdo->query("SELECT * FROM clients")->fetchAll(PDO::FETCH_ASSOC);

$loans = $pdo->query("SELECT loans.id, loans.issue_date, loans.loan_term, clients.code AS client_code, books.code AS book_code FROM loans
                      JOIN clients ON loans.client_id = clients.id
                      JOIN books ON loans.book_id = books.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD для Библиотеки</title>
    <style>
        .edit-form { display: none; }
    </style>
</head>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        font-size: 16px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    th {
        background-color: black;
        color: white;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
        transform: scale(1.02);
    }

    td:hover {
        background-color: #f1f1f1;
    }

    th, td {
        border: 1px solid #ddd;
    }

    table {
        border-radius: 8px;
        overflow: hidden;
    }

    tr:last-child td {
        border-bottom: 2px solid #ddd;
    }

    table {
        overflow-x: auto;
    }
</style>

<body>

<h2>Добавить книгу</h2>
<form method="POST">
    <input type="text" name="book_code" value="<?= isset($book_data['code']) ? htmlspecialchars($book_data['code']) : '' ?>" placeholder="Код книги" required>
    <input type="text" name="book_title" value="<?= isset($book_data['title']) ? htmlspecialchars($book_data['title']) : '' ?>" placeholder="Название книги" required>
    <input type="text" name="book_author" value="<?= isset($book_data['author']) ? htmlspecialchars($book_data['author']) : '' ?>" placeholder="Автор книги" required>
    <button type="submit" name="add_book">Добавить книгу</button>
</form>


    <h2>Добавить клиента</h2>
    <form method="POST">
        <input type="text" name="client_code" value="<?= isset($client_data['code']) ? htmlspecialchars($client_data['code']) : '' ?>" placeholder="Код клиента" required>
        <input type="text" name="client_surname" value="<?= isset($client_data['surname']) ? htmlspecialchars($client_data['surname']) : '' ?>" placeholder="Фамилия клиента" required>
        <button type="submit" name="add_client">Добавить</button>
    </form>

<?php
$books = $pdo->query("SELECT * FROM books")->fetchAll(PDO::FETCH_ASSOC);

$clients = $pdo->query("SELECT * FROM clients")->fetchAll(PDO::FETCH_ASSOC);

$loans = $pdo->query("SELECT loans.id, loans.issue_date, loans.loan_term, clients.code AS client_code, books.code AS book_code FROM loans
                      JOIN clients ON loans.client_id = clients.id
                      JOIN books ON loans.book_id = books.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Добавить запись о займе</h2>
<form method="POST">
    <select name="loan_book_id" required>
        <?php foreach ($books as $book): ?>
            <option value="<?= $book['id'] ?>" <?= isset($loan_data['book_id']) && $loan_data['book_id'] == $book['id'] ? 'selected' : '' ?>><?= htmlspecialchars($book['code']) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="loan_client_id" required>
        <?php foreach ($clients as $client): ?>
            <option value="<?= $client['id'] ?>" <?= isset($loan_data['client_id']) && $loan_data['client_id'] == $client['id'] ? 'selected' : '' ?>><?= htmlspecialchars($client['code']) ?></option>
        <?php endforeach; ?>
    </select>
    <input type="date" name="loan_issue_date" value="<?= isset($loan_data['issue_date']) ? htmlspecialchars($loan_data['issue_date']) : '' ?>" required>
    <input type="text" name="loan_term" value="<?= isset($loan_data['loan_term']) ? htmlspecialchars($loan_data['loan_term']) : '' ?>" placeholder="Срок займа (дни)" required>
    <button type="submit" name="add_loan">Добавить запись о займе</button>
</form>



    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h2>Список книг</h2>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Код книги</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Редактировать</th>
            <th>Удалить</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['id']) ?></td>
                <td><?= htmlspecialchars($book['code']) ?></td>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td>
                    <button onclick="toggleEditForm('book', <?= $book['id'] ?>)">Редактировать</button>>
                    <form id="edit-form-book-<?= $book['id'] ?>" method="POST" class="edit-form" style="display: none;">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <input type="text" name="book_code" value="<?= htmlspecialchars($book['code']) ?>" required>
                        <input type="text" name="book_title" value="<?= htmlspecialchars($book['title']) ?>" required>
                        <input type="text" name="book_author" value="<?= htmlspecialchars($book['author']) ?>" required>
                        <button type="submit" name="edit_book">Сохранить</button>
                    </form>
                </td>
                <td>
                    <a href="?delete_book=<?= $book['id'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    <h2>Список клиентов</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Код клиента</th>
                <th>Фамилия</th>
                <th>Редактировать</th>
                <th>Удалить</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= htmlspecialchars($client['id']) ?></td>
                    <td><?= htmlspecialchars($client['code']) ?></td>
                    <td><?= htmlspecialchars($client['surname']) ?></td>
                    <td>
                        <button onclick="toggleEditForm('client', <?= $client['id'] ?>)">Редактировать</button>
                        <form id="edit-form-client-<?= $client['id'] ?>" method="POST" class="edit-form">
                            <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
                            <input type="text" name="client_code" value="<?= htmlspecialchars($client['code']) ?>" required>
                            <input type="text" name="client_surname" value="<?= htmlspecialchars($client['surname']) ?>" required>
                            <button type="submit" name="edit_client">Сохранить</button>
                        </form>
                    </td>
                    <td><a href="?delete_client=<?= $client['id'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Список займов</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Код клиента</th>
                <th>Код книги</th>
                <th>Дата выдачи</th>
                <th>Срок займа (дни)</th>
                <th>Редактировать</th>
                <th>Удалить</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?= htmlspecialchars($loan['id']) ?></td>
                    <td><?= htmlspecialchars($loan['client_code']) ?></td>
                    <td><?= htmlspecialchars($loan['book_code']) ?></td>
                    <td><?= htmlspecialchars($loan['issue_date']) ?></td>
                    <td><?= htmlspecialchars($loan['loan_term']) ?></td>
                    <td>
                        <button onclick="toggleEditForm('loan', <?= $loan['id'] ?>)">Редактировать</button>
                        <form id="edit-form-loan-<?= $loan['id'] ?>" method="POST" class="edit-form">
                            <input type="hidden" name="loan_id" value="<?= $loan['id'] ?>">
                            <input type="number" name="loan_term" value="<?= htmlspecialchars($loan['loan_term']) ?>" required>
                            <button type="submit" name="edit_loan">Сохранить</button>
                        </form>
                    </td>
                    <td><a href="?delete_loan=<?= $loan['id'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        function toggleEditForm(type, id) {
            var form = document.getElementById("edit-form-" + type + "-" + id);
            form.style.display = form.style.display === "none" ? "block" : "none";
        }
    </script>
</body>
</html>
