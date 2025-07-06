<?php
$xmlFile = 'parse.xml';
if (!file_exists($xmlFile)) {
    $xml = new DOMDocument("1.0", "UTF-8");
    $root = $xml->createElement("entries");
    $xml->appendChild($root);
    $xml->save($xmlFile);
}

function loadXML($xmlFile) {
    $xml = new DOMDocument();
    $xml->load($xmlFile);
    return $xml;
}

function getNodeNames($node, &$names = []) {
    if ($node->nodeType == XML_ELEMENT_NODE) {
        $names[$node->nodeName] = $node->nodeName;
        foreach ($node->childNodes as $child) {
            getNodeNames($child, $names);
        }
    }
    return $names;
}

function displayXML($node, $depth = 0) {
    echo str_repeat("&nbsp;&nbsp;", $depth) . "<strong>" . $node->nodeName . "</strong>";

    if (!$node->hasChildNodes() || ($node->childNodes->length === 1 && $node->firstChild->nodeType === XML_TEXT_NODE)) {
        echo ": " . htmlspecialchars($node->nodeValue);
    }
    
    echo "<br>";

    foreach ($node->childNodes as $child) {
        if ($child->nodeType == XML_ELEMENT_NODE) {
            displayXML($child, $depth + 1);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xml = loadXML($xmlFile);
    $root = $xml->documentElement;
    
    if (isset($_POST['add'])) {
        $selectedNode = $_POST['nodeSelect']; // Получаем выбранный узел
        $nodeName = $_POST['name'];
        $nodeValue = $_POST['value'];
    
        if (!empty($selectedNode)) {
            $parentNodes = $xml->getElementsByTagName($selectedNode);
            if ($parentNodes->length > 0) {
                $parentNode = $parentNodes->item(0); // Берем первый найденный узел
                $newNode = $xml->createElement($nodeName, $nodeValue);
                $parentNode->appendChild($newNode);
                $xml->save($xmlFile);
            }
        }
    }
    
    
    if (isset($_POST['delete'])) {
        foreach ($root->childNodes as $entry) {
            if ($entry->nodeType == XML_ELEMENT_NODE && $entry->nodeName == $_POST['name']) {
                $root->removeChild($entry);
                break;
            }
        }
        $xml->save($xmlFile);
    }
    
    function debug_log($message) {
        file_put_contents('debug.log', date("Y-m-d H:i:s") . " - " . $message . "\n", FILE_APPEND);
    }
    
    if (isset($_POST['edit'])) {
        $oldName = $_POST['old_name'];
        $newName = $_POST['new_name'];
        $newValue = $_POST['new_value'];
    
        debug_log("Попытка редактирования узла: $oldName -> $newName ($newValue)");
    
        foreach ($root->childNodes as $entry) {
            debug_log("Проверяем узел: " . $entry->nodeName); // Записываем каждый узел
    
            if ($entry->nodeType == XML_ELEMENT_NODE && $entry->nodeName == $oldName) {
                debug_log("Найден узел: " . $entry->nodeName);
    
                $newNode = $xml->createElement($newName, $newValue);
    
                foreach ($entry->attributes as $attr) {
                    $newNode->setAttribute($attr->nodeName, $attr->nodeValue);
                }
    
                foreach ($entry->childNodes as $child) {
                    $newNode->appendChild($child->cloneNode(true));
                }
    
                $root->replaceChild($newNode, $entry);
                $xml->save($xmlFile);
    
                debug_log("Узел заменен на: " . $newNode->nodeName);
                break;
            }
        }
    }
debug_log($root);
    foreach ($root->childNodes as $entry) {
        debug_log("Проверяем узел: " . $entry);
    
}
}

$xml = loadXML($xmlFile);
$nodeNames = getNodeNames($xml->documentElement);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XML Parser</title>
</head>
<body>
    <h2>XML Content</h2>
    <?php displayXML($xml->documentElement); ?>
    
    <h3>Add Entry</h3>
    <form method="POST">
        <select name="nodeSelect">
            <?php foreach ($nodeNames as $name) { ?>
              <option value="<?= $name; ?>"><?= $name; ?></option>
            <?php } ?>
        </select>
        <label>Name: <input type="text" name="name" required></label>
        <label>Value: <input type="text" name="value" required></label>
        <button type="submit" name="add">Add</button>
    </form>
    
    <h3>Delete Entry</h3>
    <form method="POST">
        <label>Name: <input type="text" name="name" required></label>
        <button type="submit" name="delete">Delete</button>
    </form>
    
    <h3>Edit Entry</h3>
    <form method="POST">
        <label>Old Name: <input type="text" name="old_name" required></label>
        <label>New Name: <input type="text" name="new_name" required></label>
        <label>New Value: <input type="text" name="new_value" required></label>
        <button type="submit" name="edit">Edit</button>
    </form>
    
</body>
</html>

