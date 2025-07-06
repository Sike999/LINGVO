<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
</head>

<body>
    <a href="lab11.php">Главная</a>
    <form>
        <label for="state">Выберите область</label>
        <select id="state" name="state">
            <?php
            function SortArray(array $a, array $b): int
            {
                return strcmp($a[0], $b[0]);
            }

            $states = array();
            $file = fopen("NEWBASE.txt", "r");
            while (!feof($file)) {
                $data = fgetcsv($file, separator: ';');
                if ($data !== false) {
                    if (!array_key_exists($data[6], $states)) {
                        $states[$data[6]] = array();
                        $date = DateTime::createFromFormat('d/m/Y', $data[9]);
                        $now = new DateTime();
                        $age = $now->diff($date)->y;
                        $states[$data[6]][] = [$data[1] . ' ' . $data[3], $data[4], $age, $data[14]];
            ?>
                        <option value="<?= $data[6] ?>" <?= !empty($_GET['state']) && $_GET['state'] == $data[6] ? 'selected' : '' ?>><?= $data[6] ?></option>
            <?
                    } else {
                        $date = DateTime::createFromFormat('d/m/Y', $data[9]);
                        $now = new DateTime();
                        $age = $now->diff($date)->y;
                        $states[$data[6]][] = [$data[1] . ' ' . $data[3], $data[4], $age, $data[14]];
                    }
                }
            }
            ?>
        </select>
        <button type="submit">Поиск</button>
    </form>
    <?php
    if (!empty($_GET['state']) && array_key_exists($_GET['state'], $states)) {
        usort($states[$_GET['state']], 'SortArray');
        foreach ($states[$_GET['state']] as $value) { ?>
            <p><span style = "color:<? if ($value[1]=="male"){echo "blue";} else echo 'pink';?>"><?= $value[0] ?></span>, Возраст: <?= $value[2] ?>, Адрес: <?= $value[3] ?></p>
    <?php
        }
    } else {
        echo 'Данные отсутствуют';
    }
    ?>
</body>

</html>
