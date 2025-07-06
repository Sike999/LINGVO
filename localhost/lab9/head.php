<?php
    require "lab9.php";
?>
<form action="" method="get">
    <a href="web<?=$index?>.php?clicked=1&bannerIndex=<?=$index?>"><img src="0<?=$index?>.gif"></a>
</form>
<?php
$j=1;
foreach($CTR as $value){
echo "CTR" . "$j" . "= " . "$value ";
$j += 1;
}
?>
<br>
<?php
$j=1;
foreach($CTI as $value){
echo "CTI" . "$j" . "= " . "$value ";
$j += 1;
}
?>
<br>
<?php
$j=1;
foreach($CTB as $value){
echo "CTB" . "$j" . "= " . "$value ";
$j += 1;
}
?>