<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
use \guia\Autoloader;
use \guia\Mobile_Detect;
use \guia\Divers;
use \guia\Hastag;
use \guia\App;

require 'class/Autoloader.php'; 
Autoloader::register(); 
$db= App::getDatabase();
$pages= new Divers($db);

$local_id =isset($_GET['id'])?$_GET['id']: false; 
$p =isset($_GET['p'])? $_GET['p']: 0;
/// lat lng 
if( isset($_GET['lat']) && isset($_GET['lng'])) {
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$radius =isset($_GET['radius'])?$_GET['radius']: 5; 
$limite= isset($_GET['limit'])? $_GET['limit']: 10;

$total = $pages->total_porperto($lat,$lng,$radius);
$nbdepages = $pages->nb_Pages($total,$limite);

$page =$pages->page_page($p,$nbdepages);
$feed = $pages->porperto_feed($lat,$lng, $radius, $limite ,$page )->fetchAll(PDO::FETCH_ASSOC);
//$array= $pages->porperto($db,$lat,$lng,$radius,$limite)->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($feed);

}elseif(!$local_id ){
/// simples 
$hastag = isset($_GET['hastag'])? $_GET['hastag']: NULL;
$refer = isset($_GET['refer'])? $_GET['refer']: false;
$perPage= isset($_GET['perpage'])? $_GET['perpage']: 25;
$perPage = is_numeric($perPage)?  $perPage : die('error');
$total = $pages->total ($hastag,$refer);

$nbPages= $pages->nb_Pages($total,$perPage);
//// pages
$Cpage =$pages->page_page($p,$nbPages);
$array= $pages->articles($perPage,$Cpage,$hastag,$refer)->fetchAll(PDO::FETCH_ASSOC);
if($p >= $nbPages){
	// http_response_code(404);
	 echo json_encode(["status" => "404"]);
}else{

	echo json_encode($array);
}



}
/// article unique
  elseif ($local_id){
$article= $pages->foto($local_id)->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($article);
}
/*
 http_response_code(404);
  http_response_code(200);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "Product does not exist."));
    */
?>
