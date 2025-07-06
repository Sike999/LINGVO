<?php
$message = "";

$parser = isset($_GET['parser']) ? $_GET['parser'] : 'simple';

if (file_exists("airports.xml")) {
    $xml = simplexml_load_file("airports.xml");
} else {
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Аэропорты/>');
    $xml->asXML("airports.xml");
}

if (isset($_POST['edit'])) {
    $airportId   = $_POST['airport_id'];
    $elementPath = $_POST['element_path'];
    $newValue    = $_POST['new_value'];
    $pathParts = explode("::", $elementPath);
    foreach ($xml->Аэропорт as $airport) {
        if ((string)$airport['id'] === $airportId) {
            $target = $airport;
            foreach ($pathParts as $part) {
                if (isset($target->{$part})) {
                    $target = $target->{$part};
                } else {
                    $message = "Элемент {$elementPath} не найден.";
                    break 2;
                }
            }
            $target[0] = $newValue;
            $message = "Запись успешно обновлена.";
            $xml->asXML("airports.xml");
            break;
        }
    }
}

if (isset($_GET['delete']) && $_GET['delete'] == 'Рейс') {
    $airportId = $_GET['airport_id'];
    foreach ($xml->Аэропорт as $airport) {
        if ((string)$airport['id'] === $airportId) {
            $domAirport = dom_import_simplexml($airport);
            while ($domAirport->hasChildNodes()) {
                $domAirport->removeChild($domAirport->firstChild);
            }
            $message = "Элемент 'Рейс' и все поля аэропорта удалены.";
            $xml->asXML("airports.xml");
            break;
        }
    }
    $xml = simplexml_load_file("airports.xml");
}

if (isset($_POST['add_child_submit'])) {
    $airportId = $_POST['airport_id'];
    foreach ($xml->Аэропорт as $airport) {
        if ((string)$airport['id'] === $airportId) {
            $airport->addChild("Номер", $_POST['номер']);

            $rey = $airport->addChild("Рейс");
            $rey->addChild("Серия", $_POST['рейс_серия']);
            $rey->addChild("Номер", $_POST['рейс_номер']);

            $plane = $airport->addChild("Самолёт");
            $plane->addChild("Код", $_POST['samolot_код']);
            $plane->addChild("Название", $_POST['samolot_название']);

            $avia = $airport->addChild("Авиакомпания");
            $avia->addChild("Код", $_POST['aviakompaniya_код']);
            $avia->addChild("Название", $_POST['aviakompaniya_название']);

            $pass = $airport->addChild("Пассажир");
            $pass->addChild("Код", $_POST['passazhir_код']);
            $pass->addChild("ФИО", $_POST['passazhir_фио']);
            $pass->addChild("Паспорт", $_POST['passazhir_паспорт']);

            $pass = $airport->addChild("Место");
            $pass->addChild("Номер", $_POST['mesto_номер']);
            $pass->addChild("Класс", $_POST['mesto_класс']);

            $route = $airport->addChild("Маршрут");
            $route->addChild("Код", $_POST['marshrut_код']);

            $takeoff = $route->addChild("Отправление");
            $takeoff->addChild("Код", $_POST['otpravlenie_код']);
            $takeoff->addChild("Название", $_POST['otpravlenie_название']);

            $arrival = $route->addChild("Прибытие");
            $arrival->addChild("Код", $_POST['pribytie_код']);
            $arrival->addChild("Название", $_POST['pribytie_название']);

            $message = "Запись успешно добавлена в аэропорт с id $airportId.";
            $xml->asXML("airports.xml");
            break;
        }
    }
}

if (isset($_POST['add_airport_submit'])) {
    $maxId = 0;
    foreach ($xml->Аэропорт as $airport) {
        $curId = (int)$airport['id'];
        if ($curId > $maxId) {
            $maxId = $curId;
        }
    }
    $newId = $maxId + 1;
    $newAirport = $xml->addChild("Аэропорт");
    $newAirport->addAttribute("id", $newId);
    $newAirport->addChild("Номер", $_POST['номер']);

    $rey = $newAirport->addChild("Рейс");
    $rey->addChild("Серия", $_POST['рейс_серия']);
    $rey->addChild("Номер", $_POST['рейс_номер']);

    $plane = $newAirport->addChild("Самолёт");
    $plane->addChild("Код", $_POST['samolot_код']);
    $plane->addChild("Название", $_POST['samolot_название']);

    $avia = $newAirport->addChild("Авиакомпания");
    $avia->addChild("Код", $_POST['aviakompaniya_код']);
    $avia->addChild("Название", $_POST['aviakompaniya_название']);

    $pass = $newAirport->addChild("Пассажир");
    $pass->addChild("Код", $_POST['passazhir_код']);
    $pass->addChild("ФИО", $_POST['passazhir_фио']);
    $pass->addChild("Паспорт", $_POST['passazhir_паспорт']);

    $pass = $newAirport->addChild("Место");
    $pass->addChild("Номер", $_POST['mesto_номер']);
    $pass->addChild("Класс", $_POST['mesto_класс']);

    $route = $newAirport->addChild("Маршрут");
    $route->addChild("Код", $_POST['marshrut_код']);

    $takeoff = $route->addChild("Отправление");
    $takeoff->addChild("Код", $_POST['otpravlenie_код']);
    $takeoff->addChild("Название", $_POST['otpravlenie_название']);

    $arrival = $route->addChild("Прибытие");
    $arrival->addChild("Код", $_POST['pribytie_код']);
    $arrival->addChild("Название", $_POST['pribytie_название']);

    $message = "Новый аэропорт успешно добавлен с id $newId.";
    $xml->asXML("airports.xml");
    $xml = simplexml_load_file("airports.xml");
}

