<?php

use \guia\Mobile_Detect;
use \guia\Divers;
use \guia\Hastag;

require 'vendor/autoload.php';

////

$detect = new Mobile_Detect();

$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
//$deviceType =='computer' ? $tail='grande' : $tail='pequena';
$tail = 'pequena';
///
$pages = new Divers();

$my_save_dir = '../images_guia/';
$hast = new Hastag();
///

$perPage = 6;
$total = $pages->total();
$nbPages = $pages->nb_Pages($total, $perPage);

//
$p = isset($_GET['p']) ? $_GET['p'] : 0;
//
$Cpage = $pages->page_page($p, $nbPages);
$notas = $pages->articles($perPage, $Cpage);

$dtc = $pages->detect("index");
/////

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>PICTURE GUIDE- dicas de locais a visitar e a fotografar</title>
  <meta name="description" content="pequeno.eu PICTURE GUIDE | Exploração urbana  locais a descobrir e a fotografar">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="application-name" content="Picture Guide">
  <meta name="apple-mobile-web-app-title" content="Picture Guide">
  <meta name="theme-color" content="#ffffff">
  <meta name="msapplication-navbutton-color" content="#fced1e">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="msapplication-starturl" content="/guia/index.php">

  <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
  <link rel="icon" sizes="144x144" href="../images/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="72x72" href="../images/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="144x144" href="../images/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="57x57" href="../images/apple-touch-icon-57x57.png">
  <!--// Facebook-->
  <meta property="og:title" content="pequeno.eu PICTURE GUIDE | guiadas cidades em fotografias" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="PEQUENO.EU | Picture guide" />
  <meta property="og:url" content="<?= $dtc; ?>" />
  <meta property="og:description" content="Guia das cidades em fotografias geolocalizadas" />
  <meta property="og:image" content="images_guia/grande527.jpg" />
  <meta property="og:image:width" content="900" />
  <meta property="og:image:height" content="506" />
  <meta property="fb:app_id" content="1237593859680543" />
  <!--//twitter-->

  <meta name="twitter:card" content="summary" />
  <meta name="twitter:title" content="PEQUENO.EU | Picture guide/" />
  <meta name="twitter:description" content="pequeno.eu PICTURE GUIDE | guia das cidades em fotografias" />
  <meta name="twitter:image" content="https://www.pequeno.eu/guia/images_guia/grande527.jpg" />

  <!--icons google-->
  <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet" />
  <!--bootstrap-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="css/app.css">

  <!-- Otheme -->
  <link rel="manifest" href="../manifest.json">

  <script>
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker
        .register('../service-worker.js')
        .then(function() {
          console.log('Service Worker Registered');
        });
    }
  </script>
</head>

<body>

  <div id="preloader">
    <div id="status">&nbsp;</div>
  </div>
  <!--//TOP NAV-->
  <?php include 'includes/top-nav.php'; ?>

  <div class="title">
    <h1>Picture Guide </h1>
    <h2>Pequeno guia de <?= $nbPages; ?> <?php if ($nbPages > 1) { echo "paginas"; } else { echo "pagina";} ?> </h2>
    <p> <i class="material-icons-two-tone">map</i><a href="mapa.php">ver no mapa »</a></p>
  </div>

  <div class="grid">
    <?php foreach ($notas as $req) : ?>
      <?php $image = $my_save_dir . $tail . $req->id . '.jpg'; ?>
      <div class="grid-sizer"></div>
      <!--//item/-->
      <div class="grid-item">
        <span id="<?= $req->id; ?>" class="favOK"><i class="material-icons">bookmark</i></span>
        <a href="foto.php?id=<?= $req->id; ?>" target="_blank"><img src="<?= $image; ?>" alt="<?= $req->title; ?>" width="100%" /></a>
        <h4><?= $req->title; ?></h4>
        <div class="plus"> <?php if ($req->recomendo == "yes") : ?><i class="recomendo material-icons">favorite</i><?php endif; ?></div>
        <p><?= $hast->convertHashtags($req->message, "hastag.php");echo $pages->extinct($req->id, 'extinct')>0? '<i class="material-icons myfavphoto ">info</i> ' : ''; ?></p>


      </div>

    <?php endforeach; ?>
  </div>
  <!--//item/-->

  <div class="page-load-status text-center">
    <div class="loader infinite-scroll-request">Loading...</div>
    <p class="infinite-scroll-last">End of content</p>
    <p class="infinite-scroll-error">No more pages to load</p>
  </div>



  <!--nav//-->
  <div class="boton_map">
    <p class="text-center"> <a href="mapa.php" class="btn btn-default btn-lg"> <i class="material-icons">location_on</i> ver no mapa»</a> </p>
  </div>
  <div id="paginate" class="nav">
    <?php
    echo '<p class="centre"> Pages ' . $Cpage . ' / ' . $total . ' </p> ';
    if ($Cpage < $nbPages) {
      echo '<p><a class="pag-next" href="?p=' . ($Cpage + 1) . '">Seguinte »</a></p>';
    } ?>
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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


 <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
  <script src="https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.min.js"></script>
  <script src="js/localize.js"> </script>

  <script src="js/app.js"> </script>
  <script>
    function loadIndex() {
      var msnry = new Masonry('.grid', {
        // Masonry options...
        itemSelector: '.grid-item',
        columnWidth: '.grid-sizer',
      });

      // init Infinite Scroll
      var infScroll = new InfiniteScroll('.grid', {
        path: 'index.php?p={{#}}',
        // path set to string with {{#}}
        checkLastPage: '.pag-next',
        status: '.page-load-status',
        hideNav: '#paginate',
        append: '.grid-item',
        outlayer: msnry,
        debug: true,
      });

    }


    ///INIT
    window.onload = function() {
      console.log("All resources finished loading!")
      loadIndex();
      displayFavori()
      favIndex()


    }
    window.onscroll = function() {
      favIndex()
      displayFavori()
    }
  </script>

  <?php include 'includes/footer.php'; ?>