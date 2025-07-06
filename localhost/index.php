<!DOCTYPE html>
<head>
    <meta charset="utf-8">
</head>

<body>
<form action="" method="get">
    <div style=" width:500px; margin:0 auto;padding-left:60px;">
    <h1 name="h1">Введите СНИЛС</h1>
    <input type="text" name="snils" style = "margin-right:5px;"><button name="confirm">Отправить</button>
    </div>
    <br><br>
</form>
<?php  
    if(!empty($_GET['snils']) && is_numeric($_GET['snils']) && (strlen($_GET['snils']) == 11) && $_GET['snils'] > "001001998"){?>
        <?php 
        $snils = $_GET['snils'];
        $sum=0;
            for($i = 0; $i <= strlen($snils)-3; $i++){ 
                $sum += $snils[$i]*(strlen($snils)-($i+2));}
                echo $sum?>
                <span style='margin-left:270px;'>Контрольная сумма равна:</span>
                <?php 
                if ($sum < 100){ 
                    echo $sum; }
                 elseif ($sum == 100 || $sum == 101) {
                    echo "00"; }  
                 elseif ($sum > 101) {
                     echo $sum % 101; }
                 }?>

</body>
