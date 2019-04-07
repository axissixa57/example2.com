<?php
    include '../../library/core.php';
    file_include('/library/Db.php');

    $login = '';
    $error = '';

    if ($_POST) {
        $db = new Db(); 
        $login = $_POST['login']; // при нажатии на submit данные из инпута повятся в $_POST['login']
        $password = $_POST['password'];

        $db->setQuery("SELECT `id`, `login`, `fio`, `role` FROM `users` WHERE `login` = '$login' AND `password` = '$password' LIMIT 1");
        if ($db->getNumRows()) { // если нашли
            $user = $db->getObject(1); // получаем объект найденного пользователя
            $_SESSION['id'] = $user->id;
            $_SESSION['login'] = $user->login;
            $_SESSION['fio'] = $user->fio;
            $_SESSION['role'] = $user->role;
            header('Location: ' . url('/web/admin')); // перенаправляем на домашнюю страницу
        } else {
            $error = 'Пользователь не найден. Возможно вы неправильно ввели логин/пароль'; /*Если пользователь не найден сохраняем ошибку в переменную*/
        }
        $db->close();// закрываем соединение сс базой
    }
    
    file_include('/layers/header.php', 'Вход в приложение'); // Вторым параметром идет название страницы, которое будет выводится в теге <title>
?>

<div class="content">
    <h1>Вход в приложение</h1>
    <form class="form" action="<?=url('/web/auth/login.php');?>" method="POST">
        <div class="form-block">
            <label for="login">Логин</label>
            <input class="input-text" value="<?=$login?>" id="login" type="text" name="login" placeholder="введите логин" required>
        </div>
        <div class="form-block">
            <label for="password">Пароль</label>
            <input class="input-text" id="password" type="password" name="password" placeholder="введите пароль" required>
        </div>
        <div class="right">
            <input type="submit" class="submit" name="submit" value="войти">
        </div>
        <?php if($error): ?>
            <p style="color: #DD0000"><?= $error; ?></p>
        <?php endif; ?>
    </form>
</div>

<?php file_include('/layers/footer.php'); ?>