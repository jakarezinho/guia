<?php

use \guia\Divers;
use \guia\Hastag;
use Login\Guia\Galeria;
use \guia\Mobile_Detect;

require 'vendor/autoload.php';
////
$local_id    = $_GET['id'];
$pages       = new Divers();
$my_save_dir = '/images_guia/';
$my_save_dir_historic = '/history/';
$hast        = new Hastag();
///
//galeria
$galeria = new Galeria();
$h = $galeria->history_index($local_id);

$detect = new Mobile_Detect();
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$deviceType == 'computer' ? $tail = 'grande' : $tail = 'pequena';
$deviceType == 'computer' ? $th = 'foto_grande' : $th = 'foto_pequena';

///
$link = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
//$url = isset ($_SERVER['REQUEST_URI']) ?  "https://pequeno.eu". $_SERVER['REQUEST_URI']: "";
///
$nota = $pages->foto($local_id);

if (!isset($nota->id)) {
  header('Location: index.php');
  die();
}

$image = $my_save_dir . $tail . $nota->id . '.jpg';
$refer = $pages->refer($link);

$dtc = $pages->detect("foto", $local_id);
///// por perto
$radius = 1;
$limite = 13;
$ar = $pages->porperto($nota->lat, $nota->lng, $radius, $limite);

$conta = count($ar);
$data = getimagesize("images_guia/grande" . $nota->id . ".jpg");
$width = $data[0];
$height = $data[1];

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>PEQUENO.EU local a fotografar/ <?= $nota->title; ?></title>
  <meta name="description" content="<?= $nota->message; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
  <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="144x144" href="images/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="57x57" href="images/apple-touch-icon-57x57.png">
  <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
  <meta property="og:title" content="PEQUENO.EU | Picture guide | <?= $nota->title; ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="PEQUENO.EU | Picture guide" />
  <meta property="og:url" content="<?= $dtc; ?>" />
  <meta property="og:description" content="<?= $nota->message; ?>" />
  <meta property="og:image" content="<?= $image; ?>" />
  <meta property="og:image:width" content="<?= $width; ?>" />
  <meta property="og:image:height" content="<?= $height; ?>" />
  <meta property="fb:app_id" content="1237593859680543" />
  <!--//twitter-->

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="PEQUENO.EU | Picture guide/<?= $nota->title; ?>" />
  <meta name="twitter:description" content="<?= $nota->message; ?>" />
  <meta name="twitter:image" content="https://www.pequeno.eu<?= $image; ?>" />

  <meta itemprop="image" content="<?= $image; ?>" />

  <!--/maps/-->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>

  <!-- Latest compiled and minified CSS -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!--bootstrap-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="css/app.css">

</head>

