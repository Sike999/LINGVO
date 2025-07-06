<?php
function elevator($left, $right, $call) {
  if($left === $call && $right != $call){
    return "left";
  }
  elseif($right === $call && $left != $call){
    return "right";
  }
  elseif(abs(($left - $call)) < abs(($right - $call))){
    return "left";
  }
  elseif(abs(($left - $call)) > abs(($right - $call))){
    return "right";
  }
  elseif(abs(($left - $call)) == abs(($right - $call))){
    return "right";
  }
}
  echo elevator(0,1,0) . "<br>";
  echo elevator(0,1,1) . "<br>";
  echo elevator(0,1,2) . "<br>";
  echo elevator(0, 2, 0) . "<br>";
  echo elevator(1, 2, 0) . "<br>";
  echo elevator(2, 1, 0) . "<br>";
  echo elevator(0, 2, 1) . "<br>";
  echo elevator(2, 0, 2) . "<br>";
  echo elevator(2, 1, 2) . "<br>";
  echo elevator(2, 2, 2) . "<br>";



  function decode_morse(string $code): string {
    $alph = ['A' => ".-",'B' => "-...",'C' => "-.-.",'D' => "-..",'E' => ".",'F' => "..-.",'G' => "--.",
            'H' => "....",'I' => "..",'J' => ".---",'K' => "-.-",'L' => ".-..",'M' => "--",'N' => "-.",
            'O' => "---",'P' => ".--.",'Q' => "--.-",'R' => ".-.",'S' => "...",'T' => "-",'U' => "..-",
            'V' => "...-",'W' => ".--",'X' => "-..-",'Y' => "-.--",'Z' => "--..",'1' => "--..",
            '2' => "--..",'3' => "--..",'4' => "--..",'5' => "--..",'6' => "--..",'7' => "--..",
            '8' => "--..",'9' => "--..",'0' => "--..",'SOS' => "···−−−···",'.' => ".-.-.-",'!' => "--..--"];
    $tmp = [];
    $code = trim($code);
    $code = preg_replace("/   /"," $ ",$code);
    $str = explode(" ",$code);
    foreach($str as &$s){
        foreach($alph as $a){
          if($s == $a){
            $tmp [] = array_search($a,$alph);
          }
          else if($s == "$"){
            $s = " ";
            $tmp[] = $s;
          }
        }
    }
    return implode("",$tmp);
}
echo decode_morse("···−−−··· --..-- ");
?>