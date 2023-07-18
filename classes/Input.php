<?php

//Класс Input предназначен для манипуляций с данными форм.
//Т.е проверка наличия переданных данных в соответствующих суперглобальных массивах 
//и получение этих данных.
class Input
{
    //Метод exist(), в качестве аргумента принимает строку, характеризующую метод передачи формы на сервер.
    //(либо 'get' либо 'post') и в зависимости от переданного аргумента проверяет на пустоту 
    //соответствующий суперглобальный массив.
    public static function exists($type = 'post')
    {
        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;;
                break;
            default:
                return false;
                break;
        }
    }
    // Методм get() проверяет наличие в суперглобальных массивах $_POST и $_GET соответствующего элемента.
    // Если элемент найден, то возвращается его значение(содержание), в противном случае возвращается 
    //пустая строка ''
    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } else if (isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }
}
