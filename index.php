<?php
    require_once 'classes/AutoLoad.php';
    Settings::loadSettings('./settings.json');
    $handler = new Controller();
    $handler->load();
?>
