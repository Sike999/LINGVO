<head>
    <meta charset="utf-8">
    <title>Вариант 8</title>
</head>
<body>
    <form action="" method="get">
        Размер
    <input type="text" name="size" value = "<?= $_GET["size"] ?? '' ?>"><br>
    <span>Диапазон значений :</span><br>  от <span style=margin-left:156px;>до</span> <br>
        <input type="text" name="start" value = "<?= $_GET["start"] ?? '' ?>"><input type="text" name="end" value = "<?= $_GET["end"] ?? '' ?>"><button>Отправить</button>
    </form>
        <?php 
        function count_negatives(array $array) {
            $i = 0;
            foreach ($array as $x)
                if ($x < 0) $i++;
            return $i;
        }
        if($_GET['size'] > 0  && ($_GET['start'] == 0 || !empty($_GET['start'])) && ($_GET['end'] == 0 || !empty($_GET['end']))  && is_numeric ($_GET['size']) && is_numeric($_GET['start'])  && is_numeric($_GET['end'])) {   
            $start = $_GET['start'];
            $end = $_GET['end'];
            $size = $_GET['size'];
            $arr = [];
            $t2 = [];
            $counter = 0;
            for ($i = 0 ; $i < $size; $i++)
            {
                array_push($arr, mt_rand((float)$start * 100, (float)$end * 100) / 100);
                if ($arr[$i] < 0 && $counter < 2)
                {
                    $counter += 1;
                    array_push($t2,$i);
                }
            }
            if (count_negatives($arr) > 1){?>
            <br>
            <span>Сумма элементов массива, расположенных между первым и вторым
            отрицательными элементами:</span>
            <?php
                echo array_sum(array_slice($arr,$t2[0]+1,$t2[1] - $t2[0] - 1)); }
                else { echo 'В массиве нет двух отрицательных чисел';}
            ?>
            <br>
            <?php
            echo implode(", ",$arr);?>
            <br>
            <span>Номер минимального элемента:</span>
            <?php
            echo array_search(min($arr),$arr) + 1; }
       ?>
       <br>
       <?php 
       $diff = [];
       for ($i = 0; $i < $size; $i++){
        if (abs($arr[$i]) <= 1) {
            array_push($diff, $arr[$i]);
        }
       }
       sort($diff);
       $arr = array_diff($arr,$diff);?>
       <span>Преобразованный массив : </span>
       <?php echo implode(', ', array_merge($diff,$arr)); ?>
</body>