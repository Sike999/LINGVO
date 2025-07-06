<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon.png" type="image/x-icon">
    <link href="style.css" rel="stylesheet">
    <title>Баннер</title>
</head>
<body>
    <div class="main">
        <div class="heder_banner">
            <div class="banner">
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

                    $pattern_banner = '/(0[1-9])\.gif$/';
                    $pattern_pages = '/Pages' . '(0[1-9])\.php$/';
                    $get_banners = recursive_search($pattern_banner);
                    $get_pages = recursive_search($pattern_pages);
                    
                    $rand_value = rand(0, count($get_banners)-1);
                    
                    $get_pages = explode('\\', $get_pages[$rand_value]);
                    $get_banners = explode('\\', $get_banners[$rand_value]);

                    $filename = 'Counter.json';

                    if (file_exists($filename)) {
                        $data = json_decode(file_get_contents($filename), true);
                    } else {
                        $data = [];
                        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
                    }
        
                    $page = 
                    $action = 'impressions';
        
                    if (isset($data[end($get_pages)])) {
                        if (isset($data[end($get_pages)][$action])) {
                            $data[end($get_pages)][$action]++;
                        } else {
                            $data[end($get_pages)][$action] = 1;
                        }
                    } else {
                        $data[end($get_pages)] = [
                            'impressions' => 0,
                            'views' => 0,
                            'clicks' => 0,
                            'purchases' => 0
                        ];
                        $data[end($get_pages)][$action] = 1;
                    }
        
                    if (file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT))) {
                    } else {
                        ?>
                        <div class="print">
                            <h3>Не удалось открыть файл для записи</h3>
                        </div>
                        <?php
                    }
                ?>
                <a href="<?= end($get_pages) ?>"><img src="<?= 'banner/' . end($get_banners) ?>" alt="Банер не был найден"></a>
            </div>
        </div>
    </div>
</body>
</html>