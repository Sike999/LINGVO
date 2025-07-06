<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Вариант 3</title>
</head>
<body>
    <form action="" method="get">
        <p>Рубрика</p>
        Технологии <input type="radio" name="rubrika" value="tech" checked>
        Спорт <input type="radio" name="rubrika" value="sport">
        <br>
        <br>
        <input type="text" name="search" value='<?= $_GET["search"] ?? '' ?>'><button>Найти</button>
    </form>
    <?php
            function findHeader($value,$rub){
                $dir = glob('*');
                foreach($dir as $folder)
                {
                    if(preg_match("/[2]{1}[0-2]{1}([0-1]{1}[0-9]{1}|[2]{1}[0-4])(0[0-9]{1}|1[0-2]{1})([0-2]{1}[0-9]{1}|[3]{1}[0-1])/", $folder)) {
                    chdir($folder);
                    $files = glob('*.txt');
                    foreach($files as $file)
                    {
                        $data = trim(fgets(fopen($file,'r')));
                        if (!empty($data) && str_contains($data,$value) && str_contains($rub,$file)){
                            $res[] = $data;
                        }
                    }
                    chdir('..');
                    }
                }
                if(!empty($res)){
                    if ($rub == 'sport'){
                    return $res;
                    }
                }
                else{
                    return 'Найдено 0 статей.';
                }
                }
    if(!empty($_GET['search']) && !empty($_GET['rubrika'])) {
        if(findHeader($_GET['search'],$_GET['rubrika']) != 'Найдено 0 статей.'){
        echo implode('',findHeader($_GET['search'],$_GET['rubrika']));}
        else
        {
            echo findHeader($_GET['search'],$_GET['rubrika']);
        }
    }
    else
    {
        echo 'Введите запрос.';
    } 
    ?>
</body>