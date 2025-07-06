<?php
try{
    $pdo = new PDO("mysql:host=localhost;charset=utf8;dbname=var3AI;","root","",[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
}
catch(PDOException $e){
    echo "Ошибка" . $e->getMessage();
}
$cities = $pdo->query("SELECT DISTINCT `Пункт` FROM `tab`")->fetchAll();
if(isset($_GET["ok"])){
    $chooseDate = $_GET['chooseDate'];
    if($chooseDate == '1-15 feb'){
        $cond = $pdo->prepare("SELECT * FROM tab WHERE `Пункт` = ? AND `Дата` BETWEEN '2022-02-01' AND '2022-02-15' ORDER BY `Цена` DESC");
        $cond->execute([$_GET["chooseCity"]]);
        $data = $cond->fetchAll();
        $period = $pdo->query("SELECT `Цена`,`Кол-во` FROM `tab` WHERE `Дата` BETWEEN '2022-02-01' AND '2022-02-15'")->fetchAll();
    }
    elseif($chooseDate == '16-28 feb'){
        $cond = $pdo->prepare("SELECT * FROM tab WHERE `Пункт` = ? AND `Дата` BETWEEN '2022-02-16' AND '2022-02-28' ORDER BY `Цена` DESC");
        $cond->execute([$_GET["chooseCity"]]);
        $data = $cond->fetchAll();   
        $period = $pdo->query("SELECT `Цена`,`Кол-во` FROM `tab` WHERE `Дата` BETWEEN '2022-02-16' AND '2022-02-28'")->fetchAll();
    }
    elseif($chooseDate == '1-15 mar'){
        $cond = $pdo->prepare("SELECT * FROM tab WHERE `Пункт` = ? AND `Дата` BETWEEN '2022-03-01' AND '2022-03-15' ORDER BY `Цена` DESC");
        $cond->execute([$_GET["chooseCity"]]);
        $data = $cond->fetchAll();
        $period = $pdo->query("SELECT `Цена`,`Кол-во` FROM `tab` WHERE `Дата` BETWEEN '2022-03-01' AND '2022-03-15'")->fetchAll();
    }

    ?>
    <table border="1px solid black">
    <tr>
        <th>Кассир</th>
        <th>Пункт</th>
        <th>Цена</th>
        <th>Кол-во</th>
        <th>Дата</th>
    </tr>
    <?php
    if(count($data) > 0){
    foreach($data as $item){
        ?><tr>
            <td><?=$item['Кассир']?></td>
            <td><?=$item['Пункт']?></td>
            <td><?=$item['Цена']?></td>
            <td><?=$item['Кол-во']?></td>
            <td><?=$item['Дата']?></td>
        </tr>
    <?php $j=0;}}
    else{?>
    <tr>
        <td colspan="5">Нет таких данных</td>
    </tr>
    <?php $j=0;}}
else{ $data = $pdo->query("SELECT * FROM `tab`")->fetchAll()?>
    <table border="1px solid black">
        <tr>
            <th>Кассир</th>
            <th>Пункт</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th>Дата</th>
        </tr>
        <?php
    foreach($data as $item){
        ?><tr>
            <td><?=$item['Кассир']?></td>
            <td><?=$item['Пункт']?></td>
            <td><?=$item['Цена']?></td>
            <td><?=$item['Кол-во']?></td>
            <td><?=$item['Дата']?></td>
        </tr>
    <?php $j=0;}}
    ?>
<form action="" method="get">
    <select name="chooseCity">
        <?php
        foreach($cities as $city){
            ?><option <?php if(!empty($_GET['chooseCity']) && $_GET['chooseCity'] == $city['Пункт']) {echo "selected";}?>><?= $city["Пункт"]?></option><?php
        }
        ?>
    </select>
    <select name="chooseDate">
        <option value = "1-15 feb" <?php if(!empty($_GET['chooseDate']) && $_GET['chooseDate'] == "1-15 feb") {echo "selected";}?>>1 - 15 февраля</option>
        <option value = "16-28 feb" <?php if(!empty($_GET['chooseDate']) && $_GET['chooseDate'] == "16-28 feb") {echo "selected";}?>>16 — 28 февраля</option>
        <option value = "1-15 mar" <?php if(!empty($_GET['chooseDate']) && $_GET['chooseDate'] == "1-15 mar") {echo "selected";}?>>1 — 15 марта</option>
    </select>
    <button name ="ok">ОК</button>
</form>
<?php
if(isset($_GET['ok'])){
    $min = $pdo->query('SELECT MIN(Цена) as minimum FROM `tab` ')->fetchAll();
    echo "Минимальная цена билета : " . $min[0]['minimum'] . "<br>";
    $sum = 0;
    foreach($period as $p){
        $sum+=$p['Цена']*$p['Кол-во'];
    }
    echo "Общая сумма продаж за указанный период : " . $sum;
}
?>