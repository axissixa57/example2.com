<?php
include '../../library/core.php';
file_include('/library/Db.php');
if($_GET && $_GET['id'] && is_numeric($_GET['id'])) {
    $db = new Db();
    $db->setQuery("DELETE FROM `basket` WHERE `id_order` = " . $_GET['id']);
    $db->setQuery("DELETE FROM `orders` WHERE `id` = " . $_GET['id']);
    $db->close();
}
header('Location: ' . url('/web/orders/'));
?>