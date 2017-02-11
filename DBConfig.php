<?php
// установка utf-8 в качестве кодировки для скриптов
ini_set("default_charset", 'utf-8');


$dbName = 'cp593016_main';      // имя БД
$dbHost = 'localhost';          // адрес хоста
$dbUser = 'cp593016_root';      // имя пользователя
$dbPassword = 'K4BzPgbmT6d-';   // пароль

// строка подключения к бд
$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8";

// дополнительные опции подключения
$opt = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

// инициализация объекта для работы с базой
try {
    $pdo = new PDO($dsn, $dbUser, $dbPassword, $opt);
} catch (PDOException $e) {
    echo('Подключение не удалось: ' . $e->getMessage());
}