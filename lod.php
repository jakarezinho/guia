<?php
use \guia\Autoloader;
use \guia\Mobile_Detect;
use \guia\Base;
use \guia\Hastag;
use \guia\App;
require 'class/Autoloader.php'; 
Autoloader::register(); 

$db= App::getDatabase();
$t= new Base($db);

var_dump($t->hello()->fetchAll());
?>