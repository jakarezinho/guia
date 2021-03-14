<?php 
use \guia\Autoloader;
use \guia\Hastag;
use \guia\App;

 require 'class/Autoloader.php';
Autoloader::register(); 
$db= App::getDatabase();
////
$s= new Hastag($db);

$hastag= isset($_POST) ? $_POST['search']:"";
$result= $s->cherche($hastag,'foto.php',$limte=20);
?>
   




