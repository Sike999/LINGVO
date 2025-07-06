<?php
try{
    $pdo = new PDO("mysql:host=localhost;charset=utf8;dbname=AJAX;", "root", '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
}
catch(PDOException $e){
    echo 'Ошибка:' . $e->getMessage();
}
$material = $pdo->query("SELECT DISTINCT `Вид укрывного материала` FROM `tab`")->fetchAll();
$density = $pdo->query("SELECT `Плотность/толщина` FROM `tab`")->fetchAll();
?>
<form action='' method='get'>
<select name='material' id='material'>
    <?php
    foreach($material as $mat){
        ?>
        <option value="<?= $mat['Вид укрывного материала'] ?>" <?php if(!empty($_GET['material']) && $_GET['material'] == $mat['Вид укрывного материала']) {echo "selected";}?>><?=$mat['Вид укрывного материала']?></option>
        <?php
    }
    ?>
</select>
<select name ='density' id='density'>
    <?php
    foreach($density as $mat){
        ?>
        <option value="<?=$mat['Плотность/толщина']?>"<?php if(!empty($_GET['density']) && $_GET['density'] == $mat['Плотность/толщина']) {echo "selected";}?>><?=$mat['Плотность/толщина']?></option>
        <?php
    }
    ?>
</select>
<input type="text" placeholder="Количество в метрах" name='quantity' id='quantity' value="<?=isset($_GET['quantity']) ? trim($_GET['quantity']) : '';?>">
</form>
<p id='price'></p>
<p id='resp'></p>
<script>
    var matS = document.getElementById('material');
    var denS = document.getElementById('density');
    var quantityInput = document.getElementById('quantity');

    function update() {
        var material = matS.value;

        var ajax1 = new XMLHttpRequest();
        ajax1.open('GET', 'getDens.php?material=' + material, true);
        ajax1.onreadystatechange = function () {
            if (ajax1.readyState === 4 && ajax1.status === 200) {
                var data1 = JSON.parse(ajax1.responseText);
                denS.innerHTML = '';

                data1.ofd.forEach(function (ofd, index) {
                    var newOption = document.createElement('option');
                    newOption.value = ofd;
                    newOption.textContent = ofd;
                    denS.appendChild(newOption);
                    if (index === 0) {
                        denS.value = ofd;
                    }
                });

                AJAX();
            }
        };
        ajax1.send();
    }

    function AJAX() {
        var material = matS.value;
        var density = denS.value;
        var quantity = quantityInput.value;

        

        var ajax = new XMLHttpRequest();
        ajax.open('GET', 'AJAX.php?material=' + material + '&density=' + density + '&quantity=' + quantity, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState === 4 && ajax.status === 200) {
                var data = JSON.parse(ajax.responseText);
                document.getElementById('price').innerText = data.price;
                document.getElementById('resp').innerText = data.responce;
            }
        };
        ajax.send();
    }

    matS.addEventListener('change', update);
    denS.addEventListener('change', AJAX);
    quantityInput.addEventListener('input', AJAX);

    update();
</script>