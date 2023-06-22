<?php

require_once './core/init.php';

$user =  DB::getInstance()->get('users', array('username', '=', 'billy'));

if (!$user->count()) {
    echo "No user!";
} else {
    echo "OK!";
}
// var_dump($user->error());
// Почему то при допущении ошибки значение $_error не становиться true и следовательно error() не возвращает true!!!!!!
