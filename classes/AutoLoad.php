<?php
function autocargador($class) {
    $path = '../classes/' . $class . '.php';
    if(file_exists($path)){
        require_once $path;
    }
}   
spl_autoload_register('autocargador');