function renderChildren($element, $airportId, $path = "") {
    ob_start();
    ?>
    <table>
      <tr>
        <th>Элемент</th>
        <th>Данные</th>
        <th>Действие</th>
      </tr>
      <?php foreach ($element->children() as $childName => $child): 
          $currentPath = $path ? $path . "::" . $childName : $childName;
      ?>
        <tr>
          <td><?= $childName ?></td>
          <td>
            <?php if ($child->count() > 0): ?>
              <?= renderChildren($child, $airportId, $currentPath) ?>
            <?php else: ?>
              <span class="data-value"><?= (string)$child ?></span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($child->count() == 0): ?>
              <button class="edit-btn" onclick="startEdit(this, '<?= $currentPath ?>', '<?= $airportId ?>')">✏️</button>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
    <?php
    return ob_get_clean();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>парсер</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border: 1px solid #ccc; border-collapse: collapse; margin-bottom: 15px; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 5px 8px; text-align: left; }
    .container { display: flex; }
    .table-container { flex: 1; margin-right: 20px; }
    .edit-container { width: 300px; }
    .parser-buttons a { padding: 5px 10px; margin-right: 5px; background: #eef; text-decoration: none; }
    .parser-buttons a.active { background: #afa; }
    button { background: none; border: none; cursor: pointer; }
    input[type="text"] { padding: 3px; font-size: 14px; }
    .form-inline { display: inline; }
    a.add-btn { text-decoration: none; background: #ddf; padding: 3px 8px; border-radius: 3px; }
    form { margin-bottom: 10px; }
  </style>
  <script>
    function startEdit(btn, elementPath, airportId) {
      var td = btn.parentElement.previousElementSibling;
      var original = td.querySelector('.data-value').innerText;
      td.setAttribute('data-original', original);
      td.innerHTML = '<form method="post" class="form-inline">' +
        '<input type="hidden" name="airport_id" value="'+airportId+'">' +
        '<input type="hidden" name="element_path" value="'+elementPath+'">' +
        '<input type="text" name="new_value" value="'+original+'" style="width:80px;">' +
        '<button type="submit" name="edit" title="Сохранить">💾</button>' +
        '<button type="button" onclick="cancelEdit(this)" title="Отмена">❌</button>' +
        '</form>';
    }
    function cancelEdit(btn) {
      var form = btn.closest('form');
      var td = form.parentElement;
      var original = td.getAttribute('data-original');
      td.innerHTML = '<span class="data-value">'+original+'</span>' +
                     ' <button class="edit-btn" onclick="startEdit(this, \''+ td.getAttribute("data-element") +'\', \''+ td.getAttribute("data-airport") +'\')">✏️</button>';
      if (!td.getAttribute("data-element") || !td.getAttribute("data-airport")) {
          td.innerHTML = '<span class="data-value">'+original+'</span> <button class="edit-btn" onclick="startEdit(this, \''+td.dataset.elementPath+'\', \''+td.dataset.airportId+'\')">✏️</button>';
      }
    }
    window.addEventListener("DOMContentLoaded", function() {
      var tds = document.querySelectorAll("td");
      tds.forEach(function(td) {
        if(td.querySelector('.data-value')){
        }
      });
    });
  </script>
</head>
<body>
  <?php if ($message): ?>
    <p><strong><?= $message ?></strong></p>
  <?php endif; ?>

  <div class="parser-buttons">
    <a href="crud.php?parser=simple" class="<?= ($parser=='simple') ? 'active' : '' ?>">Simple Parser</a>
    <a href="crud.php?parser=dom" class="<?= ($parser=='dom') ? 'active' : '' ?>">DOM Parser</a>
  </div>

  <div class="container">
    <div class="table-container">
      <?php if ($xml): ?>
        <?php foreach ($xml->Аэропорт as $airport): ?>
          <h3>
            Аэропорт (ID: <?= $airport['id'] ?>)
            <a class="add-btn" href="crud.php?add_child=1&airport_id=<?= $airport['id'] ?>&parser=<?= $parser ?>">Добавить запись</a>
          </h3>
          <table>
            <tr>
              <th>Элемент</th>
              <th>Данные</th>
              <th>Действие</th>
            </tr>
            <?php foreach ($airport->children() as $elementName => $element): ?>
              <tr>
                <td><?= $elementName ?></td>
                <td <?php if($element->count()==0): ?> data-element="<?= $elementName ?>" data-airport="<?= $airport['id'] ?>"<?php endif; ?>>
                  <?php if ($element->count() > 0): ?>
                    <?= renderChildren($element, $airport['id'], $elementName) ?>
                  <?php else: ?>
                    <span class="data-value"><?= (string)$element ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($elementName == "Рейс"): ?>
                    <a href="crud.php?delete=Рейс&airport_id=<?= $airport['id'] ?>&parser=<?= $parser ?>" onclick="return confirm('Удалить этот элемент Рейс и очистить аэропорт?');" title="Удалить">❌</a>
                  <?php elseif($element->count()==0): ?>
                    <button class="edit-btn" onclick="startEdit(this, '<?= $elementName ?>', '<?= $airport['id'] ?>')" title="Редактировать">✏️</button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
        <?php endforeach; ?>
      <?php else: ?>
        <p>XML файл не найден.</p>
      <?php endif; ?>

      <div style="margin-top:20px;">
        <a class="add-btn" href="crud.php?add_airport=1&parser=<?= $parser ?>">Добавить аэропорт</a>
      </div>
    </div>

    <div class="edit-container">
      <?php if (isset($_GET['add_child'])): ?>
        <h3>Добавить запись в аэропорт (ID: <?= $_GET['airport_id'] ?>)</h3>
        <form action="crud.php?parser=<?= $parser ?>" method="post">
          <input type="hidden" name="airport_id" value="<?= $_GET['airport_id'] ?>">
          <p><strong>Номер:</strong><br>
             <input type="text" name="номер" required></p>
          <p><strong>Рейс</strong><br>
             Серия: <input type="text" name="рейс_серия" required> 
             Номер: <input type="text" name="рейс_номер" required></p>
          <p><strong>Самолёт</strong><br>
             Код: <input type="text" name="samolot_код" required> 
             Название: <input type="text" name="samolot_название" required></p>
          <p><strong>Авиакомпания</strong><br>
             Код: <input type="text" name="aviakompaniya_код" required> 
             Название: <input type="text" name="aviakompaniya_название" required></p>
          <p><strong>Пассажир</strong><br>
             Код: <input type="text" name="passazhir_код" required> 
             ФИО: <input type="text" name="passazhir_фио" required>
             Паспорт: <input type="text" name="passazhir_паспорт" required></p>
          <p><strong>Место</strong><br>
             Номер: <input type="text" name="mesto_номер" required> 
             Класс: <input type="text" name="mesto_класс" required></p>
          <p><strong>Маршрут</strong><br>
             Код: <input type="text" name="marshrut_код" required></p>
          <p><strong>Отправление</strong><br>
             Код: <input type="text" name="otpravlenie_код" required> 
             Название: <input type="text" name="otpravlenie_название" required></p>
          <p><strong>Прибытие</strong><br>
             Код: <input type="text" name="pribytie_код" required> 
             Название: <input type="text" name="pribytie_название" required></p>
          <p><input type="submit" name="add_child_submit" value="Добавить запись"></p>
        </form>
      <?php elseif (isset($_GET['add_airport'])): ?>
        <h3>Добавить новый аэропорт</h3>
        <form action="crud.php?parser=<?= $parser ?>" method="post">
          <p><strong>Номер:</strong><br>
             <input type="text" name="номер" required></p>
          <p><strong>Рейс</strong><br>
             Серия: <input type="text" name="рейс_серия" required> 
             Номер: <input type="text" name="рейс_номер" required></p>
          <p><strong>Самолёт</strong><br>
             Код: <input type="text" name="samolot_код" required> 
             Название: <input type="text" name="samolot_название" required></p>
          <p><strong>Авиакомпания</strong><br>
             Код: <input type="text" name="aviakompaniya_код" required> 
             Название: <input type="text" name="aviakompaniya_название" required></p>
          <p><strong>Пассажир</strong><br>
             Код: <input type="text" name="passazhir_код" required> 
             ФИО: <input type="text" name="passazhir_фио" required>
             Паспорт: <input type="text" name="passazhir_паспорт" required></p>
          <p><strong>Место</strong><br>
             Номер: <input type="text" name="mesto_номер" required> 
             Класс: <input type="text" name="mesto_класс" required></p>
          <p><strong>Маршрут</strong><br>
             Код: <input type="text" name="marshrut_код" required></p>
          <p><strong>Отправление</strong><br>
             Код: <input type="text" name="otpravlenie_код" required> 
             Название: <input type="text" name="otpravlenie_название" required></p>
          <p><strong>Прибытие</strong><br>
             Код: <input type="text" name="pribytie_код" required> 
             Название: <input type="text" name="pribytie_название" required></p>
          <p><input type="submit" name="add_airport_submit" value="Добавить аэропорт"></p>
        </form>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
