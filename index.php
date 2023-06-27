<?php


require_once './core/init.php';

$user = DB::getInstance()->query("SELECT username FROM users WHERE username = ?", array('alex'));
if ($user->error()) {
    echo 'No user!';
} else {
    echo 'Ok!';
}
$user->showError();
// Почему то , если допустить оппечатку в sql запросе, то свойство $_error в экземпляре класса DB не становиться  true
// и не выводится сообщение 'No user!'