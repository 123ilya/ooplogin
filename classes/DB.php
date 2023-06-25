<?php

class DB
{
    private static object $_instance = null; // null обозначает, что значение переменной не заданно.
    private object $_pdo;
    private string $_query;
    private bool $_error = false;
    private $_results;
    private $_count = 0;
}