<body>
  <div id="preloader">
    <div id="status">&nbsp;</div>
  </div>
  <!--top-->
  <?php include 'includes/top-nav.php'; ?>
  <!-- /modal -->
  <div class="container foto_top">
    <div class="row">
      <div id="<?= $nota->id; ?>" class="modal_my" data-id="<?= $nota->id; ?>" data-title="<?= $nota->title; ?>" data-lat="<?= $nota->lat; ?>" data-lng="<?= $nota->lng; ?>">
        <div class="modal-header">
          <?php if ($refer) : ?>
            <span id="volta" class="close">X</span>
          <?php endif; ?>
        </div>

        <div class="">
          <!-- class modal-body-->
          <div>
            <!--//may photo//-->
            <span class="myfavphoto"></span>
            <!--//history//-->
            <?php foreach ($h as $h_item) {
              $deviceType == 'computer' ? $th = $h_item->foto_grande : $th = $h_item->foto_pequena;
              echo '<img class="foto" src="' . $my_save_dir_historic . $th . '"><p class="content"><i class="material-icons">schedule</i>  Data: ' . date_format(date_create($h_item->date), "d-m-Y H:i:s") . '</p>';
            } ?>
            <img class="foto" src="<?= $image; ?>" alt="<?= $nota->title; ?>">
          </div>
          <div class="content">

            <p><i class="material-icons">schedule</i><?= date_format(date_create($nota->time), "d-m-Y H:i:s"); ?></p>
            <h4 class="modal-title" id="myModalLabel"><?= $nota->title; ?></h4>
            <div class="plus"> <?php if ($nota->recomendo == "yes") : ?><i class=" recomendo material-icons">favorite</i><?php endif; ?></div>
            <p><?= $hast->convertHashtags($nota->message, "hastag.php"); ?><?= $pages->extinct($nota->id, 'extinct') > 0 ? '<i class="material-icons myfavphoto ">info</i> ' : ''; ?></p>
          </div>
          <!--//GARDE COOKIE -->
          <p id="garde" class=" text-center "><span id="myf" class="btn btn-danger"><i class="material-icons">favorite_border</i> Adicionar ao roteiro</span></p>
          <!--///-->
          <hr>
          <div id="map" class="mapa_foto"> loading....</div>
        </div>

        <div class="">
          <!--<p> <i class="material-icons">location_on</i><?= $nota->morada; ?></p>-->
          <p class="text-center"> <a class="btn btn-outline-dark" role="button" href="https://www.google.com/maps/dir/Current+Location/<?= $nota->lat; ?>,<?= $nota->lng; ?>" target="_blank"> <i class="material-icons">near_me</i> Como chegar aqui? </a></p>
          <!--share-->
          <hr>
          <p><a data-toggle="collapse" href="#collapSocial" class="share"> <i class="material-icons">share</i> Share</a></p>
          <div class="collapse" id="collapSocial">
            <ul class="social list-inline">

              <li class="list-inline-item"> <a class="btn-facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= $dtc; ?>">Facebook</a> </li>
              <li class="list-inline-item">
                <a class="btn-pinterest" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?= $dtc; ?>&description=<?= $nota->title; ?>&media=https://www.pequeno.eu<?= $image; ?>">Pinterest</a>
              </li>

              <li class="list-inline-item"> <a class=" btn-tweet" target="_blank" href="https://twitter.com/intent/tweet?text=PequenoEu&url=<?= $dtc; ?>&via=Pequeno.eu #streetphotpgraphycolor #portugal #Portugaltravel">Tweet</a></li>
            </ul>

            <h4><i class="material-icons ring">notifications_active</i></h4>
            <hr>
            <hr>
          </div>
          <!--//-->

        </div>
        <?php if ($conta > 1) : ?>
          <h4> Por perto / nearby ...</h4>

          <?php if ($conta > 10) : ?> <p> <i class="material-icons">my_location</i> Spot!</p> <?php endif; ?>
          <hr>
          <div class="row">
            <?php foreach ($ar as $rond) : ?>

              <?php $corrent = $rond->id == $local_id ? 'cible' : '';
              $dist = (round($rond->distance, 3)) * 1000; ?>
              <div class="col-sm-6 col-md-6 <?= $corrent; ?>">
                <div class="post">
                  <span id="<?= $rond->id; ?>" class="favOK"><i class="material-icons">bookmark</i></span>
                  <?php $image2 = $my_save_dir . 'pequena' . $rond->id . '.jpg'; ?>
                  <a href="foto.php?id=<?= $rond->id; ?>"> <img class="img-fluid" src="<?= $image2; ?>"></a>
                  <div class="infos_foto">
                    <h5><?= $rond->title; ?></h5>

                    <p><small> Distante; <?= $dist; ?> m </small></p>
                  </div>
                </div>
              </div>
          <?php endforeach;
          endif; ?>

          </div>
          <!-- localiza por perto -->
          <?php if ($conta == 13) : ?>
            <form action="mapa_env.php" method="post" id="local">
              <input type="hidden" id="lat" name="lat" value="<?= $nota->lat; ?>">
              <input type="hidden" id="lng" name="lng" value="<?= $nota->lng; ?>">
              <input type="hidden" id="radius" name="rad" value="1">
              <p class="text-center"><input type="submit" class="btn btn-outline-dark" name="vermais" value=" Ver mais por perto »"> </p>
            </form>
          <?php endif; ?>
      </div>
      <!--/modal//-->
    </div>
    <!-- localiza -->
    <form action="mapa_env.php" method="post" id="local">
      <input type="hidden" id="lat" name="lat">
      <input type="hidden" id="lng" name="lng">
    </form>
    <div id="envia" class="localiza pages_loc">
      <i class="material-icons">my_location</i>
    </div>
    <!--//-->
    <!--/row-->
  </div>


  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <script src="js/localize.js"> </script>
  <script src="js/app.js"> </script>
  <script>
    let volta = document.querySelector("#volta");
    if (volta) {
      volta.addEventListener("click", function() {
        window.close();
      });
    }

    // load a tile layer
    const position = ['<?= $nota->lat; ?>', ' <?= $nota->lng; ?>']
    const map = L.map('map', {
      center: position,
      dragging: false,
      tap: false,
      zoom: 14
    });
    let CartoDB_Voyager = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
      subdomains: 'abcd',
      maxZoom: 19
    });
    CartoDB_Voyager.addTo(map);

    var myIcon = L.icon({
      iconUrl: '/images/bolinha.png',
      iconAnchor: [15, 15],
      // popupAnchor: [15, 0],
      // iconRetinaUrl: myURL + 'images/pin48.png',

    });
    var herteIcon = L.icon({
      iconUrl: '/images/myfav.png',
      iconAnchor: [15, 0],
      // popupAnchor: [15, 0],
      // iconRetinaUrl: myURL + 'images/pin48.png',

    });
    let buildingLayers = L.layerGroup().addTo(map);


    function initialize() {
      markersLayer = L.marker(position, {
        icon: myIcon
      })
      buildingLayers.addLayer(markersLayer);
      var circle = L.circle(position, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.1,
        radius: 500
      })
      buildingLayers.addLayer(circle);

      set_markers_fav(map)
    }

    ///////// MARKERS FAVORITOS 
    function set_markers_fav(map) {

      if (getCookie("myfavori")) {
        console.log(getCookie("myfavori"))
        let l = JSON.parse(getCookie("myfavori"))

        for (let i = 0; i < l.length; i++) {
          let point = [l[i]["lat"], l[i]["lng"]]
          console.log(point)
          markersFav = L.marker(point, {
            icon: herteIcon
          }).bindPopup('<p> MY FAVORITE PLACE </p> <p><a href="' + l[i]["url"] + '" target="_blank">' + l[i]["title"] + '</a> »</p>').openPopup();
          buildingLayers.addLayer(markersFav);


        }
      }
    } //
    ///garde fav
    document.querySelector('#garde').addEventListener('click', () => {
      addFavori()
      displayFavori()
      set_markers_fav(map)
      document.querySelector('#myf').innerHTML = '<i class="material-icons">favorite</i> Favorita'

    })
    ///init
    displayFavori()
    favIndex()
    window.load = initialize()

    ///display fav
    var el = document.querySelector('.modal_my')
    if (getfav(el.id) != undefined) {
      document.querySelector('#garde').style.display = 'none'
      document.querySelector('.myfavphoto').innerHTML = '<i class="material-icons">bookmark</i> Favorita'
    }
    console.log(getfav(el.id))
  </script>

  <?php include 'includes/footer.php'; ?>