<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Вариант 3</title>
</head>
<body>
    <form action="" method="get">
    </form>
<body>
<?php
    $file = fopen('Лаб_Парсер.htm','r');
    $pattern = '/<span class="text2"><a href="\/(.+?)\/" style="color:#FFFFFF">(.+?)<\/a><\/span>/';
    while(!feof($file))
    {
        $str = fgets($file);
        if(preg_match($pattern,$str)){
        $str = strip_tags($str);
        $str = str_replace('&nbsp;', ' ', $str);
        $pars[]=trim($str);}
        
    }
    fclose($file);
    $count = 0;
    $words=[];
    foreach($pars as $s)
    {
        $word = preg_split('/\s/',$s);
        foreach($word as $w)
        {
            array_push($words,$w);
        }
    }
    foreach($words as $w)
    {
        if(preg_match("/^[A-Z]|^[А-Я]/u",$w)){
            $count += 1;
        }
    }
    $pars[]="Слов напечатанных с заглавной буквы: " . $count;
    $file = fopen('res.txt','w');
    if (fwrite($file,implode("\n",$pars)) === FALSE);
    echo "Готово";
    fclose($file);
?>