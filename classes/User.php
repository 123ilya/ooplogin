<?php

class User
{
    private $_db;
    private $_data;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('There was a problem kreating account.');
        }
    }

    public function find($user = null)
    { //Если аргумент был передан, то
        if ($user) {
            //В зависимости от переданного аргумента (либо буквенная строка либо цифровая строка) 
            //переменной $field присваивается значение либо 'id' либо 'username'
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('users', array($field, '=', $user));
            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }
    //-----------------------------------------------------------------------------------------------------
    public function login($username = null, $password = null)
    {
        $user = $this->find($username); //В случае, если пользователь найден, то переменной user
        //присваивается значение true, иначе false
        //Если $user = true, то
        if ($user) {
            //Пароль из БД и пароль, возвращаемый методом Hash::make() не совпадают!!!
            //Необходимо проверить, как формируются пароли!!!
            if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
                // echo 'Ok!';
                return true; // Проверка!!!!!!!!!!!!!! Удалить если не сработает!
            }
        }

        return false;
    }
    //-------------------------------------------------------------------------------------------------------
    private function data()
    {
        return $this->_data;
    }
}
