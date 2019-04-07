<?php
    include '../../library/core.php';
    file_include('/library/Db.php');
    if ($_GET && $_GET['id']) {// Проверяем содержимое массива $_GET и ячейки $_GET['id']
        $id = $_GET['id'];
        $db = new Db(); 

        $db->setQuery("SELECT * FROM `products` WHERE `id` = $id LIMIT 1");
        if ($db->getNumRows()) {
            $product = $db->getObject(1);
            //Формируем название страницы на основе информации из базы
            $title = $product->name . ' ' . $product->model . ' по низким ценам в Минске';
        } else {
            // Если id не передан, то перенаправлям на страницу ошибок
            header('Location: ' . url('/web/errors/404.php'));
        }
    } else {
        // Если id не передан, то перенаправлям на страницу ошибок
        header('Location: ' . url('/web/errors/404.php'));
    }
    $db->close(); // Закрываем соединение с базой, т.к. на этой странице запросов больше не будет

    file_include('/layers/header.php', $title);
?>

<div class="content">
    <h1><?= $product->name . ' ' . $product->model; ?></h1>
	<?php if($product): ?>
        <img width="250px" height="auto" src="<?= url('/assets/img/products/') . $product->image;?>">
		<p>Наименование - <b><?= $product->name?></b></p>
		<p>Модель - <b><?= $product->model?></b></p>
        <p>Цена - <b><?= $product->price?></b></p>
        <p><a href="<?= url("/web/basket/add.php?id=$id"); ?>">Добавить товар в корзину</a></p>
	<?php endif; ?>
	<p>
		<a href="<?= url('/web/products/');?>">Назад к каталогу товаров</a>
	</p>	
</div>

<?php file_include('/layers/footer.php'); ?>