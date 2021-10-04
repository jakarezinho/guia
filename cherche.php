<?php 
use \guia\Autoloader;
use \guia\Hastag;
use \guia\App;

require 'vendor/autoload.php';
////
$s= new Hastag();

$hastag= isset($_POST) ? $_POST['search']:"";
$result= $s->cherche($hastag,'foto.php',$limte=20);
?>
   




