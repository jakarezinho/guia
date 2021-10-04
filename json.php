<?php

use \guia\Divers;


require 'vendor/autoload.php';
////
$pages= new Divers();
if( isset($_GET['lat']) && isset($_GET['lng'])) {
$lat = $_GET['lat'];
$lng = $_GET['lng'];

///// por perto
$radius =1;
$limite= 10;
 $array= $pages->porperto($lat ,$lng ,$radius,$limite);
					echo json_encode($array);
}else{echo 'error';}
 ?>
