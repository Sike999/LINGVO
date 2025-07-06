<?php
    $testArray[$null] = 1;
    var_dump($testArray);
    $conn = mysqli_connect("localhost", "root", "");
    if (!$conn) {
        die("Ошибка подключения: " . mysqli_connect_error());
    }

    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS Грузоперевозки");
    mysqli_select_db($conn, "Грузоперевозки");
    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS перевозки (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cargoType VARCHAR(255) NOT NULL,
        wagonType VARCHAR(255) NOT NULL,
        basePrice FLOAT NOT NULL,
        shipDate DATE NOT NULL,
        backDate DATE NOT NULL,
        winterCoef FLOAT NOT NULL,
        summerCoef FLOAT NOT NULL
    )");
    $flag = TRUE;
    if ($flag == FALSE){
    mysqli_query($conn,"INSERT INTO перевозки (cargoType, wagonType, basePrice, shipDate, backDate, winterCoef, summerCoef) VALUES
    ('Уголь', 'Полувагон', 5000.0, '2024-01-15', '2024-01-20', 0.9, 1.2),
    ('Металл', 'Крытый вагон', 7000.0, '2024-03-10', '2024-03-15', 0.85, 1.3),
    ('Зерно', 'Хоппер', 4500.0, '2024-06-01', '2024-06-10', 0.95, 1.1),
    ('Древесина', 'Платформа', 6000.0, '2024-02-05', '2024-02-10', 0.8, 1.4),
    ('Щебень', 'Платформа', 5500.0, '2024-04-20', '2024-04-25', 0.9, 1.2),
    ('Машины', 'Крытый вагон', 15000.0, '2024-05-15', '2024-05-20', 0.9, 1.5),
    ('Нефть', 'Цистерна', 10000.0, '2024-06-05', '2024-06-10', 0.85, 1.2);");
    $flag = TRUE;}

$time = new DateTime();
$time = $time -> format('Y/m/d');
if(isset($_GET['summerCoef']) && isset($_GET['winterCoef']) && isset($_GET['backDate']) && isset($_GET['shipDate']) && isset($_GET['basePrice']) && isset($_GET['wagonType']) 
&& isset($_GET['cargoType']) && isset($_GET['add']))  {
if (!preg_match('/^(19|20)\d\d-02-(30|31)$/',$_GET['shipDate']) && !preg_match('/^(19|20)\d\d-02-(30|31)$/',$_GET['backDate']) 
&& strlen($_GET['cargoType']) <= 255 && strlen($_GET['wagonType']) <= 255 && $_GET['basePrice'] >= 0 && preg_match('/^\d+(\.?\d{0,2})?$/', $_GET['basePrice'])
&& $_GET['winterCoef'] < 1000000000000 && $_GET['summerCoef'] < 1000000000000
&& $_GET['basePrice'] < 1000000000000 && preg_match('/^(19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/',$_GET['shipDate']) 
&& preg_match('/^(19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/',$_GET['backDate']) 
&& preg_match('/^[A-Za-zА-Яа-яЁё]+((\s|-)[A-Za-zА-Яа-яЁё]+)*$/u', $_GET['cargoType']) 
&& preg_match('/^[A-Za-zА-Яа-яЁё]+((\s|-)[A-Za-zА-Яа-яЁё]+)*$/u', $_GET['wagonType']) && is_string($_GET['cargoType']) 
&& is_string($_GET['wagonType']) && is_numeric($_GET['summerCoef']) 
&& preg_match('/^\d+(\.?\d{0,2})?$/', $_GET['summerCoef']) && $_GET['summerCoef'] >= 0 
&& is_numeric($_GET['winterCoef']) && preg_match('/^\d+(\.?\d{0,2})?$/', $_GET['winterCoef']) && $_GET['winterCoef'] >= 0 
&& is_numeric($_GET['basePrice']) && ($_GET['backDate'] >= $_GET['shipDate']) && ((new DateTime($_GET['backDate']))->format('Y/m/d') <= $time) 
&& ((new DateTime($_GET['shipDate']))->format('Y/m/d') <= $time)
)  {
    
    $cargoType = mysqli_real_escape_string($conn, $_GET['cargoType']);
    $wagonType = mysqli_real_escape_string($conn, $_GET['wagonType']);
    $basePrice = floatval($_GET['basePrice']);
    $shipDate = mysqli_real_escape_string($conn, $_GET['shipDate']);
    $backDate = mysqli_real_escape_string($conn, $_GET['backDate']);
    $winterCoef = floatval($_GET['winterCoef']);
    $summerCoef = floatval($_GET['summerCoef']);

       
    $sql = "INSERT INTO перевозки (cargoType, wagonType, basePrice, shipDate, backDate, winterCoef, summerCoef) 
    VALUES ('$cargoType', '$wagonType', $basePrice, '$shipDate', '$backDate', $winterCoef, $summerCoef)";
    mysqli_query($conn, $sql);
    header("Location:http://localhost/lab12/lab12.php");
}
}
if (isset($_GET['half']) && isset($_GET['year'])) {
    if(intval($_GET['year']) > 1900 && intval($_GET['year']) <= 2024 && is_numeric($_GET['year']) && is_int($_GET['year'] * 1)){
    $year = intval($_GET['year']);
    $sql = "SELECT DISTINCT wagonType FROM перевозки 
            WHERE YEAR(shipDate) = $year AND MONTH(shipDate) <= 6";
    $result = mysqli_query($conn, $sql);
    echo "Типы вагонов за первое полугодие $year:";?><br><?php
    if(mysqli_num_rows($result) > 0){
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['wagonType'];?> 
        <br>
    <?php 
    }}
    else
    {
        echo "Таких вагонов нет.";
    }
}
else {
    echo "Введен неверный год.";
}
}
if (isset($_GET['count'])) {
    $i = $_GET['avgPrice'];
    $stmt = mysqli_query($conn,"SELECT COUNT(*) as count FROM перевозки WHERE cargoType='$i'");
    $res = mysqli_fetch_assoc($stmt);
    if($res['count']!=0){
    $type = $_GET['avgPrice'];
    $sql = "SELECT AVG(basePrice) AS средняя_стоимость FROM перевозки 
            WHERE cargoType = '$type'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "Средняя стоимость перевозки для $type: " . $row['средняя_стоимость'];?>
    <br>
    <?php
    }
}
$flag = TRUE;
if (isset($_GET['show'])) {
    $sql = "SELECT SUM(basePrice) AS общая_стоимость FROM перевозки";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    ?> <div><?php echo"Общая стоимость перевозок: " . $row['общая_стоимость'];?></div>
    <br>
    <?php
}

