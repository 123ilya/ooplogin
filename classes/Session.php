<?php

class Session
{
    //Метод exists(() проверяет наличие данного свойства в суперглобальном массиве $_SESSION 
    //по ключу,переданному в качестве аргумента
    public static function exists($name):bool
    {
        return (isset($_SESSION[$name])) ? true : false;
    }
    // Метод put() помещает свойство в суперглобальный массив $_SESSION
    public static function put($name, $value)
    {
        return $_SESSION[$name] = $value;
    }
    // Метод get() возвращает значение свойства, находящегося в суперглобальном массиве $_SESSION
    // пр ключу, переданному в качестве аргумента.
    public static function get($name)
    {
        return $_SESSION[$name];
    }
    // Метод delete() удаляет соответствующее свойство из массива $_SESSION
    public static function delete($name)
    {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }
    // Метод flash(). В случае, если искомое свойство есть в массиве $_SESSION, то оно из массива удаляется
    // и метод возвращает значение этого свойства.
    //Если свойства с таким ключём нет в $_SESSION, то оно дабавляется в этот массив.
    public static function flash($name, $string = '')
    {
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
    }
}
