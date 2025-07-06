<?php
try{
$pdo = new PDO("mysql:host=localhost;charset=utf8;", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
}
catch(PDOException $e){
    die( "Ошибка" . $e->getMessage());
}
$pdo->query('USE parasha');
if(FALSE){
$pdo->query('INSERT INTO tab (Kassir, Punkt, Price, Quantity, Date) VALUES
("Иванова","Москва","20000","1","2024-10-1"),
("Пупа","Нижневартовск","12000.4","3","2024-10-2"),
("Лупа","Москва","10000","1","2024-10-3"),
("Лупа","Новосибирск","900.3","1","2024-10-4"),
("Иванова","Тюмень","9000","2","2024-10-5"),
("Иванова","Тюмень","20000","4","2024-10-6"),
("Жучка","Новосибирск","74000","2","2024-10-7");
');
}
$data = $pdo->query("SELECT * FROM tab")->fetchAll();
$cities = $pdo->query("SELECT DISTINCT Punkt FROM tab")->fetchAll();
$avgPrice = 0;
$count = 0;
$maxSum = 0;
if (isset($_GET['ok'])){
$between = $_GET["between"];
if($between == 1){
    $cond = $pdo->prepare("SELECT * FROM tab WHERE Punkt = ? AND Date BETWEEN '2024-01-15' AND '2024-01-31'");
    $cond->execute([$_GET["cit"]]);
    $res = $cond->fetchAll();
    $period = $pdo->query("SELECT `Price`,`Quantity` FROM tab WHERE Date BETWEEN '2024-01-15' AND '2024-01-31'")->fetchAll();
}    
elseif($between == 2){
    $cond = $pdo->prepare("SELECT * FROM tab WHERE Punkt = ? AND Date BETWEEN '2024-02-01' AND '2024-03-01'");
    $cond->execute([$_GET["cit"]]);
    $res = $cond->fetchAll();
    $period = $pdo->query("SELECT `Price`,`Quantity` FROM tab WHERE Date BETWEEN '2024-02-01' AND '2024-03-01'")->fetchAll();
}
elseif($between == 3){
    $cond = $pdo->prepare("SELECT * FROM tab WHERE Punkt = ? AND Date BETWEEN '2024-03-02' AND '2024-03-15'");
    $cond->execute([$_GET["cit"]]);
    $res = $cond->fetchAll();
    $period = $pdo->query("SELECT `Price`,`Quantity` FROM tab WHERE Date BETWEEN '2024-03-02' AND '2024-03-15'")->fetchAll();
}
foreach ($period as $case) {
    $count+=1;
    $avgPrice+=$case['Price'];
    $maxSum+=$case['Price']*$case['Quantity'];
}
}
?>


<head>
    
</head>
<body>
    <form method="get" action="">
        <select name="cit">
            <?php
            foreach($cities as $city){
                ?><option <?php if(!empty($_GET['cit']) && $_GET['cit'] == $city['Punkt']) {echo "selected";}?>><?=$city['Punkt']?></option><?php
            }
            ?>
        </select>
        <br>
        <select name="between">
            <option value ="1" <?php if(!empty($_GET['between']) && $_GET['between'] == 1) {echo "selected";}?>>15 - 31 января</option>
            <option value ="2" <?php if(!empty($_GET['between']) && $_GET['between'] == 2) {echo "selected";}?>>1 февраля - 1 марта</option>
            <option value ="3" <?php if(!empty($_GET['between']) && $_GET['between'] == 3) {echo "selected";}?>>2 - 15 марта</option>
        </select>
        <br>
        <button name="ok">Выгрузить данные</button>
        <br>
        <table border="solid; 1px">
            <tr>
                <th>Кассир</th>
                <th>Пункт Назначения</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Дата</th>
            </tr>
            <?php
            if(!isset($_GET['ok'])){
            foreach($data as $d){
                ?><tr>
                    <td><?=$d['Kassir']?></td>
                    <td><?=$d['Punkt']?></td>
                    <td><?=$d['Price']?></td>
                    <td><?=$d['Quantity']?></td>
                    <td><?=$d['Date']?></td>
                </tr><?php
            }}
            elseif(isset($_GET['cit']) && !empty($res)){
                foreach($res as $r){
                    ?><tr>
                        <td><?=$r['Kassir']?></td>
                        <td><?=$r['Punkt']?></td>
                        <td><?=$r['Price']?></td>
                        <td><?=$r['Quantity']?></td>
                        <td><?=$r['Date']?></td>
                    </tr><?php
                }
            }
            else
            {
                ?><tr>
                        <td>Пусто</td>
                        <td>Пусто</td>
                        <td>Пусто</td>
                        <td>Пусто</td>
                        <td>Пусто</td>
                    </tr><?php
            }
            ?>
        </table>
        <?php
        if(isset($_GET['ok'])){
        if($avgPrice != 0 && $count != 0){
            echo "Средняя цена за указанный период : " . round($avgPrice / $count);
        }
        else{
            echo "Средняя цена за указанный период : 0";
        }
        ?>
        <br>
        <?php
        if($maxSum != 0){
            echo "Максимальная сумма за указанный период : " . round($maxSum);
        }
        else{
            echo "Максимальная сумма за указанный период : 0";
        }}
        ?>
    </form>
</body>