<?php

session_start();//Стартуем сессию
//В суперглобальном массиве $GLOBALS создаём элемент 'config', являющийся многомерным массивом.
//В массиве 'config' храняться параметры для подключения к базе данных
$GLOBALS['config'] = array(
    'mysql' => array(//Данные для подключения к базе данных
        'host' => '127.0.0.1',//Хост
        'username' => 'root',//Пользователь
        'password' => '',//Пароль (в данном случае пустой)
        'db' => 'ooplogin'//Название базы данных
    ),
    'remember' => array(//Настройки куки
        'cookie_name' => 'hash',
        'cookie_expire' => 604800
    ),
    'session' => array(//Настройки куки
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
//поэтому  одключаем функцию вручную
require_once './functions/sanitize.php';
