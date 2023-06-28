<?php


require_once './core/init.php';
// $user = DB::getInstance()->get('users', array('username', '=', 'alex'));
$user = DB::getInstance()->query('SELECT * FROM users');

if (!$user->count()) {
    echo 'no user';
} else {
    echo $user->first()->username;
    // echo 'ok';
}
