<?php
header('HTTP/1.0 404 Not Found');
header('Status: 404 Not Found');
include '../../library/core.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Not Found</title>
    <style type="text/css">
        body,html{
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }
        body{
            background-color: #212121;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #eee;
            font-family: Verdana;
            font-size: 22px;
            padding: 15px;
            box-sizing: border-box;
        }

        .imgstl{
            width: 100%;
            max-width: 800px;
            height: auto;
        }

        .content{
            position: relative;
            display: inline-block;
        }

        .content span{
            top: 15px;
            left: 15px;
            position: absolute;
        }

    </style>
</head>
<body>
<div class="content">
    <h2>Это не та страница которую вы ищите!</h2>
    <img class="imgstl" src="<?=url('/assets/img/pagenotfound.gif');?>">
</div>
<h2>404 Страница не существует!</h2>
</body>
</html>
