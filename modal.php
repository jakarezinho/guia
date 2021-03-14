<?php
require 'class/Autoloader.php'; 
Autoloader::register(); 
$db= App::getDatabase();
////
$local_id = $_GET['id'];
$pages= new Divers();
$my_save_dir = '../geo/images_guia/';
$hast = new Hastag();
///

 
$r= $db->query("SELECT * FROM hastag WHERE id='$local_id'  AND public='1' ");
$nota = $r->fetch(PDO::FETCH_OBJ);
$image = $my_save_dir.'grande'.$nota->id.'.jpg';
$url= "http://www.check-inlove.com/guia/";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Picture Guide - Checkinlove / <?=$note->title;?></title>
<meta name="description" content="<?=$note->texte;?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="robots" content="noindex,follow" />
<meta property="og:title" content="CheckinLove PICTURE GUIDE|<?=$note->title;?>" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="CheckinLove PICTURE GUIDE" />
<meta property="og:url" content="<?=$url;?>" />
<meta property="og:description" content="<?=$note->texte;?>" />
<meta property="og:image" content="<?=$image;?>"/>
<!--//twitter-->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="Checkinlove  PICTURE GUIDE/<?=$title;?>">
<meta name="twitter:creator" content="@checkinlove">
<meta name="twitter:title" content="Checkinlove picture guide">
<meta name="twitter:description" content="<?=$note->title.$note->texte;?>">
<meta name="twitter:image" content="<?=$image;?>">  
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDuFEnYW8kosE4JjPvC9Qq8qfd4KHbduH0&region=PT&callback=initMap"> </script>
  <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
	height: 500px;
	width: 100%;
	background-color: #FF9;
      }
    </style>

</head>
<body>
            		<!-- /modal -->
               <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=$nota->title;?></h4>
      </div>
         <div ><img class="img-responsive" src="<?=$image;?>"></div>
          <div class="modal-body">
<p>><?=$hast->convertHashtags($nota->message,"h.php");?> </p> 
<div id="map"> loading....</div>
     </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>

<script>

      function initMap() {
       var myLatLng = new google.maps.LatLng(<?=$nota->lat;?>,<?=$nota->lng;?>);

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: myLatLng
        });

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });
      }

    </script>

  <script src="js/app.js"> </script>
</body>
</html>
