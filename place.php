<?php 
// $json_link = "https://graph.facebook.com/v2.10/search?type=place&q=cafe&center=40.7304,-73.9921&distance=1000&fields=name,checkins,picture&access_token={}";
//https://developers.facebook.com/docs/places/web/search
if( isset($_GET['lat']) && isset($_GET['lng'])) {
$lat = $_GET['lat'];
$lng = $_GET['lng'];
}else{ die();}
$access_token = 'EAARllcZAh6R8BANbvLy0Kw9lQ7NmEqSdEDfkdicdQKLwtTibXZAYhFK9B0zZB9CYbGUtddWAoSYX8jtZA1VE4Ox5J0FuhRVtjmqfXwVPZC7ggQrBGfPrGOFhjdvk19VIfdxTXQaxMtADR3qrpvnS0YvN1NyUc5e4nr1OZClHtM9ZCK0cCO6ZBSf7ZCP9cVruVQhwZD';
$categories = "['HOTEL_LODGING','FOOD_BEVERAGE','SHOPPING_RETAIL','ARTS_ENTERTAINMENT']";


function Loc_Places ($categories,$lat,$lng,$access_token) {
try{
	$url = isset($_POST['url'])? $_POST['url']: "https://graph.facebook.com/search?type=place&categories={$categories}&center={$lat},{$lng}&distance=1000&fields=name,about,overall_star_rating,link,checkins,picture&access_token={$access_token}";

        $json = file_get_contents($url);
        $places = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
       return $places;
		
    } catch (Exception $e) { var_dump($e);}
	
}
////
$places=  Loc_Places ($categories,$lat,$lng,$access_token);

$places_count = count($places['data']);
 $next=  isset( $places ['paging']['next']) ? $places ['paging']['next']:"";
var_dump($places);
	?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title> PEQUENO.EU | Places</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
<style type="text/css">
.media  {
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #CCC;
	}
	.top h1 {
    text-align: center;
    font-size: 18px;
    padding-top: 20px;
}

 </style>
</head>
<body>
<div class="container">
<header class="top">
<h1><img src="https://www.pequeno.eu/guia/images/dog.png" alt="pequenoeuGuide"> <br>Picture guide  </h1>
</header>
<hr>

<?php 
	if ($places_count > 0) :?>
    <ul class="list-unstyled">
	<?php foreach ($places['data'] as $item):?>
	  <?php
		$pic = isset($item ['picture']['data']['url']) ? $item ['picture']['data']['url'] : "";
		$name = $item ['name'];
		$rating = isset($item ['overall_star_rating']) ? "Rating-<span  class='text-primary'> " .$item ['overall_star_rating'] . "/5 *</span> ": "";
		$checkins =  isset($item ['checkins'])?$item ['checkins']:"" ;
		$about = isset($item ['about']) ? $item ['about'] ."<br>":"";
		$link = $item ['link']; ?>
        
     <li class="media mb-2">

       <img class="d-flex mr-3 border border-secondary" src ="<?=$pic;?>" />
       <div class="media-body">
        <h5 class="mt-0"><a href="<?=$link;?>"><?=$name;?></a></h5>
		<p><?=$about;?><small> <?=$rating;?></small> Checkins <?=$checkins;?></p>
       </div>
    </li>
	  <?php endforeach;?> 
  </ul>
  
     <?php if(isset( $places ['paging']['next'])) : ?>
      <form action="#" method="post">
        <input type="hidden" name="url" value="<?=$next;?>">
  <input class="btn btn-primary btn-lg btn-block" type="submit" value="More places..">
</form>
<hr>

<?php endif;?>

<?php else:?>
<h2 class="text-center"> Não existe mais nada por aqui...</h2>
<?php endif;?>

<hr>
<p class="text-center"> <small> Picture Guide guia fotográfico </small></p>

</div>
</body>
</html>

	