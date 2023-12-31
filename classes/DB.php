<?php

class DB
{
    private static  $_instance = null;//Статическое свойство, предназначенное для записи экземпляра класса DB.
    private $_pdo; //Экземпляр класса PDO
    private $_query;//Строка sql запроса
    private $_error = false;
    private $_results;
    private $_count = 0;

    private function __construct()
    {
        try {
            //Записываем в свойство _pdo экземпляр класса PDO. Процедура проходит в блоке try catch.
            //Так как необходимо в случае неудачи отловить ошибку.
            $this->_pdo = new PDO(
                'mysql:host=' . Config::get('mysql/host') .
                    ';dbname=' . Config::get('mysql/db'),
                Config::get('mysql/username'),
                Config::get('mysql/password')
            );
            // echo "Connected";
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    //-----------------------------------------------------------------------------------------------------
    //Статический метод, создаёт экземпляр класса DB,записывает его в статическое свойство $_instance
    //и возвращает его. Если экземпляр класса уже создан и записан в $_instance, то тогда метод просто
    //возвращает ранее созданный экземпляр класса и не создаёт новый.
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    //--------------------------------------------------------------------------------------------------------
    //Метод, формирующий строку SQL запроса.
    public function query($sql, $params = array())
    {
        $this->_error = false;
        //Подготавливаем строку запроса и записываем её в свойство _query
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            //Если параметры были переданы, то связываем строку запроса с этими параметрами
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            try {
                //Пытаемся выполнить запрос
                if ($this->_query->execute()) {
                    //Результат получаем в виде объекта
                    $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                    //В свойство _count записываем количество вернувшихся строк.
                    $this->_count = $this->_query->rowCount();
                }
            } catch (PDOException $e) {
                $this->_error = true;
                echo $e->getMessage();
            }
        }
        return $this;
    }
    //-----------------------------------------------------------------------------------------------------------
    public function action($action, $table, $where = array())
    {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field}{$operator} ?";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }
    //---------------------------------------------------------------------------------------------------------
    public function get($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }
    //-------------------------------------------------------------------------------------------------------
    public function delete($table, $where)
    {
        return $this->action('DELETE ', $table, $where);
    }
    //-----------------------------------------------------------------------------------------------
    public function insert($table, $fields = array())
    {
        if (count($fields)) {
            $keys = array_keys($fields);
            $values = '';
            $x = 1;
            foreach ($fields as $field) {
                $values .= '?';
                if ($x < count($fields)) {
                    $values .= ', ';
                    $x++;
                }
            }
            // die($values);

            $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES({$values})";
            // echo $sql;
            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }
    //----------------------------------------------------------------------------------------------
    public function update($table, $id, $fields)
    {
        $set = '';
        $x = 1;
        foreach ($fields as $key => $value) {
            $set .= "{$key} = ?";
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    //-----------------------------------------------------------------------------------------------
    public function results()
    {
        return $this->_results;
    }
    //--------------------------------------------------------------------------------------------------
    public function first()
    {
        return $this->results()[0];
    }
    //-----------------------------------------------------------------------------------------------
    public function error()
    {
        return $this->_error;
    }
    //--------------------------------------------------------------------------------------
    public function count()
    {
        return $this->_count;
    }
    //---------------------------------------------------------------------------------------

}
