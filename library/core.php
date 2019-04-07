<?php
session_start();//Запуск встроенной в php функции для работы с сессиями на всех страницах

/* Функция отладки, использоуется только просмотра содержимого массива и объектов в читаемом виде*/
function dump($var) {
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}

/*Функция определяет субдомен и добавляет его во все пути*/
function subdomain() { // костыль для компьютеров рипк
    $root = $_SERVER['DOCUMENT_ROOT'];//корень сайта (W:/domains/example2.com)
    $subdomain = dirname(__DIR__);// текущее расположение файла core.php (W:\domains\library\)
    $subdomain = str_replace('\\', '/', $subdomain);// меняем обратные слеши на прямые (W:/domains/library/core.php)
    $subdomain = str_replace($root, '', $subdomain);/*Вырезаем из текущего
    расположения файла путь до корня. Оставшаяся часть является субдоменом (library/core.php)*/
    return $subdomain; // выводим результат из функции
}

/*Подключение файлов php к проекту с учетом субдомена $title выводится в шапке сайта*/
function file_include($path, $title = '') {
    include_once $_SERVER['DOCUMENT_ROOT'] . subdomain() . $path; // W:/domains/example2.com +  + /library/Db.php
 }

/*Генерация абсолютных адресов для сайта с учетом субдомена */
function url($path) {
	$url = 'http://' . $_SERVER["HTTP_HOST"];
	return $url . subdomain() . $path;
}

/*Функция для контроля доступа к страницам сайта
принимает параметр массив типов пользователей,
которым доступна данная страница.
Если параметр роль не указан, то проверяет только наличие авторизации
Например access(['admin', 'manager']) закроет страницу для все пользователей,
кроме админа и менеджеров. Остальным будет выводится 403 ошибка. Роль хранится в базе данных
*/
function access($roles = array())
{
    if ($_SESSION && $_SESSION['login']) {// проверяем авторизацию
        if ($roles) {// если передан параметр роль
            foreach ($roles as $role) {// проходим в цикле массив ролей
                if ($_SESSION['role'] && $_SESSION['role'] == $role) {
                    /*если роль в сессии совпадает с одним из значений массива роль, то выходим из функции, редирект не срабатывает и пользователь попадает на страницу. У него есть права на это*/
                    return;
                }
            }
            /*Если роль в сессии не совпадает ни с одним из значений в массиве ролей, значит у пользователя нет прав для просмотра текущей страницы переводим на страницу 403 Нет доступа*/
            header('Location: ' . url('/web/errors/403.php'));
        }
        /*Если массив ролей не указан, но логин в сессии указан, то пользователь существует значит его можно пустить на страницу, поэтому выходим из функции с помощью return, чтобы не отработал редирект ниже*/
        return;
    }
    // если сессия пустая и нас нигде не выбросило на предыдущих этапах, то пользователь не авторизован, значит переводим на страницу авторизации
    header('Location: ' . url('/web/auth/login.php'));
}
