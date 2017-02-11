<?php

// класс предотвращения повторных регистраций с одного и того же
// ip адреса за определенный промежуток времени (в данном случае 7 дней)
class IPFilter
{
    private $ip;
    private $dbh;

    // конструктор
    public function __construct($pdo)
    {
        // получение адреса
        $this->ip = $_SERVER['REMOTE_ADDR'];
        // инициализация объекта для соединения с БД
        $this->dbh = $pdo;
    }

    // метод для проверки возможности регистрации
    public function check($add_to_base)
    {
        // получение записей, в ктотрых фигурирует текущий ip
        $stmt = $this->dbh->prepare('select * from ip_list where ip=?');
        $stmt->execute([$this->ip]);
        $reg_record = $stmt->fetchAll();

        // если есть хоть одна такая запись и она добавлена менее 7 дней назад, то метод возвращает true
        if (count($reg_record)
            && strtotime($reg_record[0]['last_reg']) > strtotime('-7 days', strtotime(date('Y-m-d H:i:s')))) {
            return true;
        }
        // если параметр $add_to_base равен true
        if ($add_to_base) {
            // удалаются все записи с текущим ip адресом
            $stmt = $this->dbh->prepare('delete from ip_list where ip=?');
            $stmt->execute([$this->ip]);
            // добавляется новая запись
            $stmt = $this->dbh->prepare('insert into ip_list (ip, last_reg) values (?, NOW())');
            $stmt->execute([$this->ip]);
        }
        return false;
    }
}