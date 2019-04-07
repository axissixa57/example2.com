<!DOCTYPE html>
<html>
     <head>
           <title><? echo $title; ?></title>
           <meta charset="utf-8">
           <meta name="viewport" content="width=device-width, initial-scale=1.0">
           <link rel="stylesheet" href="<?=url('/assets/css/style.css'); ?>">
     </head>
     <body>
            <div class="page">
                <header>
                    <nav> 
                        <div>
                            <a href="<?=url('/'); ?>">Главная</a>
                            <a href="<?=url('/contact.php'); ?>">Контакты</a>
                            <a href="<?=url('/web/products/'); ?>">Товары</a>

                            <?php if(isset($_SESSION['basket']) && $_SESSION['basket']) : ?>
                                <a href="<?=url('/web/orders/add.php'); ?>">Корзина(<?= count($_SESSION['basket']); ?>)</a>
                            <?php else: ?>
                                <a href="<?=url('/web/orders/add.php'); ?>">Корзина(0)</a>
                            <?php endif; ?>
                        </div>

                        <div>
                            <?php if ($_SESSION && $_SESSION['login']):  ?>
                                <a href="<?=url('/web/admin'); ?>"><?= $_SESSION['fio'] ?></a>

                                <?php if ($_SESSION['role'] == 'admin'):  ?>
                                    <a href="<?=url('/web/auth/registry.php'); ?>">Добавить пользователя</a>
                                <?php endif;?>

                                <a href="<?=url('/web/auth/logout.php'); ?>">Выйти</a>
                            <?php else:?>
                                <a href="<?=url('/web/auth/login.php'); ?>">Войти</a>
                            <?php endif;?>
                        </div>

                    </nav>
                </header>
                <div class="wrapper">
                    <div class="left-bar">Левый блок</div>