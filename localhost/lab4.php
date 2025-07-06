<!DOCTYPE html>
<html>

<head>
    <title>Вариант 6</title>
    <meta charset="utf-8">
<script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=ci4hZUl6Rdhyy3hHX5d-AAb2Pc0ujqSlnEXrICzpK61NB-p-1RKPFWmIUlkf_tMqS7DpFlVhMFBIQWJ4FrkXfzStkcXmRT_SO_zbTHNc1k1Bye9ChsH-VTMjiCVPyUtwEhqnUXRYIPzK6Jq9pw24lBFS-2U4CHppHwxgsjThs7e3jPyCRA68E9KsV6yYgG9KtwYDewEK0V0w7GzbtWJvUE7stJguFuPxPhNtOvrrsX025gupVTkUbwYnmICA7JBeoNe91cPSA1R-z8BHziXUW_2J88U3ZOcVh6AOrjuyvaewxq4LWD4x-csojdb4xmrtZhKMploWrhkOHn8t474Obw3qn96DniJrUoPxONO-kHKFtgW2u2noJwO4sRi3zTU6Pxc164Ht_MlFNLW82dhRtRZT7619MeWSJBM4ScaqmopWtNDTrFOITYqY_bTcxhZWzl5uhSmmEEfwKl_kFlvOnWPe4-LZLWQ_u68XkEGGoB-jYuLL1b6KU_6k1BTkcslWVSKqnXFT-XT7PNgcuz5C9ewPF7rk0Swz7FPxvhOrorrFaVAk3Lb3a3PHn2Qt7-iB2GrjZKLfdGgrUJ63Xn7sxAAV5I1u55bgTTPGyCt36ggdx43LYlNBDJeowvAUaZZOjt8XqRHT16h4PYs7KaINKMSd5olJiZKBEhEBZUVGX2nO1jEfG1GTgMmIsklCUAy8K9LERc9SrA61TdFcnqd55sdiM4X9sXEXhHzyaXCFPljEJUJqmJc-G7zJJSbwYgTLAsZh9L2Cp33OntsCB5BYOQqamtM5Llh4sr5eAQ-2YFQ0HcBdgk4nB_yTNJtUZyZXEjWsYWxQe9KjcLjw1SGLL2ww08CfUMGc0nJFSz2UCjdsip1XjPQENU5Lt0xgSLGo9H0MOYh1qcQgQOo5JXMsThvVi0sSN_wuMn3tVWhkf1uSpOIjr4ohfbB49kjj3_mf" charset="UTF-8"></script></head>

<body>
    <form action="" method="get">
        <p>Размерность</p>
        <span>N</span> <span style=margin-left:156px;>M</span><br>
        <input type="text" name="N" value="<?= $_GET["N"] ?? '' ?>"><input type="text" name="M" value="<?= $_GET["M"] ?? '' ?>"><button name="confirm">Создать</button>
    </form>
    <?php if (is_numeric($_GET['N']) && is_numeric($_GET['M']) && !empty($_GET['N']) && $_GET['N'] > 0 && !empty($_GET['M']) && $_GET['M'] > 0 && is_int($_GET['M'] * 1) && is_int($_GET['N'] * 1)) { ?>
        <p>Исходный массив
        <p>
            <?php $arr = [];
            for ($i = 0; $i < $_GET['N']; $i++) { ?>

                <?php $row = [];
                array_push($arr, $row); ?>

                <?php
                for ($j = 0; $j < $_GET['M']; $j++) { ?>
                    <?php $arr[$i][$j] = rand(-10, 10); ?>

                <?php
                } ?>

            <?php   } ?>

            <?php function table($arr)
            { ?>
        <table>
            <?php
                foreach ($arr as $row) { ?>
                <tr>
                    <?php
                    foreach ($row as $r) { ?>
                        <td style="border:1px solid black;"> <?php echo $r; ?></td>
                    <?php
                    } ?>
                </tr>
            <?php   } ?>
        </table>
    <?php }
            $arr2 = $arr;
            table($arr); ?>


    <?php foreach ($arr2 as &$row) {
            $max = max($row);
            foreach ($row as &$r) {
                if ($r == $max) {
                    $r = 0;
                }
            }
        } ?>
    <p>Заменить максимальный элемент каждой строки нулем </p>
    <?php table($arr2);
        $arr3 = $arr;
        $newRow = [];
        for ($i = 0; $i < $_GET['M']; $i++) {
            array_push($newRow, 0);
        }
        $N = $_GET['N'];
        for ($i = 0; $i < $N; $i++) {
            if ($arr3[$i][0] % 3 == 0) {
                array_splice($arr3, $i, 0, [$newRow]);
                $i++;
                $N++;
            }
        } ?>
    <p> Вставить перед всеми строками, первый элемент которых делится на 3, строку из нулей</p>
    <?php table($arr3); ?>
    <p> Удалить самый левый столбец, в котором встретится четный отрицательный элемент</p>
    <?php
        $arr4 = $arr;
        if ($_GET['M'] > 1) {
            $t4 = [];
            for ($i = 0; $i < $_GET['N']; $i++) {
                for ($j = 0; $j < $_GET["M"]; $j++) {
                    if ($arr4[$i][$j] < 0 && $arr4[$i][$j] % 2 == 0) {
                        $t4[] = $j;
                    }
                }
            }
            if (!empty($t4)) {
                for ($i = 0; $i < $_GET['N']; $i++) {
                    array_splice($arr4[$i], min($t4), 1);
                }
            }
            table($arr4);
        } else {
            table($arr4);
        }
    ?>
    <br>
    <p>Поменять местами второй и предпоследний столбцы</p>
<?php
        $arr5 = $arr;
        $col2 = [];
        $colprelast = [];
        if ($_GET['M'] > 3) {
            for ($i = 0; $i < $_GET['N']; $i++) {
                $col2[] = $arr5[$i][1];
                $colprelast[] = $arr[$i][$_GET['M'] - 2];
            }
            for ($i = 0; $i < $_GET['N']; $i++) {
                array_splice($arr5[$i], 1, 1);
                array_splice($arr5[$i], $_GET["M"] - 3, 1);
            }
            for ($i = 0; $i < $_GET['N']; $i++) {
                array_splice($arr5[$i], 1, 0, $colprelast[$i]);
            }
            for ($i = 0; $i < $_GET['N']; $i++) {
                array_splice($arr5[$i], $_GET["M"] - 2, 0, $col2[$i]);
            }
            table($arr5);
        } else {
            table($arr5);
        }
    } else if (!isset($_GET["N"]) && !isset($_GET["M"])) {
        echo '';
    } else {
        echo "Введите корректные значения!";
    } ?>
</body>