<?php
function quarterOf($month) {
    return ceil($month / 3);
  }
  echo quarterOf(12) . "<br>";

  function pillars($numberOfPillars, $dist, $width)
  {
    return ($numberOfPillars-1) * ($dist/100 + $width);
  }
  echo pillars(1, 10, 10);

  function draw_stairs($n){
    $res = "";
    $space = "";
    for($i = 0; $i < $n-1; $i++){
      $space = $space . "a";
      $res = $res . "I\n" . $space;
    }
    return $res . "I";
  }
  echo draw_stairs(5);

  function countsheep($num){
    $res1 = "";
    if($num > 0){
      for ($i = 0; $i < $num; $i++){
       $res1 = $res1 . $i+1 . ' sheep...';
      return $res1;}
      }
    else{
      return $res1;
    }}
    echo countsheep(1);
?>