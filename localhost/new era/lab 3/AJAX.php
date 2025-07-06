<?php
try{
    $pdo = new PDO("mysql:host=localhost;charset=utf8;dbname=AJAX;", "root", '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
}
catch(PDOException $e){
    echo 'Ошибка:' . $e->getMessage();
}
$material = $pdo->query("SELECT DISTINCT `Вид укрывного материала` FROM `tab`")->fetchAll();
$density = $pdo->query("SELECT `Плотность/толщина` FROM `tab`")->fetchAll();

if(isset($_GET['material']) && isset($_GET['density']) && isset($_GET['quantity'])){

    $i = $_GET['material'];
    $stmt = $pdo->prepare("SELECT COUNT(*) as countM FROM `tab` WHERE `Вид укрывного материала` = ?");
    $stmt->execute([$i]);
    $resM = $stmt->fetchAll();  

    $i = $_GET['density'];
    $stmt = $pdo->prepare("SELECT COUNT(*) as countD FROM `tab` WHERE `Плотность/толщина` = ?");
    $stmt->execute([$i]);
    $resD = $stmt->fetchAll();

    if ($resM[0]['countM']!=0 && $resD[0]['countD']!=0) {
        if (is_numeric($_GET['quantity'])){
        $stmt = $pdo->prepare("SELECT `Стоимость 1 м` FROM `tab` WHERE `Вид укрывного материала` = ? AND `Плотность/толщина` = ?");
        $stmt->execute([$_GET['material'], $_GET['density']]);
        $wtf = $_GET['material'] . $_GET['density'];
        $res = $stmt->fetch();}

        else{
            if(empty($_GET['quantity'])){
                $resp = 'Введите данные.';}
            else{
            $resp = 'Введены неверные данные!';}
        }

        if(empty($res)){
            if(!empty($_GET['quantity']) && is_numeric($_GET['quantity']))
            $resp = $wtf;
        }
        else{
        $totalPrice = $res["Стоимость 1 м"] * trim($_GET['quantity']);
        $totalPrice = "Стоимость материала: " . $totalPrice;
        }
    }
    if(isset($totalPrice)){
    echo json_encode([
        'material' => $material,
        'density' => $density,
        'price' => $totalPrice,
        'responce' => null
    ]);}
    else{
        echo json_encode([
            'material' => $material,
            'density' => $density,
            'price' => null,
            'responce' => $resp
        ]);
    }
}

?>
