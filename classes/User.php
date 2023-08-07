<?php

class User
{
    private $_db;
    private $_data;
    private $_sessionName;
    private $_isLoggedIn;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    //process ligout...
                }
            }
        } else {
            $this->find($user);
        }
    }
    //-------------------------------------------------------------------------------------------
    public function create($fields = array())
    {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('There was a problem kreating account.');
        }
    }
    //-------------------------------------------------------------------------------------------
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
                Session::put($this->_sessionName, $this->data()->id);
                // echo 'Ok!';  
                return true; // Проверка!!!!!!!!!!!!!! Удалить если не сработает!
            }
        }

        return false;
    }
    //--------------------------------------------------------------------------------------------------
    public function logout()
    {
        Session::delete($this->_sessionName);
    }
    //-------------------------------------------------------------------------------------------------------
    public function data()
    {
        return $this->_data;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}
