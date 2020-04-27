<?php

require_once __DIR__ . '/Template.php';
require_once __DIR__ . '/Connection.php';

abstract class Controller
{
    protected $connection;

    public function __construct()
    {
        $this->connection = Connection::getConnection();
    }

    abstract public function run();

    protected function redirect($url)
    {
        header('Location: ' . $url);
        die;
    }
}
