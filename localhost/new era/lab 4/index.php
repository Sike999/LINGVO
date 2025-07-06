<link rel="icon" href="imgs/logo.png">
<link rel="stylesheet" href="style.css">
<?php
try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8;dbname=AJAX;', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo 'Ошибка' . $e->getMessage();
}

$stuff = $pdo->query('SELECT `Model`,`id` from `tabExtra`')->fetchAll();

if (!empty($stuff)) { ?>
    <form method='get' action="">
        <div class="container">
            <div class="big">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 20px;">
                    <?php foreach ($stuff as $item) { ?>
                        <div style="padding: 15px; text-align: center; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <span class="items">
                                <?php echo $item['Model']; ?>
                                <input class="ch" type="checkbox" data-model="<?= $item['Model'] ?>" value="<?= $item['id'] ?>" style="background-color: #362513;">
                            </span>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class='small'>
                <div>
                    <img src="imgs/logo.png" width="48px" height="48px">
                    <div id='place'></div>
                </div>
                <p id='price'></p>
            </div>
        </div>
        <button>Записаться</button>
    </form>
<?php } ?>

<script>
        document.addEventListener('DOMContentLoaded', function () {

        function Add() {
        event.preventDefault();
        var quan = event.target.closest('div').querySelector('span');
        var quantity = parseInt(quan.innerText);
        quan.innerText = " " + (quantity + 1) + " ";
    }

    function Subtract() {
        event.preventDefault();
        var quan = event.target.closest('div').querySelector('span');
        var quantity = parseInt(quan.innerText);
        if (quantity > 1) {
            quan.innerText = " " + (quantity - 1) + " ";
            event.target.disabled = false;
        }
        else {
            event.target.disabled = true;
        }
    }

    function CountByQuan() {
    var priceP = document.getElementById('price');
    var currentPrice = Number(priceP.innerText) || 0;
    var tempQuan = event.target.closest('div').querySelector('span');
    var quan = Number(tempQuan.innerText);
    var id = event.target.id.match(/\d+$/)[0];

    var ajax = new XMLHttpRequest();
    ajax.open('POST', 'getQuantity.php', true);
    ajax.setRequestHeader('Content-Type', 'application/json');

    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var response = JSON.parse(ajax.responseText);
            var button = document.getElementById('countPlus-' + id);
            if (quan > response.maxQuantity) {
                alert('Такое количество отсутствует на складе.');
                button.disabled = true;
                tempQuan.innerText = ' ' + response.maxQuantity + ' ';
                quan = response.maxQuantity;

            }
            else {
                button.disabled = false;
            }

            var productDiv = document.getElementById('product-' + id);
            var oldQuantity = parseInt(productDiv.getAttribute('data-quantity') || "1");
            var pricePerItem = response.price;

            var oldTotal = oldQuantity * pricePerItem;
            var newTotal = quan * pricePerItem;

            priceP.innerText = currentPrice - oldTotal + newTotal;
            productDiv.setAttribute('data-quantity', quan);
        }
    };

    var data = JSON.stringify({ id: id });
    ajax.send(data);
}


        var checkboxes = document.querySelectorAll('.ch');
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    var model = this.getAttribute('data-model');
                    var id = this.value;
                    addToSmallBlock(id,false);
                }
                else if(this.checked == false) {
                    var model = this.getAttribute('data-model');
                    var id = this.value;
                    var div = document.getElementById('product-'+id);
                    var delQuan = div.querySelector('span').innerText;
                    div.remove();
                    addToSmallBlock(id,true,delQuan);
                }
            });
        });

        function addToSmallBlock(id, isRemoved, delQuan) {
            var ajax = new XMLHttpRequest();
            ajax.open('POST', 'getProduct.php', true);
            ajax.setRequestHeader('Content-Type', 'application/json');

            ajax.onreadystatechange = function () {
                if (ajax.readyState === 4) {
                    if (ajax.status === 200) {

                        var response = JSON.parse(ajax.responseText);
                        var priceP = document.getElementById('price');
                        let currentPrice = Number(priceP.innerText);

                        if (response.model) {
                            if (!isRemoved) {
                            var place = document.getElementById('place');
                            var newItem = document.createElement('div');

                            newItem.innerHTML = response.model + response.basicQuantity;
                            newItem.setAttribute('id', 'product-' + id);
                            place.appendChild(newItem);

                            if(response.price){
                                currentPrice += Number(response.price);
                                priceP.innerText = currentPrice;
                            }
                            
                            document.getElementById(response.idMinus).addEventListener('click', Subtract);
                            document.getElementById(response.idPlus).addEventListener('click', Add);
                            document.getElementById(response.idMinus).addEventListener('click', CountByQuan);
                            document.getElementById(response.idPlus).addEventListener('click', CountByQuan);
                            }
                            else {
                                currentPrice -= Number(response.price) * Number(delQuan);
                                priceP.innerText = currentPrice;
                            }
                            if (priceP.innerText == 0) {
                                priceP.innerText = '';
                            }
                        } else {
                            alert('Ошибка: ', response.error);
                        }
                    } else {
                        alert('Ошибка запроса: ', ajax.status);
                    }
                }
            };

            var data = JSON.stringify({ id: id });
            ajax.send(data);
        }
    });// при отмене selecta вычитается просто price, а не price * quantity
</script>
