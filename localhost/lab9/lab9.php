<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>CTR</title>
</head>
<body>
    <?php
        $index = random_int(1,5);
    ?>
    <form action="" method="get">
    </form>
        <?php
        $file = fopen('bannerCount.txt','r');
        while (!feof($file))
        {
            $counts[] = fgets($file) * 1;
        }
        fclose($file);
        $counts[$index - 1] += 1;
        $file = fopen('bannerCount.txt','w');
        fwrite($file,implode("\n",$counts));
        fclose($file);

        $file = fopen('bannerClickCount.txt','r');
        while (!feof($file))
        {
            $click[] = fgets($file) * 1;
        }
        fclose($file);

        $file = fopen('orderCount.txt','r');
        while (!feof($file))
        {
            $orders[] = fgets($file) * 1;
        }
        fclose($file);

        $file = fopen('webCount.txt','r');
        while (!feof($file))
        {
            $open[] = fgets($file) * 1;
        }
        fclose($file);

        for ($i = 0; $i < 5; $i++){
        if($click[$i] != 0 && $open[$i] != 0 && $counts[$i] != 0 && $orders[$i] != 0){
        $CTR[] = $click[$i] / $counts[$i];  
        $CTI[] = $open[$i] / ($click[$i] + $open[$i]);
        $CTB[] = $orders[$i] / $click[$i];
        }
        }
    ?>