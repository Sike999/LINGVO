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
    $stmt = $pdo->prepare('SELECT `Model`,`Quan`,`Price` FROM `tabExtra` WHERE `id` = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    $idMinus = 'countMinus-' . $id;
    $idPlus = 'countPlus-' . $id;
    $price = $product['Price'];

    if (!empty($product)) {
        echo json_encode(['price' => $price, 'model' => $product['Model'] . "<br>", 'basicQuantity' => '<button id="' . $idMinus . '" class="countMinus" >-</button><span id="quan">' . ' ' . 1 . ' ' . '</span><button id="' . $idPlus . '" class="countPlus">+</button>',
    'idMinus' => $idMinus, 'idPlus' => $idPlus]);
    } else {
        echo json_encode(['error' => 'Неверное id']);
    }
}
 else {
    echo json_encode(['error' => 'Не найдено']);
}
?>