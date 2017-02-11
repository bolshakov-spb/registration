<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/DBConfig.php');

// получение всех записей с указанным адресом почты
$stmt = $pdo->prepare('select * from user where email=?');
$stmt->execute([$_POST['email']]);
$user = $stmt->fetchAll();

if (!count($user) || $user[0]['passwd'] != $user[0]['salt'].md5($user[0]['salt'].$_POST['password'])) {
    // если запрос не вернул ни одной строки или указанный пароль не прошел проверку
    // то клиенту отправляется соответствующее сообщение
    echo 'Неверная комбинация email + пароль!';
} else {
    // в противном случае отправляется сообщение об успешной авторизации
    echo 'Вы успешно авторизировались!';
}
