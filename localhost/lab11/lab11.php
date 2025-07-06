<head>
    <meta charset="utf-8">
    <title>Вариант 1</title>
</head>
<body>
    <a href="search.php">Поиск</a>
    <form action="" method="get"></form>
    <?php
    $file = fopen('OLDBASE.txt','r');
    while(!feof($file))
    {
        $person = fgetcsv($file);
        if($person !== false && !in_array("",$person) && count($person) == 17){
        $data[] = $person;
        }
    }
    fclose($file);
    $errors = ["Mail" => 0,"Sex" => 0];


    $pattern = "/^[\w.-]+@[\w.-]+\.\w+$/";
    $invalid=[];
    $i=0;
    $data = array_filter($data, function($line) use ($pattern, $i, &$invalid,&$errors) {

        if($line[4] != 'male' && $line[4] != 'female')
        {
            $line[4] = '';
            $errors["Sex"] += 1;
        }
        if (preg_match($pattern, $line[7])) {
            return true;
        } else {
            $invalid[]=$line;
            return false;
        }
        $i+=1;
    }
  );

    foreach($invalid as &$line)
    {
        
        if(preg_match("/@@/",$line[7]))
        {
            $line[7] = preg_replace("/@@/","@",$line[7]);
            $errors['Mail'] +=1;
        }
        if(preg_match("/\W/",$line[7]))
        {
            $errors['Mail'] +=1;
        }
        if(str_contains($line[7],"@")){
            $parts = preg_split("/@/",$line[7]);
            $parts[0] = preg_replace("/\W/","",$parts[0]);
            $line[7] = implode("@",$parts);
        }
        if(!preg_match("/@/",$line[7]))
        {
            $errors['Mail'] +=1;
            switch($line[7])
            {
                case str_contains($line[7],"trashymail.com"):
                    $position = strpos($line[7], "trashymail.com");
                    $line[7] = substr($line[7], 0, $position) . "@" . substr($line[7], $position);
                    break;
                case str_contains($line[7],"spambob.com"):
                    $position = strpos($line[7], "spambob.com");
                    $line[7] = substr($line[7], 0, $position) . "@" . substr($line[7], $position);
                    break;
                case str_contains($line[7],"dodgit.com"):
                    $position = strpos($line[7], "dodgit.com");
                    $line[7] = substr($line[7], 0, $position) . "@" . substr($line[7], $position);
                    break;
                case str_contains($line[7],"pookmail.com"):
                    $position = strpos($line[7], "pookmail.com");
                    $line[7] = substr($line[7], 0, $position) . "@" . substr($line[7], $position);
                    break;
                case str_contains($line[7],"mailinator.com"):
                    $position = strpos($line[7], "mailinator.com");
                    $line[7] = substr($line[7], 0, $position) . "@" . substr($line[7], $position);
                    break;             
            }
        }
    }
    $i=0;
    foreach($invalid as $line)
    {
        array_push($data,$line);
    }
    foreach($data as &$line)
    {
        $line[8] = preg_replace('/\D/', '', $line[8]);
        $length = strlen($line[8]);
        if ($length == 8) {
            $line[8] = substr($line[8], 0, 1) . '-' . substr($line[8], 1, 3) . '-' . substr($line[8], 4, 4);
        } elseif ($length == 9) {
            $line[8] = substr($line[8], 0, 2) . '-' . substr($line[8], 2, 3) . '-' . substr($line[8], 5, 4);
        } elseif ($length == 10) {
            $line[8] = substr($line[8], 0, 3) . '-' . substr($line[8], 3, 3) . '-' . substr($line[8], 6, 4);
        } 
        $len = strlen($line[0]);
        for($j = 1; $j <= (6-$len); $j++)
        {
            $line[0] = '0' . $line[0];
        }
        if(str_contains($line[12],'.'))
        {
            $line[12] = round((float)$line[12]);
            $line[12] = (string) $line[12];
        }
    }

    $file = fopen('NEWBASE.txt','w');
    $woman = 0;
    $men = 0;
    $avgWomanWeight = 0;
    $avgWomanAge = 0;
    $avgWomanHeight = 0;
    $avgMenWeight = 0;
    $avgMenAge = 0;
    $avgMenHeight = 0;
    foreach($data as $row)
    {
        $age = DateTime::createFromFormat('m/d/Y', $row[9])->diff(new DateTime());
        if($row[4] == "male")
        {
            $men += 1;
            $avgMenWeight += $row[12];
            $avgMenHeight += $row[13];
            $avgMenAge += $age->y;
        }
        else if($row[4] == "female")
        {
            $woman += 1;
            $avgWomanWeight += $row[12];
            $avgWomanHeight += $row[13];
            $avgWomanAge += $age->y;
        }
        fputcsv($file,$row,';');
    }
    fclose($file);
    $avgMenAge /= $men;
    $avgMenHeight /= $men;
    $avgMenWeight /= $men;
    $avgWomanAge /= $woman;
    $avgWomanHeight /= $woman; 
    $avgWomanWeight /= $woman;

    $holidays=['01.01', '07.01', '14.02', '23.02','08.03', '01.05', '31.12'];
    $holidayAndPeople=[];

    $stat = ["womanWeightLessAVG" => 0, "womanWeightHigherAVG" => 0, "womanWeightEqualAVG" => 0,"womanHeightLessAVG" => 0, "womanHeightHigherAVG" => 0, "womanHeightEqualAVG" => 0,
    "womanAgeLessAVG" => 0, "womanAgeHigherAVG" => 0, "womanAgeEqualAVG" => 0,"manWeightLessAVG" => 0, "manWeightHigherAVG" => 0, 
    "manWeightEqualAVG" => 0,"manHeightLessAVG" => 0, "manHeightHigherAVG" => 0, "manHeightEqualAVG" => 0,"manAgeLessAVG" => 0, "manAgeHigherAVG" => 0, "manAgeEqualAVG" => 0];
    foreach ($data as $line) {
        $age = DateTime::createFromFormat('m/d/Y', $line[9])->diff(new DateTime())->y;
    
        if ($line[4] == 'female') {
            $stat['womanHeightHigherAVG'] += $line[13] > $avgWomanHeight;
            $stat['womanHeightLessAVG'] += $line[13] < $avgWomanHeight;
            $stat['womanHeightEqualAVG'] += $line[13] == round($avgWomanHeight);
    
            $stat['womanWeightHigherAVG'] += $line[12] > $avgWomanWeight;
            $stat['womanWeightLessAVG'] += $line[12] < $avgWomanWeight;
            $stat['womanWeightEqualAVG'] += $line[12] == round($avgWomanWeight);
    
            $stat['womanAgeHigherAVG'] += $age > $avgWomanAge;
            $stat['womanAgeLessAVG'] += $age < $avgWomanAge;
            $stat['womanAgeEqualAVG'] += $age == round($avgWomanAge);
        }
    
        if ($line[4] == 'male') {
            $stat['manHeightHigherAVG'] += $line[13] > $avgMenHeight;
            $stat['manHeightLessAVG'] += $line[13] < $avgMenHeight;
            $stat['manHeightEqualAVG'] += $line[13] == round($avgMenHeight);
    
            $stat['manWeightHigherAVG'] += $line[12] > $avgMenWeight;
            $stat['manWeightLessAVG'] += $line[12] < $avgMenWeight;
            $stat['manWeightEqualAVG'] += $line[12] == round($avgMenWeight);
    
            $stat['manAgeHigherAVG'] += $age > $avgMenAge;
            $stat['manAgeLessAVG'] += $age < $avgMenAge;
            $stat['manAgeEqualAVG'] += $age == round($avgMenAge);
        }

    $birthdate = DateTime::createFromFormat('m/d/Y', $line[9]);
    $formattedDate = $birthdate->format('d.m');
    
    if (in_array($formattedDate, $holidays)) {
        $holidayAndPeople[$formattedDate][] = $line[1];
    }

    }
    foreach ($holidayAndPeople as $holidays => $names) {
        echo "Дата: $holidays";?> <br> <?php
        echo "Имена: " . implode(', ', $names); ?> <br> <?php
    }
    ?>
    <br>
    <h3>Статистика по женщинам</h3>
    <?php
    $womanStats = [
        "womanWeightLessAVG" => "Вес меньше среднего",
        "womanWeightHigherAVG" => "Вес выше среднего",
        "womanWeightEqualAVG" => "Вес равен среднему",
        "womanHeightLessAVG" => "Рост меньше среднего",
        "womanHeightHigherAVG" => "Рост выше среднего",
        "womanHeightEqualAVG" => "Рост равен среднему",
        "womanAgeLessAVG" => "Возраст меньше среднего",
        "womanAgeHigherAVG" => "Возраст выше среднего",
        "womanAgeEqualAVG" => "Возраст равен среднему",
    ];

    foreach ($womanStats as $key => $category) {
        ?>
        <div class='category'><span class='category-name'><?= $category . ':'?></span><?= $stat[$key]?></div>
        <?php
    }
    ?>

    <h3>Статистика по мужчинам</h3>
    <?php
    $manStats = [
        "manWeightLessAVG" => "Вес меньше среднего",
        "manWeightHigherAVG" => "Вес выше среднего",
        "manWeightEqualAVG" => "Вес равен среднему",
        "manHeightLessAVG" => "Рост меньше среднего",
        "manHeightHigherAVG" => "Рост выше среднего",
        "manHeightEqualAVG" => "Рост равен среднему",
        "manAgeLessAVG" => "Возраст меньше среднего",
        "manAgeHigherAVG" => "Возраст выше среднего",
        "manAgeEqualAVG" => "Возраст равен среднему",
    ];

    foreach ($manStats as $key => $category) {

        ?>
        <div class='category'><span class='category-name'><?= $category . ':'?></span><?= $stat[$key]?></div>
        <?php
    }
    echo var_dump($errors);
    
    ?>
