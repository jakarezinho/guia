<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use \guia\Divers;


require 'vendor/autoload.php';
$pages= new Divers();

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
$feed = $pages->porperto_feed($lat,$lng, $radius, $limite ,$page );
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
$posts= $pages->articles($perPage,$p,$hastag,$refer);

if($p <= $nbPages){
  	echo json_encode(["posts" => $posts, 'pages'=>$nbPages]);

}else{ 
  	// http_response_code(404);
 echo json_encode(["status" => '404']);

}



}
/// article unique
  elseif ($local_id){
$article= $pages->foto($local_id);
echo json_encode($article);
}
/*
 http_response_code(404);
  http_response_code(200);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "Product does not exist."));
    */
