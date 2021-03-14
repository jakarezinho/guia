<?php
use \guia\Autoloader;
use \guia\Mobile_Detect;
use \guia\Divers;
use \guia\Hastag;
use \guia\App;

require 'class/Autoloader.php'; 
Autoloader::register(); 
$db= App::getDatabase();
////
$pages= new Divers();
if( isset($_GET['lat']) && isset($_GET['lng'])) {
$lat = $_GET['lat'];
$lng = $_GET['lng'];

///// por perto
$radius =1;
$limite= 10;
 $array= $pages->porperto($db,$lat ,$lng ,$radius,$limite)->fetchAll(PDO::FETCH_ASSOC);
					echo json_encode($array);
}else{echo 'error';}
 ?>
