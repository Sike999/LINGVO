<?php
$data = json_decode(file_get_contents('php://input'), true);
try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8;dbname=AJAX;', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Ошибка: ' . $e->getMessage()]);
    exit;
}

if (isset($data['id'])) {
    $id = $data['id'];
    $stmt = $pdo->prepare('SELECT `Quan`,`Price` FROM `tabExtra` WHERE `id` = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if ($product) {
        echo json_encode(['maxQuantity' => $product['Quan'],'price' => $product['Price']]);
    }
    else {
        echo json_encode(['error' => 'Неверное id']);
    }
}
?>