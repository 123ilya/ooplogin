<?php


require_once './core/init.php';
$user = DB::getInstance()->get('users', array('username', '=', 'alexZ'));
if (!$user->count()) {
    echo 'no user';
} else {
    echo 'ok';
}
