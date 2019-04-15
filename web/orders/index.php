<?php
include '../../library/core.php';
// ограничим доступ только для админа
access(['admin']);
file_include('/library/Db.php');
$db = new Db();
$order = '`o`.`date_order` DESC'; // сортировка по умолчанию
if ($_GET && $GET['sort']) { // проверяем наличие параметра sort
    $type = $_GET['type'] ? 'DESC' : 'ASC'; // если передан параметр type, то DESC иначе ASC
    switch ($_GET['sort']) { // проверяем переданный параметр
        case 'id':
            $order = "`o`.`id` $type"; // формируем кусок для будующего sql запроса 
            break;
        case 'date_order':
            $order = "`o`.`customer` $type";
            break;
        case 'customer':
            $order = "`o`.`customer` $type";
            break;
        case 'summary':
            $order = "`summary` $type";
            break;
        case 'quantity':
            $order = "`quantity $type";
            break;
    }
}
// Выбираем список заказов с подсчетом суммы каждого заказа и количества товаров в корзине
$db->setQuery("SELECT `o`.`id`, `o`.`customer`, `o`.`date_order`, `o`.`phone`,
                SUM(`p`.`price` * `b`.`quantity`) AS `summary`, COUNT(`o`.`id`) AS `quantity`
                FROM `orders` AS `o`
                JOIN `basket` AS `b` ON `o`.`id` = `b`.`id_order`
                JOIN `products` AS `p` ON `p`.`id` = `b`.`id_product`
                GROUP BY `o`.`id`
                ORDER BY $order"); // в переменную order передаем сортировку
$orders = array(); // если заказов нет то у нас будет пустой массив
if ($db->getNumRows()) { // есть ли запись?
    $orders = $db->getObject(); // сохраняем результат запроса в переменную
}
$db->close();

file_include('/layers/header.php', 'Список заказов');
?>

<div class="content">
    <h1>Список заказов</h1>
    <?php if($orders): ?>
        <table class="simple-table">
            <tr>
                <th>
                    <span class="td-sort">
                        Номер заказа
                        <span class="sort">
                            <!-- Тот же адрес, но с параметром sort. В sort передаём название столбца для сортировки -->
                            <!-- ??????sort=id???? -->
                            <a href="<?=url('/web/orders/'); ?>?sort=id">
                                <div class="arrow-top"></div>
                            </a>
                            <!-- Тот же адрес, но с параметрами sort и type. В type передаём направление сортировки, если type не указан, то по умолчанию ASC -->
                            <a href="<?=url('/web/orders/'); ?>?sort=id&type=desc">
                                <div class="arrow-bottom"></div>
                            </a>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="td-sort">
                        Дата заказа
                        <span class="sort">
                            <a href="<?=url('web/orders/'); ?>?sort=date_order">
                                <div class="arrow-top"></div>
                            </a>
                            <a href="<?=url('/web/orders/'); ?>?sort=date_order&type=desc">
                                <div class="arrow-bottom"></div>
                            </a>
                        </span>
                    </span>
                </th>
                <th>
                    <span class="td-sort">
                        Клиент
                        <span class="sort">
                            <a href="<?=url('web/orders/'); ?>?sort=customer">
                                <div class="arrow-top"></div>
                            </a>
                            <a href="<?= url('/web/orders/'); ?>?sort=customer&type=desc">
                                <div class="arrow-bottom"></div>
                            </a>
                        </span>
                    </span>
                </th>
                <th>Телефон</th>
                <th>
                    <span class="td-sort">
                        Сумма заказа
                        <span class="sort">
                            <a href="<?=url('web/orders/'); ?>?sort=summary">
                                <div class="arrow-top"></div>
                            </a>
                            <a href="<?=url('/web/orders/'); ?>?sort=summary&type=desc">
                                <div class="arrow-bottom"></div>
                            </a>
                        </span>
                    </span>
                </th>
                <th class="td-sort">
                    <span>Товаров в корзине</span>
                    <div class="sort">
                        <a href="<?=url('web/orders/'); ?>?sort=quantity">
                            <div class="arrow-top"></div>
                        </a>
                        <a href="<?=url('/web/orders/'); ?>?sort=quantity&type=desc">
                            <div class="arrow-bottom"></div>
                        </a>
                    </div>
                </th>
                <th></th>
            </tr>
            <?php foreach($orders as $order): ?>
                <tr>
                    <td><?= $order->id; ?></td>
                    <td><?= date('d.m.Y H:i:s', strtotime($order->date_order)); ?></td>
                    <td><?= $order->customer; ?></td>
                    <td><?= $order->phone; ?></td>
                    <td><?= $order->summary; ?></td>
                    <td><?= $order->quantity; ?></td>
                    <td>
                        <a href="<?=url('/web/orders/del.php?id=') . $order->id; ?>">
                            <img width="24px" height="auto" src="<?=url('/assets/img/del.png'); ?>" alt="Удалить заказ" title="Удалить заказ">
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>Заказы не найдены</p>
    <?php endif; ?>
</div>

<?php file_include('/layers/footer.php'); ?>