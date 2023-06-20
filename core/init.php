<?php

session_start();
//В суперглобальном массиве $GLOBALS создаём элемент 'config', являющийся массивом.
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'ooplogin'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expire' => 604800
    ),
    'session' => array(
        'session_name' => 'user'
    ),
);
// Функция запускается при попытке инициализации экземпляра класса.
//Функция будет пытаться подключить класс с именем, соответствующим значению 
//аргумента $class. Возможность автозагрузки классов основывается на допущении, что
//имя класса совпадает с именем файла, в котором этот класс находиться.
spl_autoload_register(function ($class) {
    require_once './classes/' . $class . '.php';
});
//К сожаению автододгрузчик функций не предусмотрен
require_once './functions/sanitize.php';
