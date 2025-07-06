<?php
try{
    $pdo = new PDO("mysql:host=localhost;charset=utf8;dbname=AJAX;", "root", '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
}
catch(PDOException $e){
    echo 'Ошибка:' . $e->getMessage();
}
if(isset($_GET['material'])){

    $i = $_GET['material'];
    $stmt = $pdo->prepare("SELECT COUNT(*) as countM FROM `tab` WHERE `Вид укрывного материала` = ?");
    $stmt->execute([$i]);
    $resM = $stmt->fetchAll();  
    if ($resM[0]['countM']!=0 ) {
    $stmt = $pdo->prepare("SELECT `Плотность/толщина` FROM `tab` WHERE `Вид укрывного материала` = ?");
    $stmt->execute([$_GET['material']]);
    $ofdD = $stmt->fetchAll();
        
    foreach($ofdD as $item){
        $ofd[] =  $item["Плотность/толщина"];
    }

    echo json_encode([
        'ofd' => $ofd
    ]);

    }
}
?>