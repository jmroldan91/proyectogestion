<?php
function autocargador($class) {
    $module = substr($class, 0, strlen($class)-9);
    $path = '../classes/' . $class . '.php';
    $path1 = './classes/' . $class . '.php';
    $path2 = './modules/' .  $module . '/' . $class . '.php';
    if(file_exists($path)){
        require_once $path;
    }else if(file_exists($path1)){
        require_once $path1;
    }else if(file_exists($path2)){
        require_once $path2;
    }
}   
spl_autoload_register('autocargador');
