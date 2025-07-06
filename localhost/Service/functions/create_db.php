<?php
     try {
        $pdo = new PDO('mysql:host=localhost;charset=utf8;', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $sql = "CREATE DATABASE IF NOT EXISTS clinic";
        $pdo->exec($sql);

        $pdo->exec("USE clinic");

        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fio VARCHAR(255),
            email VARCHAR(255) UNIQUE NOT NULL,
            user_password VARCHAR(255) NOT NULL,
            admin BOOLEAN DEFAULT FALSE
        )";
        $pdo->exec($sql);


        $sql = "CREATE TABLE IF NOT EXISTS doctors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fio VARCHAR(255) NOT NULL,
            specialization VARCHAR(255) NOT NULL
        )";
        $pdo->exec($sql);

        $sql = "CREATE TABLE IF NOT EXISTS available_slots (
            id INT AUTO_INCREMENT PRIMARY KEY,
            doctor_id INT NOT NULL,
            available_date DATE NOT NULL,
            available_slots INT NOT NULL DEFAULT 5 CHECK (available_slots >= 0 AND available_slots <= 5),
            FOREIGN KEY (doctor_id) REFERENCES doctors(id)
        )";
        $pdo->exec($sql);

         $sql = "CREATE TABLE IF NOT EXISTS appointments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            doctor_id INT NOT NULL,
            appointment_date DATE NOT NULL,
            cancelled_at DATETIME DEFAULT NULL, 
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (doctor_id) REFERENCES doctors(id)
        )";
        $pdo->exec($sql);

    } catch (PDOException $e) {
        die('Ошибка: ' . $e->getMessage());
    }

    $pdo = null;
?>