<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='style.css' rel='stylesheet'>
    <link rel="icon" href="icon.png" type="image/x-icon">
    <title>Lab_09</title>
</head>
<body>
    <div class="main">
        <div class="print_res">
            <h2>Заказ был успешно оформлен!</h2>
            <p>Ожидайте доставки.</p>
            <a href="main.php">Вернуться на главную</a>
        </div>
    </div>
    <?php
            $site = @$_SERVER['HTTP_REFERER'];
            $orderSite = explode('/', $site);
            $namePagesOrder = end($orderSite);
            if($namePagesOrder != ''){
                $filename = 'Counter.json';

            if (file_exists($filename)) {
                $data = json_decode(file_get_contents($filename), true);
            } else {
                $data = [];
                file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
            }

            $page = $_SERVER['REQUEST_URI']; 
            $page = explode('/', $page);

            if((isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] == 'http://lab09/' . $namePagesOrder)) 
            && (isset($_GET['data']) && $_GET['data'] == 'banner')){
                $action = 'purchases';

                if (isset($data[$namePagesOrder])) {
                    if (isset($data[$namePagesOrder][$action])) {
                        $data[$namePagesOrder][$action]++;
                    } else {
                        $data[$namePagesOrder][$action] = 1;
                    }
                } else {
                    $data[$namePagesOrder[1]] = [
                        'impressions' => 0,
                        'views' => 0,
                        'clicks' => 0,
                        'purchases' => 0
                    ];
                    $data[$namePagesOrder][$action] = 1;
                }

                if (file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT))) {
                } else {
                    ?>
                    <div class="print">
                        <h3>Не удалось открыть файл для записи</h3>
                    </div>
                    <?php
                }
            } 
        }
    ?>
</body>
</html>