<?php


require_once './core/init.php';

if (Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '</p>';
}
echo '<p>'.Session::get(Config::get('session/session_name')).'</p>';
