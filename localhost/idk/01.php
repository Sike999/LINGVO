<?php
session_start();

function connect($username, $dbname) {
    try {
        $con = new PDO("mysql:host=localhost;charset=utf8;dbname=$dbname;", $username, '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        $_SESSION['db_username'] = trim($username);
        $_SESSION['db_name'] = trim($dbname);
        return ["Подключение успешно.", $con];
    } catch (PDOException $e) {
        return ["Ошибка: " . $e->getMessage(), null];
    }
}

function getModel($con, $name) {
    if (!$con) return [];
    
    $pdo = $con->prepare("SELECT `COL 1`,`COL 6` FROM `______3` WHERE `COL 2` = ?");
    $pdo->execute([$name]);
    return $pdo->fetchAll(PDO::FETCH_NUM);
}

function desc($con, $num) {
    if (!$con) return [];
    
    $pdo = $con->prepare("SELECT `COL 5` FROM `______3` WHERE `COL 4` = ?");
    $pdo->execute([$num]);
    return $pdo->fetchAll();
}

if (isset($_GET['clear_session'])) {
    session_unset();
    header("Location: 01.php");
    exit;
}

$con = null;
if (isset($_GET["conneсt"])) {
    if (!empty($_GET["username"]) && !empty($_GET["dbname"])) {
        list($message, $con) = connect($_GET["username"], $_GET["dbname"]);
        echo $message;
    }
}

elseif (isset($_SESSION['db_username'], $_SESSION['db_name'])) {
    list($message, $con) = connect($_SESSION['db_username'], $_SESSION['db_name']);
}

if ($con && isset($_GET['modelTrigger'])) {
    if (isset($_GET['fact']) && preg_match('/^\w+(?:\s\w+)*$/', $_GET['fact']) && strlen($_GET['fact']) <= 50) {
        $model = getModel($con, $_GET['fact']);
        foreach ($model as $item) {
            echo "<p>" . htmlspecialchars($item[0]) . "</p>";
            echo '<img src="img/' . htmlspecialchars($item[1]) . '" width="350px">';
        }
    }
}

if ($con && isset($_GET['descTrigger'])) {
    if (isset($_GET['num']) && preg_match('/^[1-7]$/', $_GET['num'])) {
        $desc = desc($con, $_GET['num']);
        foreach ($desc as $d) {
            foreach ($d as $i) {
                echo "<p>" . htmlspecialchars($i) . "</p>";
            }
        }
    }
}
?>
<form method="GET" action="">
    <div>
        <input type="text" placeholder="Имя пользователя" name="username" value="<?= isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '' ?>">
        <input type="text" placeholder="База данных" name="dbname" value="<?= isset($_GET['dbname']) ? htmlspecialchars($_GET['dbname']) : '' ?>">
        <button name="conneсt">Подключиться</button>
    </div>

    <div>
        <input type="text" placeholder="Производитель" name="fact" value="<?= isset($_GET['fact']) ? htmlspecialchars($_GET['fact']) : '' ?>">
        <button type="submit" name="modelTrigger">Поиск</button>
    </div>
    
    <div>
        <input type="text" placeholder="Кол-во мест" name="num" value="<?= isset($_GET['num']) ? htmlspecialchars($_GET['num']) : '' ?>">
        <button name="descTrigger">Поиск</button>
    </div>
    <div>
        <button name="clear_session">Отключиться</button>
    </div>
</form>