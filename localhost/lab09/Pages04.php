<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon.png" type="image/x-icon">
    <link href="style.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <?php
        if ($file = @fopen('info for site\Текст04.txt', 'r')) {
            $input = [];
            while ($data = fgets($file)) {
                $input[] = $data;
            }
            foreach ($input as $inpt) {
                ?>
                <div class="print">
                    <p><?= $inpt ?></p>
                </div>
                <?php
            }

            fclose($file);
        } else {
            ?>
                <div class="print">
                    <h3>Не удалось открыть файл 'Текст04.txt'</h3>
                </div>
            <?php
        }
    ?>
    


    <?php
                        $filename = 'Counter.json';

                        if (file_exists($filename)) {
                            $data = json_decode(file_get_contents($filename), true);
                        } else {
                            $data = [];
                            file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
                        }
            
                        $page = $_SERVER['REQUEST_URI']; 
                        $page = explode('/', $page);
            
                        if(isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] == 'http://lab09/banner.php')){
                        $action = 'clicks';
            
                        if (isset($data[$page[1]])) {
                            if (isset($data[$page[1]][$action])) {
                                $data[$page[1]][$action]++;
                            } else {
                                $data[$page[1]][$action] = 1;
                            }
                        } else {
                            $data[$page[1]] = [
                                'impressions' => 0,
                                'views' => 0,
                                'clicks' => 0,
                                'purchases' => 0
                            ];
                            $data[$page[1]][$action] = 1;
                        }
                        $ban = 'resout_order.php . ?data=banner';            
                        if (file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT))) {
                        } else {
                            ?>
                            <div class="print">
                                <h3>Не удалось открыть файл для записи</h3>
                            </div>
                            <?php
                        }
                    } elseif (!(isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] == 'http://lab09/banner.php'))) {
                        $ban = 'resout_order.php';
                    }
                    $action = 'views';
            
                    if (isset($data[$page[1]])) {
                        if (isset($data[$page[1]][$action])) {
                            $data[$page[1]][$action]++;
                        } else {
                            $data[$page[1]][$action] = 1;
                        }
                    } else {
                        $data[$page[1]] = [
                            'impressions' => 0,
                            'views' => 0,
                            'clicks' => 0,
                            'purchases' => 0
                        ];
                        $data[$page[1]][$action] = 1;
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

<div class="button_down">
        <a class="toBottom" data-to="section_3" href="javascript:void(0);">
            <svg xmlns="http://www.w3.org/2000/svg" id="arrow-circle-down" viewBox="0 0 24 24" width="25" height="35"><path d="M23.148,11.973l-9.38,9.295c-.944,.945-2.589,.947-3.537-.002L.852,11.973c-.196-.194-.513-.192-.707,.004-.194,.195-.193,.513,.003,.707l9.377,9.291c.661,.661,1.54,1.025,2.475,1.025s1.814-.364,2.473-1.023l9.379-9.293c.196-.194,.197-.512,.003-.707-.194-.196-.51-.198-.707-.004Z"/><path d="M23.149,1.644L13.06,11.561c-.565,.566-1.551,.569-2.124-.003L.851,1.644c-.198-.194-.514-.192-.707,.006-.194,.197-.191,.514,.006,.707L10.232,12.268c.472,.473,1.1,.732,1.768,.732s1.296-.26,1.765-.729L23.851,2.356c.197-.193,.2-.51,.006-.707-.193-.198-.51-.2-.707-.006Z"/></svg>
        </a>
    </div>
    <hr>
    <div class="Order">
        <div class="button_order">
            <a href="<?= $ban ?>">
                Закакзать
            </a>
        </div>
    </div>
    <div id="section_3"></div>

    <script>
        window.onload = function() {
            let nav = document.querySelectorAll(".toBottom");
            for (let i = 0; i < nav.length; i++) {
                nav[i].onclick = function(){
                    let where = document.getElementById(nav[i].getAttribute("data-to"));
                    window.scrollBy(0, where.getBoundingClientRect().top - document.documentElement.clientHeight + where.offsetHeight);	
                };
            }	
        }; 
    </script>
</body>
</html>
