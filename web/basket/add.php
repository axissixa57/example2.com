<?php 
include '../../library/core.php';
file_include('/library/Db.php'); // функция для подключения файлов(объявление ф-ции находится в core.php)

if($_GET && $_GET['id']) {
    $id = $_GET['id'];
    $db = new Db();
    $db->setQuery("SELECT `id` FROM `products` WHERE `id` = $id LIMIT 1");
    if($db->getNumRows()) {
        if(!isset($_SESSION['basket']) || !$_SESSION['basket']) {
            $_SESSION['basket'] = array(); // если корзины сессии не существует, то создаём пустой массив
        }
        
        if(array_search($id, $_SESSION['basket']) === false) { // проверяем товар в корзине и если его там нет, то добавляем его в сессию
            $_SESSION['basket'][] = $id; // такая запись говорит, что мы запишем значение в индекс по порядку, т.е. если массив пустой - запишет в 0 индекс id и дальше в 1,2,3 индексы
        } // array_search() - oсуществляет поиск данного значения в массиве и возвращает ключ первого найденного элемента в случае удачи

        $db->close();
        header('Location: ' . url("/web/products/show.php?id=$id"));
    }

    $db->close();
    header('Location: ' . url("/web/errors/404.php")); // если товар в базе не найден или параметр ид не передан
}



