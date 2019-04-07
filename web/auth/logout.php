<?php
    include '../../library/core.php';
    unset($_SESSION['id']);// Уничтожение переменной id в массиве $_SESSION; unset() - удаляет ключ и его значение. unset() удаляет перечисленные переменные.
    unset($_SESSION['login']);
    unset($_SESSION['fio']);
    unset($_SESSION['role']);

    header('Location: ' . url('/')); // возвращает на главную страницу (index.php в корне)