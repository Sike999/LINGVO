<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "var3AI";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$destination = isset($_POST['destination']) ? $_POST['destination'] : '';
$date_range = isset($_POST['date_range']) ? $_POST['date_range'] : '';

$query = "SELECT *, STR_TO_DATE(`Дата`, '%d.%m.%y') as formatted_date FROM `tab` WHERE 1";

// Фильтр по пункту назначения
if ($destination) {
    $query .= " AND `Пункт` = ?";
}

// Фильтр по диапазону дат
if ($date_range) {
    switch ($date_range) {
        case '15-31-jan':
            $query .= " AND STR_TO_DATE(`Дата`, '%d.%m.%y') BETWEEN '2018-01-15' AND '2018-01-31'";
            break;
        case '1-feb-1-mar':
            $query .= " AND STR_TO_DATE(`Дата`, '%d.%m.%y') BETWEEN '2018-02-01' AND '2018-03-01'";
            break;
        case '2-15-mar':
            $query .= " AND STR_TO_DATE(`Дата`, '%d.%m.%y') BETWEEN '2018-03-02' AND '2018-03-15'";
            break;
    }
}

$query .= " ORDER BY `Кассир` ASC";

$stmt = $conn->prepare($query);

if ($destination) {
    $stmt->bind_param("s", $destination);
}

$stmt->execute();
$result = $stmt->get_result();

// Функция для расчета средней цены и максимальной суммы продаж
function calculate_totals($result) {
    $total_price = 0;
    $total_quantity = 0;
    $max_sales = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sales = $row['Цена'] * $row['Кол-во'];
            $total_price += $sales;
            $total_quantity += $row['Кол-во'];
            if ($sales > $max_sales) {
                $max_sales = $sales;
            }
        }
    }

    $avg_price = $total_quantity > 0 ? $total_price / $total_quantity : 0;
    return [
        'avg_price' => $avg_price,
        'max_sales' => $max_sales
    ];
}

$calc_result = $conn->query($query);
$totals = calculate_totals($calc_result);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выборка данных</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Выгрузка из базы данных</h1>

    <form method="post">
        <label for="destination">Пункт:</label>
        <select name="destination" id="destination">
            <option value="">Все</option>
            <option value="Москва" <?= ($destination == 'Москва') ? 'selected' : ''; ?>>Москва</option>
            <option value="Нижневартовск" <?= ($destination == 'Нижневартовск') ? 'selected' : ''; ?>>Нижневартовск</option>
            <option value="Новосибирск" <?= ($destination == 'Новосибирск') ? 'selected' : ''; ?>>Новосибирск</option>
            <option value="Тюмень" <?= ($destination == 'Тюмень') ? 'selected' : ''; ?>>Тюмень</option>
        </select>

        <label for="date_range">Дата продажи:</label>
        <select name="date_range" id="date_range">
            <option value="">Все</option>
            <option value="15-31-jan" <?= ($date_range == '15-31-jan') ? 'selected' : ''; ?>>15 - 31 января</option>
            <option value="1-feb-1-mar" <?= ($date_range == '1-feb-1-mar') ? 'selected' : ''; ?>>1 февраля - 1 марта</option>
            <option value="2-15-mar" <?= ($date_range == '2-15-mar') ? 'selected' : ''; ?>>2 - 15 марта</option>
        </select>

        <button type="submit">Выгрузить данные</button>
    </form>

    <h2>Данные из базы:</h2>
    <table>
        <tr>
            <th>Кассир</th>
            <th>Пункт</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th>Дата</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Кассир']); ?></td>
                    <td><?= htmlspecialchars($row['Пункт']); ?></td>
                    <td><?= htmlspecialchars($row['Цена']); ?></td>
                    <td><?= htmlspecialchars($row['Кол-во']); ?></td>
                    <td><?= htmlspecialchars($row['Дата']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Данные не найдены</td>
            </tr>
        <?php endif; ?>
    </table>

    <h2>Рассчитанные значения:</h2>
    <table>
        <tr>
            <th>Средняя цена билетов</th>
            <th>Максимальная сумма продаж</th>
        </tr>
        <tr>
            <td><?= number_format($totals['avg_price'], 2) ?> руб.</td>
            <td><?= number_format($totals['max_sales'], 2) ?> руб.</td>
        </tr>
    </table>
</body>
</html>

<?php
$conn->close();
?>
