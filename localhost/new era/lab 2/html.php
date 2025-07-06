<?php 
require 'item.php';
if (isset($_GET['item'])) {
    $pdo = new PDO("mysql:host=localhost;charset=utf8;dbname=" . 'CSV_DB 12' . ";", "root", '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $stmt = $pdo->prepare("SELECT * FROM `______3` WHERE `COL 1` = ?");
    $stmt->execute([$_GET['item']]);
    $result = $stmt->fetch();
    $title = $result ? $result['COL 1'] : 'Пустая страница';
} else {
    $title = 'Пустая страница';
}
$class = new HTMLPage($title);
$html = $class->write();
echo $html;
?>