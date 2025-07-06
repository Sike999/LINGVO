<?php
$doc = new DOMDocument();
$doc->preserveWhiteSpace = false;
$doc->formatOutput = true;
if (!$doc->load("airports.xml")) {
    exit("Файл airports.xml не найден или произошла ошибка при загрузке.");
}

function renderDOMChildren($node) {
    $html = '<table>';
    $html .= '<tr><th>Элемент</th><th>Значение</th></tr>';
    
    foreach ($node->childNodes as $child) {
        if ($child->nodeType === XML_ELEMENT_NODE) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($child->nodeName) . '</td>';
            $html .= '<td>';
            
            $hasChildElements = false;
            foreach ($child->childNodes as $subChild) {
                if ($subChild->nodeType === XML_ELEMENT_NODE) {
                    $hasChildElements = true;
                    break;
                }
            }
            
            if ($hasChildElements) {
                $html .= renderDOMChildren($child);
            } else {
                $html .= htmlspecialchars($child->nodeValue);
            }
            
            $html .= '</td></tr>';
        }
    }
    
    $html .= '</table>';
    return $html;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>DOM XML Parser</title>
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
  <h2>Аэропорты – DOM Parser</h2>
  <?php
  $airports = $doc->getElementsByTagName("Аэропорт");
  foreach ($airports as $airport):
      $id = $airport->getAttribute("id");
  ?>
    <h3>Аэропорт (ID: <?= htmlspecialchars($id) ?>)</h3>
    <table>
      <tr><th>Элемент</th><th>Значение</th></tr>
      <?php foreach ($airport->childNodes as $node): ?>
        <?php if ($node->nodeType === XML_ELEMENT_NODE): ?>
          <tr>
            <td><?= htmlspecialchars($node->nodeName) ?></td>
            <td>
              <?php
              $hasChildElements = false;
              foreach ($node->childNodes as $child) {
                  if ($child->nodeType === XML_ELEMENT_NODE) {
                      $hasChildElements = true;
                      break;
                  }
              }
              echo $hasChildElements 
                   ? renderDOMChildren($node) 
                   : htmlspecialchars($node->nodeValue);
              ?>
            </td>
          </tr>
        <?php endif; ?>
      <?php endforeach; ?>
    </table>
  <?php endforeach; ?>
</body>
</html>