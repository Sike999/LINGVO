<html>
<head>
    <meta charset="utf-8">
    <title>Вариант 1</title>

<body>
    <?php
    if (file_exists('input.txt')) {
        $data = file_get_contents('input.txt');

        preg_match_all('/\b(\w{2})\w*\b/ui', $data, $matches);


        for ($i = 0; $i < count($matches[1]); $i++) 
        {
            $matches[1][$i] =  mb_strtolower($matches[1][$i]);
            $firstTwo = $matches[1][$i];
            $word = $matches[0][$i];
            $arr[$firstTwo][] = $word;
        }
        $countRepeat = array_count_values(array_map('mb_strtolower', $matches[1]));

        foreach ($arr as $key => $words) {
            if ($countRepeat[$key] > 1) {
                foreach ($words as $w) {
                    $data = preg_replace('/\b' . preg_quote($w, '/') . '\b/u', '', $data);
                }
            }
        }
        $file = fopen('output.txt','w');
        fwrite($file,trim($data));
        fclose($file);

        echo 'Файл перезаписан';
    } else {
        echo 'Не удалось найти файл input.txt!';
    }
    ?>
</body>
</head>

</html>