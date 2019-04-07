<?php 
include '../../library/core.php';
file_include('/library/Db.php');

$order_id = 0; // номер заказа по умолчанию, т.е. его нет
$db = new Db();

// если из формы пришли данные (проверка наличие всех данных из формы)
if($_POST && $_POST['fio'] && $_POST['phone'] && $_POST['products'] && $_POST['quantity']) {
    $date_order = date('Y-m-d H:i:s');
    $customer = $_POST['fio'];
    $phone = $_POST['phone'];
    $db->setQuery("INSERT INTO `orders` (`date_order`, `customer`, `phone`)
                    VALUES ('$date_order', '$customer', '$phone')");
    $order_id = $db->lastId(); // lastId() - получаем id последней добавленной записи

    if($order_id) { // если номер заказа известен
        // От формы мы получаем 2 массива (Массив с ид товаров и массив с количеством) количество элементов в этих массивах одинаково, поэтому прогоняем массив products через цикл. В $key будет попадать индекс, с помощью которого будет извлекаться количество товаров из $_POST['quantity']
        foreach($_POST['products'] as $key => $product_id) {
            $quantity = $_POST['quantity'][$key]; // определяем кол-во одинаковых товаров
            // добавляем товар в корзину к заказу указанному в переменной $order_id
            $db->setQuery("INSERT INTO `basket` (`id_order`, `id_product`, `quantity`)
                            VALUES ('$order_id', '$product_id', '$quantity')");
        }
        // удаляем содержимое сессии
        unset($_SESSION['basket']);
    }
}

// проверяем наличие данных в сессии
$basket = array(); // пустой массив basket, если данных в сессии нет

if(isset($_SESSION['basket']) && $_SESSION['basket']) {
    // если товары в сессии есть, то преобразуем массив в строку
    $ids_products = implode(',', $_SESSION['basket']); // implode() - превратит массив в строку с разделителем из первого параметра
    // используя конструкцию IN ищем в базе товары с id сохраненными в сессии
    $db->setQuery("SELECT `id`, `name`, `model`, `price`, FROM `products` WHERE `id` IN($ids_products)");

    if($db->getNumRows()) { // если товары найдены, то выводим на экран
        $basket = $db->getObject(); // результат запроса к базе сохраняем в переменной
    }

}

$db->close();
file_include('/layers/header.php', 'Оформление заказа');
?>

<div class="content">
    <?php if($basket) : ?>
    <!-- Если товары в сессии есть, то выводим товары в таблицу и готовим форму отправки заказа внутри таблицы -->
    <h1>Оформление заказа</h1>
    <!-- Обработчик находится в этом же файле -->
    <form class="form" action="<?=url('/web/orders/add.php');?>" method="POST">
        <div class="form-block">
            <label for="fio">ФИО</label>
            <input class="input-text" id="fio" type="text" name="fio" placeholder="введите фио" required>
        </div>
        <div class="form-block">
            <label for="phone">Мобильный телефон</label>
            <input class="input-text" id="phone" type="number" name="phone" placeholder="введите номер телефона" required>
        </div>
        <br>
        <p><b>Товары в корзине</b></p>
        <table id="basket" class="simple-table">
            <tr>
                <td>Наименование</td>
                <td>Модель</td>
                <td>Цена</td>
                <td>Количество</td>
                <td>Стоимость</td>
                <td>Удалить</td>
            </tr>
            <?php $cost = 0; // сумма заказ ?>
            <?php foreach($basket as $num => $bsk): ?>
                <tr>
                    <!-- products[] - означает массив инпутов которые хранятся в массиве $_POST['products'] type="hidden" - означает, что поле скрыто от пользователя, но при этом значение этого поля отправляется на сервер -->
                    <input type="hidden" name="products[]" value="<?= $bsk->id; ?>">
                    <td><?= $bsk->name; ?></td>
                    <td><?= $bsk->model; ?></td>
                    <td><?= $bsk->price; ?></td>
                    <td>
                        <!-- setQuantity - функция js описанная внизу файла для обработки изменений в таблице -->
                        <input onchange="setQuantity(this, <?= ++$num;?>)" type="number" name="quantity[]" min="1" value="1">
                    </td>
                    <td><?= $bsk->price; ?></td>
                    <td align="center">
                        <!-- Удаление товара из корзины через js функцию deleteRow описанную ниже -->
                        <input type="button" value="Удалить" onclick="deleteRow(this)">
                    </td>
                    <?php $cost += $bsk-> price; // увеличиваем сумму заказа ?>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4">Итого</td>
                <td id="cost_order"><?= $cost; // итоговая сумма заказа ?></td>
                <td></td>
            </tr>
        </table>
        <br>
        <div class="right">
            <input id="order" type="submit" class="submit" name="order" value="оформить">
        </div>
    </form>
    <?php elseif($order_id): // если заказ добавлен в базу и мы знаем номер заказа?>
        <p>Ваш заказ №<b><?= $order_id; ?></b> принят к обработке</p>
    <?php else: // если номера заказа нет и сессия пуста ?>
        <p><b>Корзина пуста</b></p>
    <?php endif; ?>
</div>
<script>
    function setQuantity(q, num) {
        let basket = document.getElementById('basket');
        let price = basket.rows[num].cells[2].innerText;
        let quantity = q.value;
        let cost = basket.rows[num].cells[4].innerText;
        let cost_order = document.getElementById('cost_order');
        cost_order.innerText = (cost_order.innerText - cost) + price * quantity;
        basket.rows[num].cells[4].innerText = price * quantity;
    }
    // Удаляем строку из таблицы и пересчитываем сумму заказа
    function deleteRow(r) {
        let basket = document.getElementById('basket');
        let r_num = r.parentNode.parentNode.rowIndex;
        basket.deleteRow(r_num);
        let cost = 0;
        for(let i = 1; i < (basket.rows.length - 1); i++) {
            cost += parseFloat(basket.rows[i].cells[4].innerText);
        }
        if(cost === 0) { // если в корзине не осталось товаров блокируем кнопку оформить
            document.getElementById('order').disabled = true;
            document.getElementById('order').style.backgroundColor = 'rgb(229, 156, 156)';
        }
        document.getElementById('cost_order').innerText = cost.toFixed(2);
    }
</script>

<?php file_include('/layers/footer.php'); ?>

