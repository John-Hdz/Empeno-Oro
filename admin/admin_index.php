<?php


require_once 'model.php';
$web = new Model();

require_once (__DIR__ . '/views/header.php');
require_once (__DIR__.'/../visitante/index.php');
require_once (__DIR__ . '/views/footer.php');

?>