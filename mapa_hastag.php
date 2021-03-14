<?php
require 'class/Autoloader.php';

use \guia\Autoloader;
use \guia\Mobile_Detect;
use \guia\Divers;
use \guia\Hastag;
use \guia\App;

Autoloader::register();
$db = App::getDatabase();
///
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
///
$pages = new Divers($db);
$my_save_dir = '/guia/images_guia/';
$hast = new Hastag($db);
///
$hastag = isset($_GET['hastag']) ? $_GET['hastag'] : NULL;
$refer = isset($_GET['refer']) ? $_GET['refer'] : false;
$perPage = 25;
$total = $pages->total($hastag, $refer);
///paginate
$nbPages = $pages->nb_Pages($total, $perPage);
$p = isset($_GET['p']) ? $_GET['p'] : 0;
$Cpage = $pages->page_page($p, $nbPages);

function pagine($p, $nbPages)
{
  if (isset($p)  && $p > 0 && $p <= $nbPages) {
    return $Cpage = $p;
  } else {
    return $Cpage = 1;
  }
}

$notas = $pages->articles($perPage, $Cpage, $hastag, $refer)->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>Short Guide</title>
  <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
  <meta name="description" content="Short Guide guia das cidades em fotografias">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="theme-color" content="#ffffff">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <!--/maps/-->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!-- Optional theme -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="css/app.css">

  <script>
    let json = <?php echo json_encode($notas, true); ?>
  </script>
</head>

<body>
  <div id="map-canvas" class="mapa with-photos"> </div>
  <?php if ($hastag) : ?>
    <p class="text-center volta_lista"> <a class="btn btn-primary" href="hastag.php?hastag=<?= $hastag; ?>&refer=<?= $refer; ?>"><img src="images/dog.png" width="32" height="32" border="0"> </a></p>
  <?php else : ?>
    <p class="text-center volta_lista "> <a class="btn btn-primary" href="index.php"> <img src="images/dog.png" width="32" height="32" border="0"></a></p>
  <?php endif; ?>

  <!--nav//-->
  <footer class="nav nav_mapa">


    <?php if ($Cpage < $nbPages || $Cpage >= 2) : ?>
      <div class=" more-notes centre">
        Pages <?= $Cpage; ?> /<?= $nbPages; ?> » #<?= $hastag; ?>
        <?php if (isset($hastag) && !empty($refer)) {
          if ($Cpage < $nbPages) {
            echo '<a href="?p=' . ($Cpage + 1) . '&hastag=' . $hastag . '&refer=' . $refer . '"><span class="seguinte"> »</span></a>';
          }
          if ($Cpage >= 2) {
            echo '<a href="?p=' . ($Cpage - 1) . '&hastag=' . $hastag . '&refer=' . $refer . '"><span class="precedente"> « </span></a>';
          }
        } else if ($hastag) {
          if ($Cpage < $nbPages) {
            echo '<a href="?p=' . ($Cpage + 1) . '&hastag=' . $hastag . '"><span class="seguinte"> »</span></a>';
          }
          if ($Cpage >= 2) {
            echo '<a href="?p=' . ($Cpage - 1) . '&hastag=' . $hastag . '"><span class="precedente"> « </span></a>';
          }
        } else {

          if ($Cpage < $nbPages) {
            echo '<a href="?p=' . ($Cpage + 1) . '"><span class="seguinte"> »</span></a>';
          }
          if ($Cpage >= 2) {
            echo '<a href="?p=' . ($Cpage - 1) . '"><span class="precedente"> « </span></a>';
          }
        }

        ?>
      </div>
    <?php endif; ?>
    <!-- localiza -->

    <form action="mapa_env.php" method="post" id="local">
      <input type="hidden" id="lat" name="lat">
      <input type="hidden" id="lng" name="lng">
    </form>

    <div id="envia" class="localiza maps_loc">
      <i class="material-icons">my_location</i></div>
    <div class="page-footer">
      <div class="tabs_img" id="tabs"> </div>
    </div>
    <script src="js/localize.js"> </script>
  </footer>

  <script>
    // JavaScript Document
    const map = L.map('map-canvas');
    let buildingLayers = L.layerGroup().addTo(map);
    let postsContainer = document.getElementById('tabs')
    let thisLayer = L.popup({
      maxWidth: '350px'
    })
    // load a tile layer
    var CartoDB_Voyager = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
      subdomains: 'abcd',
      maxZoom: 19
    });
    CartoDB_Voyager.addTo(map);

    var myIcon = L.icon({
      iconUrl: '/images/bolinha.png',
      iconAnchor: [15, 0],


    });
    var herteIcon = L.icon({
      iconUrl: '/images/myfav.png',
      iconAnchor: [15, 0],

    });

    function initialize() {

      var bounds = L.latLngBounds()


      for (var i = 0, length = json.length; i < length; i++) {

        var data = json[i]
        let foto = document.createElement('img')
        foto.setAttribute('data-lng', data.lng)
        foto.setAttribute("data-lat", data.lat);
        let num = document.createElement('span')
        let link = document.createElement('a')
        let labels = "" + (i + 1);
        let src = '<img src="images_guia/' + data.foto_mini + '" style="width:120px; height:auto;">'
        let icon_img = "images/bolinha.png"
        foto.src = 'images_guia/' + data.foto_mini
        let content_window = labels + '<div><a href="/foto.php?id=' + data
          .id + '" target="blank">' + data.title + '» ' + src + '</a></div>'

        postsContainer.appendChild(foto)
        num.innerHTML = i + 1
        foto.parentNode.appendChild(num)

        //mapa
        latlng = [data.lat, data.lng]
        bounds.extend(latlng)
        markersLayer = L.marker([data.lat, data.lng], {
          icon: myIcon
        })
        buildingLayers.addLayer(markersLayer);

        markersLayer.addEventListener('click', (e) => {
          let pointmarker = markersLayer.getLatLng()

          thisLayer.setLatLng(e.latlng)
            .setContent(content_window)

          map.panTo(e.latlng);
          buildingLayers.addLayer(thisLayer);
          S
        })
        foto.addEventListener('click', (e) => {
          let el = e.currentTarget
          let point = [el.dataset.lat, el.dataset.lng]
          thisLayer.setLatLng(point)
            .setContent(content_window)
          foto.classList.add("visit");

          map.panTo(point);

          buildingLayers.addLayer(thisLayer);
        })

      }
      map.fitBounds(bounds)
      set_markers_fav(map)
    }
    ///////// FUNCTION VER COOKIE
    function getCookie(cname) {
      let name = cname + "=";
      let ca = document.cookie.split(';');
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
      }
      return "";
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

    window.load = initialize();
  </script>
</body>

</html>