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
        <div class="Calculation">
            <h1>Pасчёт посещений\заказов и прочего:</h1>
            <br>
            <table border="1">
                <tr>
                    <th>Баннер</th>
                    <th>Просмотры страницы</th>
                    <th>Показы банера</th>
                    <th>Переход по банеру</th>
                    <th>Заказы</th>
                    <th>CTR (%)</th>
                    <th>CTI (%)</th>
                    <th>CTB (%)</th>
                </tr>
                <?php
                    function recursive_search($pattern){
                        $foundFiles = [];
                        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator( __DIR__));
                        foreach ($iterator as $file) {
                            if ($file->isFile() && preg_match($pattern, $file->getFilename())) {
                                $foundFiles[] = $file->getPathname();
                            }
                        }
                        return $foundFiles;
                    }

                    if (file_exists('Counter.json')) {
                        $data = json_decode(file_get_contents('Counter.json'), true);
                    } else {
                        ?> 
                            <div class="print">
                                <p>Файл не был найден!</p>
                            </div>
                        <?php
                    }
                    $img = recursive_search('/(0[1-9])\.gif$/');
                    $pages = recursive_search('/Pages' . '(0[1-9])\.php$/');
                   
                    for($i = 0; $i < 5; $i++){
                        $imgNew = explode('\\', $img[$i]);
                        $pagesNew = explode('\\', $pages[$i]);
                        $views = $data[end($pagesNew)]['views'];
                        $impressions = $data[end($pagesNew)]['impressions'];
                         $clicks = $data[end($pagesNew)]['clicks'];
                       $purchases = $data[end($pagesNew)]['purchases'];
                        ?>
                            <tr>
                                <td><img style="width: 70px;" src="/banner/<?= end($imgNew) ?>" alt=""></td>
                                <td><?= $views ?></td>
                                <td><?= $impressions ?></td>
                                <td><?= $clicks ?></td>
                                <td><?= $purchases ?></td>
                                <td><?= $ctr = ($data[end($pagesNew)]['views'] > 0) ? round(($data[end($pagesNew)]['clicks'] / $data[end($pagesNew)]['impressions']) * 100, 2) : 0; ?></td>
                                <td><?= $cti = ($data[end($pagesNew)]['clicks'] > 0) ? round(($data[end($pagesNew)]['clicks'] / $data[end($pagesNew)]['views']) * 100, 2) : 0; ?></td>
                                <td><?= $ctb = ($data[end($pagesNew)]['views'] > 0) ? round(($data[end($pagesNew)]['purchases'] / $data[end($pagesNew)]['clicks']) * 100, 2) : 0; ?></td>
                            </tr>
                        <?php
                    }
                    
                ?>
            </table>
            <br>
            
        </div>
        <hr>
        <div class="go_to_banner">
            <a href="banner.php">Перейти к странице с баннерами</a>
        </div>
    </div>
    <script>
        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };
    </script>
</body>
</html>