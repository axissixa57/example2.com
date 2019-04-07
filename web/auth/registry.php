<?php
    include '../../library/core.php';
    access(['admin']);

    file_include('/library/Db.php');
    $error_login = false;
    $error_pass = false;
    $error = false;
    $login = '';
    $fio = '';
    $role = '';
    $db = new Db();
    if ($_POST) {
        if ($_POST['login'] && $_POST['password'] && 
            $_POST['repeat_password'] && $_POST['fio'] && 
            $_POST['role']) {

                $login = $_POST['login'];
                $password = $_POST['password'];
                $repeat_password = $_POST['repeat_password'];
                $fio = $_POST['fio'];
                $role = $_POST['role'];
                $db->setQuery("SELECT `id`, `login` FROM `users` 
                              WHERE `login` = '$login' LIMIT 1");
                if ($db->getNumRows()) {
                    $error_login = 'Пользователь существует';
                }
                if ($password != $repeat_password) {
                    $error_pass = 'Пароли не совпадают';
                }

                if (!$error && !$error_login && !$error_pass) {
                    $db->setQuery("INSERT INTO `users` (`login`, `password`, 
                                `fio`, `role`) VALUES ('$login', '$password', 
                                '$fio', '$role')");
                    $_SESSION['login'] = $login;
                    $_SESSION['fio'] = $fio;
                    $_SESSION['role'] = $role;
                    header('Location: ' . url('/web/admin'));
                    // Добавление в базу и редирект на домашнюю страницу
                }
                
        } else {
            $error = 'Указаны не все поля';
        }   

    }  
    $db->close();

    file_include('/layers/header.php', 'Добавление нового пользователя');
?>

<div class="content">
    <h1>Добавление нового пользователя</h1>
    <form id="form" class="form" action="<?=url('/web/auth/registry.php');?>" method="POST">
        <div class="form-block">
            <label for="login">Логин</label>
            <input class="input-text" value="<?= $login; ?>" id="login" type="text" name="login" placeholder="введите логин" required>
        <?php if($error_login): ?>
            <p style="width:100%; margin:0; color: #DD0000"><?= $error_login; ?></p>
        <?php endif; ?>
            
        </div>
        <div class="form-block">
            <label for="password">Пароль</label>
            <input class="input-text" id="password" type="password" name="password" placeholder="введите пароль" required>
            <?php if($error_pass): ?>
                <p style="width:100%; margin:0; color: #DD0000"><?= $error_pass; ?></p>
            <?php endif; ?>
        </div>
        <div class="form-block">
            <label for="repeat_password">Подтверждение пароля</label>
            <input class="input-text" id="repeat_password" type="password" name="repeat_password" placeholder="подтвердите пароль" required>
            <?php if($error_pass): ?>
                <p style="width:100%; margin:0; color: #DD0000"><?= $error_pass; ?></p>
            <?php endif; ?>
        </div>
        <div class="form-block">
            <label for="fio">ФИО</label>
            <input class="input-text" id="fio" value="<?= $fio; ?>" type="text" name="fio" placeholder="введите фио" required>
        </div>
        <div class="form-block">
            <label for="fio">Выберите роль</label>
            <select id="role" class="input-text" name="role">
                <option value="user" <?= ($role == 'user') ? 'selected' : ''; ?>>Пользователь</option>
                <option value="manager" <?= ($role == 'manager') ? 'selected' : ''; ?>>Менеджер</option>
            </select>
        </div>        
        <div class="right">
            <input id="sub" type="submit" class="submit" name="submit" value="войти">
        </div>
        
    </form>
    <?php if($error): ?>
        <p style="width:100%; margin:0; color: #DD0000"><?= $error; ?></p>
    <?php endif; ?>
</div>

<?php file_include('/layers/footer.php'); ?>