<?php
require_once 'db_connect.php';
require_once 'functions.php';

$pdo->exec("USE library");

$authors = getAllAuthors($pdo);
$allClients = $pdo->query("SELECT id, code, surname FROM clients")->fetchAll(PDO::FETCH_ASSOC);
$sortMethod = $_GET['sort_method'] ?? 'author_desc';
$booksByAuthors = getBooksByAuthors($pdo, $authors, $sortMethod);
$clientsEndingWithOv = getClientsEndingWithOv($pdo);
$uniqueLoanedBooks = getUniqueLoanedBooks($pdo);
$clientsWithLoanCount = getClientsWithLoanCount($pdo);
$booksNeverLoaned = getBooksNeverLoaned($pdo);
$clientsWithMoreThan5Loans = getClientsWithMoreThan5Loans($pdo);
$clientsWithLoanSummary = getClientsWithLoanSummary($pdo);
$booksWithLoanStats = getBooksWithLoanStats($pdo);
$clientsWhoRepeatedLoans = getClientsWhoRepeatedLoans($pdo);
$booksLoanedMoreThan10TimesFor30Days = getBooksLoanedMoreThan10TimesFor30Days($pdo);
$selectedClientCode = $_GET['client_code'] ?? null;
$clientsWithLoanSummary = getClientsWithLoanSummary($pdo, $selectedClientCode);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Queries</title>
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

</head>
<body>
<marquee>Примите лабу не глядя</marquee>
    <h2>1. Список книг заданных авторов, упорядоченный по убыванию по авторам или по возрастанию по названиям</h2>
    <form method="get">
        <label>
            <input type="radio" name="sort_method" value="author_desc" <?= $sortMethod === 'author_desc' ? 'checked' : '' ?>>
            Автор (убыв.)
        </label>
        <label>
            <input type="radio" name="sort_method" value="title_asc" <?= $sortMethod === 'title_asc' ? 'checked' : '' ?>>
            Название (возр.)
        </label>
        <button type="submit">Сортировать</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Автор</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($booksByAuthors as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['id']) ?></td>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <h2>2. Список клиентов, фамилии которых заканчиваются на «ов»</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Код клиента</th>
            <th>Фамилия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientsEndingWithOv as $client): ?>
            <tr>
                <td><?= htmlspecialchars($client['id']) ?></td>
                <td><?= htmlspecialchars($client['code']) ?></td>
                <td><?= htmlspecialchars($client['surname']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<h2>3. Список кодов книг, которые выдавались (без повторов)</h2>
<table>
    <thead>
        <tr>
            <th>ID книги</th>
            <th>Код книги</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($uniqueLoanedBooks as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['id']) ?></td>
                <td><?= htmlspecialchars($book['code']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<h2>4. Список клиентов, которым выдавались книги с указанием количества выдач</h2>
<table>
    <thead>
        <tr>
            <th>ID клиента</th>
            <th>Код клиента</th>
            <th>Фамилия</th>
            <th>Количество выдач</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientsWithLoanCount as $client): ?>
            <tr>
                <td><?= htmlspecialchars($client['id']) ?></td>
                <td><?= htmlspecialchars($client['code']) ?></td>
                <td><?= htmlspecialchars($client['surname']) ?></td>
                <td><?= htmlspecialchars($client['loan_count']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>5. Список книг, которые не выдавались</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Код книги</th>
            <th>Название</th>
            <th>Автор</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($booksNeverLoaned as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['id']) ?></td>
                <td><?= htmlspecialchars($book['code']) ?></td>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>6. Список клиентов, бравших книги более 5 раз</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Код клиента</th>
            <th>Фамилия</th>
            <th>Количество выдач</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientsWithMoreThan5Loans as $client): ?>
            <tr>
                <td><?= htmlspecialchars($client['id']) ?></td>
                <td><?= htmlspecialchars($client['code']) ?></td>
                <td><?= htmlspecialchars($client['surname']) ?></td>
                <td><?= htmlspecialchars($client['loan_count']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<h2>7. Список клиентов с полем, содержащим количество выдач книг данному клиенту</h2>

<form method="GET">
    <label for="client_code">Выберите клиента:</label>
    <select name="client_code" id="client_code">
        <option value="">Все клиенты</option>
        <?php foreach ($allClients as $client): ?>
            <option value="<?= htmlspecialchars($client['code']) ?>"
                <?= $selectedClientCode === $client['code'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($client['code'] . ' - ' . $client['surname']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Показать</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID клиента</th>
            <th>Код клиента</th>
            <th>Фамилия</th>
            <th>Количество выдач</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientsWithLoanSummary as $client): ?>
            <tr>
                <td><?= htmlspecialchars($client['id']) ?></td>
                <td><?= htmlspecialchars($client['code']) ?></td>
                <td><?= htmlspecialchars($client['surname']) ?></td>
                <td><?= htmlspecialchars($client['loan_count']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>8. Список книг с указанием, сколько раз она выдавалась и среднего срока выдачи</h2>
<table>
    <thead>
        <tr>
            <th>ID книги</th>
            <th>Код книги</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Количество выдач</th>
            <th>Средний срок выдачи (дней)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($booksWithLoanStats as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['id']) ?></td>
                <td><?= htmlspecialchars($book['code']) ?></td>
                <td><?= htmlspecialchars($book['title'] ?? 'Название отсутствует') ?></td>
                <td><?= htmlspecialchars($book['author'] ?? 'Автор неизвестен') ?></td>
                <td><?= htmlspecialchars($book['loan_count'] ?? 0) ?></td>
                <td><?= htmlspecialchars($book['avg_loan_term'] ?? 0) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<h2>9. Список клиентов, бравших одну и ту же книгу более 1 раза</h2>
<table>
    <thead>
        <tr>
            <th>ID клиента</th>
            <th>Код клиента</th>
            <th>Фамилия</th>
            <th>Название книги</th>
            <th>Код книги</th>
            <th>Количество выдач</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientsWhoRepeatedLoans as $loan): ?>
            <tr>
                <td><?= htmlspecialchars($loan['client_id']) ?></td>
                <td><?= htmlspecialchars($loan['client_code']) ?></td>
                <td><?= htmlspecialchars($loan['surname']) ?></td>
                <td><?= htmlspecialchars($loan['title']) ?></td>
                <td><?= htmlspecialchars($loan['book_code']) ?></td>
                <td><?= htmlspecialchars($loan['loan_count']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<h2>10. Список книг, которые брались более 10 раз на срок не менее 30 дней</h2>
<table>
    <thead>
        <tr>
            <th>ID книги</th>
            <th>Код книги</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Количество выдач</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($booksLoanedMoreThan10TimesFor30Days as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['id']) ?></td>
                <td><?= htmlspecialchars($book['book_code']) ?></td>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['loan_count']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</html>
