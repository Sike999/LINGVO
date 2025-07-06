<?php
require_once 'db_connect.php';

try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(10) NOT NULL UNIQUE,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS clients (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(10) NOT NULL UNIQUE,
            surname VARCHAR(255) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS loans (
            id INT AUTO_INCREMENT PRIMARY KEY,
            book_id INT NOT NULL,
            client_id INT NOT NULL,
            issue_date DATE NOT NULL,
            loan_term INT NOT NULL,
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
            FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
        );

        INSERT INTO books (code, title, author) VALUES
        ('BK001', 'Азбука', 'Артур Сатрудинов'),
        ('BK002', 'Бебубка', 'Артур Пиражков'),
        ('BK003', 'Цебука', 'Михаил Кругов'),
        ('BK004', 'Дудека', 'София Иванова');

        INSERT INTO clients (code, surname) VALUES
        ('C001', 'Иванов'),
        ('C002', 'Петров'),
        ('C003', 'Сидоров'),
        ('C004', 'Смирнов');

        INSERT INTO loans (book_id, client_id, issue_date, loan_term) VALUES
        (1, 1, '2023-11-01', 30),
        (2, 2, '2023-11-05', 20),
        (3, 3, '2023-11-10', 40),
        (1, 1, '2023-11-15', 30),
        (3, 2, '2023-11-20', 10);
    ");
    echo "Таблицы успешно созданы и заполнены.";
} catch (PDOException $e) {
    die("Ошибка создания таблиц: " . $e->getMessage());
}
