<?php

// Загружаем XML-файл или создаем новый, если его нет
$xmlFile = 'data.xml';
if (!file_exists($xmlFile)) {
    $xml = new SimpleXMLElement('<Аэропорт></Аэропорт>');
    $xml->asXML($xmlFile);
}

// Функция для отображения XML в виде HTML
function displayXml($xml, $level = 0)
{
    foreach ($xml->children() as $child) {
        echo str_repeat('&nbsp;', $level * 4) . $child->getName() . ': ' . ($child->__toString() ?: '[пусто]') . '<br>';
        displayXml($child, $level + 1);
    }
}

// Загружаем XML для отображения
$xml = simplexml_load_file($xmlFile);

?>
<!DOCTYPE html>
<html>
<head>
    <title>XML Parser</title>
</head>
<body>
    <h2>Содержимое XML</h2>
    <pre><?php displayXml($xml); ?></pre>
    
    <h2>Добавить элемент</h2>
    <form method="post">
        <label>Родительский узел:</label>
        <input type="text" name="parent" required><br>
        <label>Имя нового элемента:</label>
        <input type="text" name="name" required><br>
        <label>Значение:</label>
        <input type="text" name="value"><br>
        <button type="submit" name="add">Добавить</button>
    </form>
</body>
</html>

<?php
// Добавление нового элемента
if (isset($_POST['add'])) {
    $parentName = $_POST['parent'];
    $name = $_POST['name'];
    $value = $_POST['value'];
    
    $xml = simplexml_load_file($xmlFile);
    $parent = $xml->xpath("//$parentName")[0] ?? null;
    
    if ($parent) {
        $newElement = $parent->addChild($name, $value);
        $xml->asXML($xmlFile);
        echo "<p>Элемент добавлен!</p>";
    } else {
        echo "<p>Родительский узел не найден!</p>";
    }
}
?>
