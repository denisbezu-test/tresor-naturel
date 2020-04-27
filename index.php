<?php

session_start();

require_once 'views/templates/header.html';

require_once 'classes/Dispatcher.php';
require_once 'classes/Config.php';
require_once 'classes/Connection.php';


try {
    $dispatcher = new Dispatcher();
    $dispatcher->dispatch();
} catch (Exception $e) {
    var_dump($e->getMessage());
}

require_once 'views/templates/footer.html';