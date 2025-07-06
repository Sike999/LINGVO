
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вариант 7</title>
<script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=IfwrtLGcG3Ov4Lwf99dj3lF0PRnqpbiJsemOGwP5JWqQBUgVtAhMMKNBMcF5XQwKct3NoCGAuFNFWcgyRRfkvoudOM5Jq1hWyeusGXEqzRexZTRSh62oouZuS28UA8mtEufrems-t-PlefEn4LVs63MxT6fflQbdazcV79knZPC6QDnnm3nyV2UUFQKy380BASOpMBKCkkTG1EIEVDF-1n1kqi2Ph3tHGskhZ7ud1cC8T4k9kZT8QRJbzI5CL9CXs1gtUMB0r5wRXvmd3UEuilPxh1ax89B4kzbXL75HbcCSjeKCCEhOFR3czBBLodyoPdXfUnzobarwgvUfAc24d2jTIJNYF3EcJvvPA_O1Zb5YDNP19fpbj7smLugZpwSjq-WjI04VcgLE0HN45wYMDMmWiiqFGa707j6-aRxOMOV-9lR7Ptl5AvaMkYHPPgwUraCw0KQ_oXF7RZkGbbINDaYIukTUhIT35Yai_mBLWT_kdwu7e9st28A2iN-qh5xgQFlwiZD68WO-s0_lcJSyckYOPvo4TorqwzsvQ4zO2aweO7C8rzDoOEMpzbdW02EO94qzwhO1oX246QIR5BXuob-k1zImHcXj9SIryi05F42xZVb6sAyBy9IKPTyE6s0PF_ZnunnxzLBlspACitqzg2qpuV0bYGxMNqEa4dImXn411Bjy969LQQF1EfpCRGxghKQmKf4NPihcgMLoCQe39Jfhx_AicawMVw8kWwglcdmPpc40S0AXEXv_abt-I0RPg9TepXJ96cpYoRzktLfx2Lmp6SGYDwPUNzAjiDhJM7OtZuRuDHELo04qIyIW_1sT_M1ZX35NUj7PPu_QQThLd7unu_BPuhhUggrmiKyc0Qe06IFkrLKH3kVH0xfeeJkVBjhTRaXRm8iTNdIfJZ42CqUEi4wEAdsynQgpNIvMzLP7NPoKeTAuZAX2db-1Yvs4" charset="UTF-8"></script></head>
<form method="get">
    <input type="text" name='text' value="<?= $_GET['text'] ?? '' ?>" size=40> <button name='button'>Отправить</button>
</form>
<body>
<?php
if (mb_substr($_GET['text'], -1) === '.' && isset($_GET['text'])) {
    $text = $_GET['text'];
    $text = trim($text, '.');
    $arr = preg_split('/\s+/', $text);
    $arr = array_merge(array_filter($arr, fn($str) => preg_match_all('/^[А-Яа-яЁё]+$/u', $str)));
    $text = implode(' ', $arr);

    function checkSequence($word) {
        $glas = 'аеёиоуыэюя';
        $soglas = 'бвгджзйклмнпрстфхцчшщ';
        $isVowel = in_array(mb_strtolower(mb_substr($word, 0, 1)), preg_split('//u', $glas, -1, PREG_SPLIT_NO_EMPTY));
        for ($i = 1; $i < mb_strlen($word); $i++) {
            $char = mb_strtolower(mb_substr($word, $i, 1));
            if (($isVowel && in_array($char, preg_split('//u', $glas, -1, PREG_SPLIT_NO_EMPTY))) || 
                (!$isVowel && in_array($char, preg_split('//u', $soglas, -1, PREG_SPLIT_NO_EMPTY)))) {
                return false;
            }
            $isVowel = !$isVowel;
        }
        return true;
    }

    $words = explode(' ', $text);
    $resultWords = [];
    $match = [];

    $lowercaseWords = array_map('mb_strtolower', $words);
    foreach ($words as $word) {
        if (checkSequence($word)) {
            $resultWords[] = $word;
        }
    }


    $lowercaseResultWords = array_map('mb_strtolower', $resultWords);
    $lastWord = mb_strtolower(end($resultWords));
    $resultWords = array_filter($resultWords, function($word) use ($lastWord) {
        return mb_strtolower($word) !== $lastWord;
    });

    if (!empty($resultWords)) {
        echo 'Результат: ' . implode(' ', $resultWords);
    } else {
        echo 'Нет слов, удовлетворяющих условию.';
    }
} else {
    echo 'Строка должна заканчиваться точкой.';
}
?>

</body>
</html>
