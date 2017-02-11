<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/DBConfig.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/IPFilter.php');

// функция для генерирования "соли"
function generateSalt($length = 8)
{
    $chars = 'abcdef0123456789';
    $numChars = strlen($chars);
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $string;
}

// Проверка ip на возможность регистрации
$filter = new IPFilter($pdo);
if ($filter->check(true)) {
    $url_data = array('title' => 'Ошибка',
        'message' => 'Вы не можете зарегистрироаться, так как с вашего ip-адреса уже была '
            . 'зарегистрирована учетная запись. Повотрная регистрация возможна cпустя 7 дней.');
    header('Location: /result.php?' . http_build_query($url_data));
    exit();
}

// получение количества записей с указанным email
$stmt = $pdo->prepare('select count(*) from user where email=?');
$stmt->execute([$_POST['inputemail']]);
$row_count = $stmt->fetchColumn();
// если есть хоть одна запись, то сообщаем пользователю, что данный email уже зарегистрирован
if ($row_count) {
    $url_data = array('title' => 'Ошибка',
        'message' => 'Пользователь с таким email уже зарегистрирован!');
    header('Location: /result.php?' . http_build_query($url_data));
    exit();
}

// генерирование "соли"
$salt = generateSalt();
// получение пароля (соль+md5(соль+пароль))
$password = $salt . md5($salt . $_POST['inputpassword']);
// добавление записи в БД
$stmt = $pdo->prepare('insert into user (email, passwd, salt) VALUES (:email, :passwd, :salt)');
$stmt->execute(array('email' => $_POST['inputemail'],
    'passwd' => $password,
    'salt' => $salt));

// получение идентификатора добавленной записи
$user_id = $pdo->lastInsertId();
$birthday = date('Y-m-d', strtotime($_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day']));
// добавление в БД информации о пользователе
$stmt = $pdo->prepare('insert into user_data (user_id, firstname, surname, birthday, gender, address)'
    . 'VALUES (:uid, :fname, :sname, :bd, :gender, :address)');
$stmt->execute(array('uid' => $user_id,
    'fname' => $_POST['firstname'],
    'sname' => $_POST['lastname'],
    'bd' => $birthday,
    'gender' => $_POST['genderRadios'],
    'address' => $_POST['address']));
// сообщаем пользователю об успешной регистрации
$url_data = array('title' => 'Поздравляем!',
    'message' => 'Вы успешно зарегистрировались!');
header('Location: /result.php?' . http_build_query($url_data));