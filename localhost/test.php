<?php
try{
    $pdo = new PDO("mysql:host=localhost;charset=utf8;dbname=AJAX;", "root", '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
}
catch(PDOException $e){
    echo 'Ошибка:' . $e->getMessage();
}

$stmt = $pdo->prepare("SELECT `Плотность/толщина` FROM `tab` WHERE `Вид укрывного материала` = ?");
        $stmt->execute(['Полиэтиленовая пленка']);
        $ofdD = $stmt->fetchAll();
        foreach($ofdD as $item){
            $ofd[] =  $item["Плотность/толщина"];
        }
        var_dump($ofd);
?>