if(isset($_GET['delete'])){
    $i = $_GET['delete'];
    $stmt = mysqli_query($conn,"SELECT COUNT(*) as count FROM перевозки WHERE id='$i'");
    $res = mysqli_fetch_assoc($stmt);
if (is_numeric($i) && is_int($i * 1) && $res['count']!=0) {
    $id = $i;
    mysqli_query($conn,"DELETE FROM перевозки WHERE id = $id");
    header("Location:http://localhost/lab12/lab12.php");
}
else {?><script>alert("Неверное значение id!")</script><?php $kostil=0;}
}
if (isset($_GET['edit']) && isset($_GET['id'])){
    $i = $_GET['id'];
    $stmt = mysqli_query($conn,"SELECT COUNT(*) as count FROM перевозки WHERE id='$i'");
    $res = mysqli_fetch_assoc($stmt);
    if(!is_numeric($i) || !is_int($i * 1) || !$res['count']!=0) {
            ?><script>alert("Неверное значение id!")</script><?php $kostil=0;
    }
    else{
if(isset($_GET['EcargoType']) && isset($_GET['EwagonType']) && isset($_GET['EbasePrice']) && 
    isset($_GET['EshipDate']) && isset($_GET['EbackDate']) && isset($_GET['EwinterCoef']) && 
    isset($_GET['EsummerCoef'])){
if ((strlen($_GET['EcargoType']) <= 255 && strlen($_GET['EwagonType']) <= 255) 
    && (!empty($_GET['EcargoType']) && !empty($_GET['EwagonType']) && !empty($_GET['EbasePrice']) && 
    !empty($_GET['EshipDate']) && !empty($_GET['EbackDate']) && !empty($_GET['EwinterCoef']) && 
    !empty($_GET['EsummerCoef'])) && (preg_match('/^[A-Za-zА-Яа-яЁё]+((\s|-)[A-Za-zА-Яа-яЁё]+)*$/u', $_GET['EcargoType']) 
    && preg_match('/^[A-Za-zА-Яа-яЁё]+((\s|-)[A-Za-zА-Яа-яЁё]+)*$/u', $_GET['EwagonType']) && is_string($_GET['EcargoType']) && is_string($_GET['EwagonType']) 
    && is_numeric($_GET['EsummerCoef']) && $_GET['EsummerCoef'] >= 0 && preg_match('/^\d+(\.?\d{0,2})?$/', $_GET['EsummerCoef']) && preg_match('/^\d+(\.?\d{0,2})?$/', $_GET['EwinterCoef']) 
    && is_numeric($_GET['EwinterCoef']) && $_GET['EwinterCoef'] >= 0  && $_GET['EwinterCoef'] < 1000000000000 
    && $_GET['EsummerCoef'] < 1000000000000 && preg_match('/^(19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/',$_GET['EbackDate']) 
    && preg_match('/^(19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/',$_GET['EshipDate'])  
    && is_numeric($_GET['EbasePrice']) && $_GET['EbasePrice'] >= 0 && preg_match('/^\d+(\.?\d{0,2})?$/', $_GET['EbasePrice']) && $_GET['EbasePrice'] < 1000000000000
    && ($_GET['EbackDate'] >= $_GET['EshipDate']) 
    && ((new DateTime($_GET['EbackDate']))->format('Y/m/d') <= $time) && ((new DateTime($_GET['EshipDate']))->format('Y/m/d') <= $time))) {
  
    $id = $i;
    $cargo = mysqli_real_escape_string($conn, $_GET['EcargoType']);
    $wagon = mysqli_real_escape_string($conn, $_GET['EwagonType']);
    $price = floatval($_GET['EbasePrice']);
    $ship = mysqli_real_escape_string($conn, $_GET['EshipDate']);
    $back = mysqli_real_escape_string($conn, $_GET['EbackDate']);
    $winter = floatval($_GET['EwinterCoef']);
    $summer = floatval($_GET['EsummerCoef']);
    mysqli_query($conn, "UPDATE перевозки 
        SET cargoType = '$cargo', wagonType = '$wagon', basePrice = $price, 
        shipDate = '$ship', backDate = '$back', winterCoef = $winter, summerCoef = $summer 
        WHERE id = $id");
    header("Location:http://localhost/lab12/lab12.php");
    
}
    else {
        echo "Введенные значения неверны.";
    }
  }    
}
}
$data = mysqli_query($conn,"SELECT * FROM перевозки");
$table = mysqli_fetch_assoc($data);
while($table != NULL)
{
    $arr[] = $table;
    $table = mysqli_fetch_assoc($data); 
}
    $i=0;
    ?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Вариант 1</title>
    <style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        font-size: 16px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    th {
        background-color: black;
        color: white;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    th, td {
        border: 1px solid #ddd;
    }

    table {
        border-radius: 8px;
        overflow: hidden;
    }

    tr:last-child td {
        border-bottom: 2px solid #ddd;
    }

    table {
        overflow-x: auto;
    }
    a{

    }
    </style>
</head>
<body>
<form method="get" action="">
        <h3>Добавить запись</h3>
        Вид груза: <input type="text" name="cargoType" value="<?=!empty($_GET["cargoType"]) ? $_GET["cargoType"] : ""?>">
        Тип вагона: <input type="text" name="wagonType" value="<?=!empty($_GET["wagonType"]) ? $_GET["wagonType"] : ""?>">
        Базовая стоимость: <input type="text" name="basePrice" value="<?=!empty($_GET["basePrice"]) ? $_GET["basePrice"] : ""?>"><br>
        Дата отгрузки: <input type="date" name="shipDate" value="<?=!empty($_GET["shipDate"]) ? $_GET["shipDate"] : ""?>">
        Дата возврата: <input type="date" name="backDate" value="<?=!empty($_GET["backDate"]) ? $_GET["backDate"] : ""?>"><br>
        Коэффициент зимней скидки: <input type="text" name="winterCoef" value="<?=!empty($_GET["winterCoef"]) ? $_GET["winterCoef"] : ""?>">
        Коэффициент летнего повышения: <input type="text" name="summerCoef" value="<?=!empty($_GET["summerCoef"]) ? $_GET["summerCoef"] : ""?>"><br>
        <button type="submit" name="add">Добавить</button>
    
        <h3>Типы вагонов за первое полугодие</h3>
        Укажите год: <input type="text" name="year" value="<?=!empty($_GET["year"]) ? $_GET["year"] : ""?>"><br>
        <button type="submit" name="half">Показать</button>
        
        <div>
        <h3>Общая стоимость перевозок</h3>
        <button type="submit" name="show">Показать</button>
        </div>

        <h3>Средняя стоимость перевозки</h3>
        Вид груза: 
        <select name="avgPrice"> 
            <?php 
            
            $data = mysqli_query($conn, "SELECT DISTINCT cargoType FROM перевозки"); 
            $row = mysqli_fetch_all($data); 
            foreach ($row as $value) { 
            ?> 
                <option value ="<?=$value[0]?>" <?= !empty($_GET['avgPrice']) && $_GET['avgPrice'] === $value[0] ? 'selected' : '' ?> ><?= $value[0] ?></option> 
            <?php 
            } 
            ?> 
        </select>
        <br>
        <button type="submit" name="count">Рассчитать</button>
    </form>
    <style>
        .edit-form { display: none; }
    </style>
    <table border="1">
    <tr>
    <?php
    foreach(array_keys($arr[0]) as $key)
    {
        ?>
        <th><?=$key?></th>
        <?php
    }
    ?>
        <th>Редактировать</th>
        <th>Удалить</th>
    <tr>
    <?php
    foreach($arr as $a)
    {
    ?>
    <tr>
        <?php
        foreach($a as $value)
        {
            ?>
            <td><?=$value?></td>
            <?php
        }
        ?>
        <td>
            <button onclick="toggleEditForm(<?= $a['id'] ?>)">Редактировать</button>
            <form id="edit-form-<?= $a['id'] ?>" method="GET" class="edit-form" style="display: none;">
                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                        <input type="text" name="EcargoType" value="<?=$a['cargoType']?>" required>
                        <input type="text" name="EwagonType" value="<?=$a['wagonType']?>" required>
                        <input type="text" name="EbasePrice" value="<?=$a['basePrice']?>" required>
                        <input type="date" name="EshipDate" value="<?=$a['shipDate']?>" required>
                        <input type="date" name="EbackDate" value="<?=$a['backDate']?>" required>
                        <input type="text" name="EwinterCoef" value="<?= $a['winterCoef']?>" required>
                        <input type="text" name="EsummerCoef" value="<?= $a['summerCoef']?>" required>
                        <button type="submit" name="edit">Сохранить</button>
            </form>
        </td>
        <td><a href="?delete=<?= $a['id'] ?>" onclick="return confirm('Вы уверены?')">Удалить</a></td>
    </tr>
    <?php  
    }
    ?>
    </table>
    <script>
    function toggleEditForm(id) {
            var form = document.getElementById("edit-form-" + id);
            form.style.display = form.style.display === "none" ? "block" : "none";
        }
    </script>   
</body>



