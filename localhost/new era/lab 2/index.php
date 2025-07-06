<?php
require 'item.php';
$class = new HTMLPage('Главная');
$html = $class->write();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $class->title ?></title>
</head>

<body>
    <?= $html; ?>
</body>

</html>