<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $_GET['title'] ?></title>
    <link href="/css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <h1><?= $_GET['title'] ?></h1>
    <br>
    <h2> <?= $_GET['message'] ?> </h2>
    <hr>
    <a href="/login.html">Перейти к авторизации</a>
</body>
</html>