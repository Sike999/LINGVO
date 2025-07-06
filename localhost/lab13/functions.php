<?php
require_once 'db_connect.php';

function getAllAuthors($pdo) {
    $stmt = $pdo->query("
        SELECT DISTINCT author FROM books
    ");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}


function getBooksByAuthors($pdo, $authorList, $sortMethod) {
    $inQuery = implode(',', array_fill(0, count($authorList), '?'));

    $orderBy = $sortMethod === 'author_desc' 
        ? "author ASC"
        : "title DESC";

    $stmt = $pdo->prepare("
        SELECT * FROM books 
        WHERE author IN ($inQuery)
        ORDER BY $orderBy
    ");
    $stmt->execute($authorList);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getClientsEndingWithOv($pdo) {
    $stmt = $pdo->prepare("
        SELECT id, code, surname FROM clients
        WHERE surname LIKE ? 
    ");
    $stmt->execute(['%ов']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getUniqueLoanedBooks($pdo) {
    $stmt = $pdo->query("
        SELECT DISTINCT b.id, b.code
        FROM books b
        JOIN loans l ON b.id = l.book_id
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getClientsWithLoanCount($pdo) {
    $stmt = $pdo->query("
        SELECT clients.id, clients.code, clients.surname, COUNT(loans.id) AS loan_count
        FROM clients
        JOIN loans ON clients.id = loans.client_id
        GROUP BY clients.id
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getBooksNeverLoaned($pdo) {
    $stmt = $pdo->query("
        SELECT id, code, title, author
        FROM books
        WHERE id NOT IN (SELECT DISTINCT book_id FROM loans)
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getClientsWithMoreThan5Loans($pdo) {
    $stmt = $pdo->query("
        SELECT clients.id, clients.code, clients.surname, COUNT(loans.id) AS loan_count
        FROM clients
        JOIN loans ON clients.id = loans.client_id
        GROUP BY clients.id
        HAVING loan_count > 5
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getClientsWithLoanSummary($pdo, $selectedClientCode = null) {
    $query = "
        SELECT clients.id, clients.code, clients.surname, COUNT(loans.id) AS loan_count
        FROM clients
        LEFT JOIN loans ON clients.id = loans.client_id
    ";

    if ($selectedClientCode) {
        $query .= " WHERE clients.code = :code";
    }

    $query .= " GROUP BY clients.id";

    $stmt = $pdo->prepare($query);

    if ($selectedClientCode) {
        $stmt->execute(['code' => $selectedClientCode]);
    } else {
        $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getBooksWithLoanStats($pdo) {
    $stmt = $pdo->query("
        SELECT 
            books.id, 
            books.code, 
            books.title, 
            books.author, 
            COALESCE(COUNT(loans.id), 0) AS loan_count, 
            ROUND(COALESCE(AVG(loans.loan_term), 0), 1) AS avg_loan_term
        FROM books
        LEFT JOIN loans ON books.id = loans.book_id
        GROUP BY books.id
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getClientsWhoRepeatedLoans($pdo) {
    $stmt = $pdo->query("
        SELECT 
            clients.id AS client_id, 
            clients.code AS client_code, 
            clients.surname, 
            books.title, 
            books.code AS book_code, 
            COUNT(loans.id) AS loan_count
        FROM loans
        JOIN clients ON loans.client_id = clients.id
        JOIN books ON loans.book_id = books.id
        GROUP BY clients.id, loans.book_id
        HAVING loan_count > 1
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getBooksLoanedMoreThan10TimesFor30Days($pdo) {
    $stmt = $pdo->query("
        SELECT 
            books.id, 
            books.code AS book_code, 
            books.title, 
            books.author, 
            COUNT(loans.id) AS loan_count
        FROM books
        JOIN loans ON books.id = loans.book_id
        WHERE loans.loan_term >= 30
        GROUP BY books.id
        HAVING loan_count > 10
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
