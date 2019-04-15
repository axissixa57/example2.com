<?php
header('HTTP/1.0 403 Forbidden');
header('Status: 403 Forbidden');
include '../../library/core.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Forbidden</title>
    <style type="text/css">
        body,
        html {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }

        body {
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

        .content {
            position: relative;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="content">
        <h1>403 Доступ запрещен</h1>
    </div>
</body>

</html>