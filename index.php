<?php
    // Начало php кода
    // Главная страница - example2.com/

    /*
     * Подключение файла с функциями, которые используются на этой странице
     * Так как файл находится в корне сайта, то выходить из текущей директории не требуется.
     * Мы заходим в папку library и обращаемся к файлу core.php
     * */
    include 'library/core.php';

    /*
     * file_include - функция для подключения файлов.
     * См описание в файле /library/core.php
     * Подключаем шапку сайта. Здесь будет идти код из файла /layers/header.php
     * Вторым параметром идет название страницы, которое будет выводится в теге <title>
     * */
    file_include('/layers/header.php', 'Главная страница');
    // Завершение php кода
?>
<!--Начало HTML кода-->
<div class="content"> <!--div контейнер для вывода контента сайта-->
    <h1>Главная страница</h1><!--Заголовок страницы-->
	Контент
</div>
<!--Завершение HTML кода-->
<!--Подключаем подвал сайта. Здесь будет идти код из файла /layers/footer.php-->
<?php file_include('/layers/footer.php'); ?>