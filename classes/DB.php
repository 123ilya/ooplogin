<?php
class DB
{
    private static $_instance = null;
    private $_pdo;
    private $_query;
    private $_error = false;
    private $_results;
    private $_count = 0;
    private function __construct()
    {
        try {
            //В блоке try пытаемся создать экземпляр встроенного класса PDO. В касестве значений аргументов используем
            //статический метод get класса Config с соответствующей строкой.
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . '; dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
            echo 'Connected!';
        } catch (PDOException $e) {
            //Если соединение не удалось, то выводим сообщение об ошибке.
            die($e->getMessage());
        }
    }
    //Создаём статический метод getInstance(), который создаёт экземпляр класса DB и записывает его в 
    //статическое свойство  $_instance, если там ранее не был записан экземпляр другой экземпляр класса DB.
    //то есть экземпляр класса DB храниться в самом классе DB в статичном свойстве !!!
    //то есть получается какая то рекурсия-подобная ситуация. 
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
}
