<?php

require_once './core/init.php';
//Обращаемся посредством статического метода класса DB - getInstance() к экземпляру этого самого класса.
//Вызываем метод get().
$users = DB::getInstance()->get('users', array('username', '=', 'alex'));
if ($users->count()) {
    foreach ($users as $user) {
        echo $user->username;
    }
}
