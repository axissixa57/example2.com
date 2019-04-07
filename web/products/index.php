<?php
    include '../../library/core.php';
    file_include('/library/Db.php');
    $db = new Db(); 
    $db->setQuery('SELECT * FROM `products`');
    $products = array(); 
    if ($db->getNumRows()) { 
        $products = $db->getObject();
    }
    $db->close();

    file_include('/layers/header.php', 'Каталог товаров');
?>

<div class="content">
    <h1>Каталог товаров</h1>

	<?php if($products): ?>

	<table class="simple-table">
		<tr>
			<th>Код</th>
			<th>Наименование</th>
			<th>Модель</th>
            <th>Цена</th>
			<th>Изображение</th>
		</tr>
		<?php foreach ($products as $product): ?>
			<tr>
				<td>
                    <!--Ссылка на карточку товара.
                    Для каждого товара прописывается параметр id, который передается
                    через браузерную строку. В файле /web/products/show.php он попадет
                    в глобальный массив $_GET-->
                    <?=$product->id; ?>
				</td>
				<td>
                    <a href="<?=(url('/web/products/show.php?id=') . $product->id);?>">
                        <?=$product->name; ?> 
                    </a>
                </td>

				<td> <?=$product->model; ?> </td>

                <td> <?=$product->price; ?> </td>
                
                <td align="center"> 
                    <img width="50px" height="auto" src="<?= url('/assets/img/products/') . $product->image;?>">
                </td>
			</tr>
		<?php endforeach; ?>
	</table>
    <?php else: ?>
        <p>Товары не найдены</p>
	<?php endif; ?>
</div>

<?php file_include('/layers/footer.php'); ?>

<script type="text/javascript">
    var str = '<?php echo json_encode($products); ?>'; // переводим объект (stdClass) в json формат
    str = JSON.parse(str); // из json в js
    console.log(str);
</script>