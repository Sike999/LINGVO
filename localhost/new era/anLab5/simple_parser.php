<?php
if (!file_exists("airports.xml")) {
    exit("Файл airports.xml не найден.");
}
$xml = simplexml_load_file("airports.xml");

function renderChildren($element) {
    $html = '<table>';
    $html .= '<tr><th>Элемент</th><th>Значение</th></tr>';
    
    foreach ($element->children() as $childName => $child) {
        $html .= '<tr>';
        $html .= '<td>' . $childName . '</td>';
        $html .= '<td>';
        
        if ($child->count() > 0) {
            $html .= renderChildren($child);
        } else {
            $html .= htmlspecialchars((string)$child);
        }
        
        $html .= '</td></tr>';
    }
    
    $html .= '</table>';
    return $html;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Simple XML Parser</title>
  <style>
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
      padding: 5px;
    }
    table { margin-bottom: 20px; }
  </style>
</head>
<body>
  <h2>Аэропорты – Simple Parser</h2>
  <?php foreach ($xml->children() as $airport): ?>
    <h3>Аэропорт (ID: <?= $airport['id'] ?>)</h3>
    <table>
      <tr>
        <th>Элемент</th>
        <th>Значение</th>
      </tr>
      <?php foreach ($airport->children() as $elementName => $element): ?>
        <tr>
          <td><?= $elementName ?></td>
          <td>
            <?php if ($element->count() > 0): ?>
              <?= renderChildren($element) ?>
            <?php else: ?>
              <?= htmlspecialchars((string)$element) ?>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endforeach; ?>
</body>
</html>