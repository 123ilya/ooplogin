<?php
class DB
{
    private static  $_instance = null;
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
            // echo 'Connected!';
        } catch (PDOException $e) {
            //Если соединение не удалось, то выводим сообщение об ошибке.
            die($e->getMessage());
        }
    }
    //Создаём статический метод getInstance(), который создаёт экземпляр класса DB и записывает его в 
    //статическое свойство  $_instance, если там ранее не был записан экземпляр другой экземпляр класса DB.
    //то есть экземпляр класса DB храниться в самом классе DB в статичном свойстве !!!
    //то есть получается какая то рекурсия-подобная ситуация. 
    public static function getInstance(): DB
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    // Метод подготавливает и выполняет sql запрос
    public function query(string $sql, array $params = array())
    {
        $this->_error = false;
        //Результат выполнения метода prepare экземпляра класса PDO записывается в переменную _query (в случае успешного выполнения метода)
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            //Если массив $params соддержит хотя бы один элемени, то 
            if (count($params)) {
                //пробегаем по этому массиву.
                foreach ($params as $param) {
                    //И для каждого элемента массива связываем значение.
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            // Пытаемся выполнить запрос
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }
    //  Функция формирует и обрабатывает запрос согласно параметрам, указанным при вызове функции.
    //Где первый аргумент - SQL  команда. Второй аргумент - имя таблицы. Третий аргумент - массив, состоящий из трёх строк
    //- имя столбца, оператор, значение столбца.

    public function action(string $action, string $table, array $where = array())
    {
        //Проверяем количество элементов массива (правильно ли передан третий параметр), и в случае успеха выполняем следующее:
        if (count($where) == 3) {
            //Записываем в переменную $operators массив с допустимыми операторами.
            $operators = array('=', '>', '<', '>=', '<=');
            // Переменной $field присваиваем значение первого элемента массива (третьего аргумента метода)
            $field = $where[0];
            // Переменной operator присваиваем значение второго элемента массива.
            $operator = $where[1];
            // Переменной $value присваиваем значение третьено элемента массива.
            $value = $where[2];
            //Проверяем присутствует ли переданный оператор в массиве разрешенных и если да то
            if (in_array($operator, $operators)) {
                //формируем запрос
                $sql = "$action  FROM  $table WHERE $field $operator ?";
                //Выполняем запрос. В случае успеха возвращаем объект (экземпляр класса DB)
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        //Иначе возвращаем false
        return false;
    }

    //Методы get и delete представляют собой вариации использования метода action но уже с 
    // соответствующей sql командой.

    public function get($table, $where = array())
    {
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where = array())
    {
        return $this->action('DELETE', $table, $where);
    }

    // Метод всего лишь возвращает значение приватного свойства _error
    public function error(): bool
    {
        return $this->_error;
    }

    public function count()  
    {
        return $this->_count;
    }
}
