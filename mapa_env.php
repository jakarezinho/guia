<?php
if (isset($_POST['lat']) && isset($_POST['lng'])) {
  $lat = $_POST['lat'];
  $lng = $_POST['lng'];
  $radius = isset($_POST['rad']) ? $_POST['rad'] : '0.5';
} else {
  $lat = '39.408809';
  $lng = '-9.1225972';
  $radius = '1';
}
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

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- Latest compiled and minified CSS -->



  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!-- Optional theme -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/app.css">

  <!-- Optional theme -->

  <link rel="stylesheet" type="text/css" href="css/app.css">


<body>

  <!--NAV-->

  <!--///NAV///-->

  <!--<p><button id="volta" class="resetzoom btn btn-default ">+ - Zoom</button></p>-->
  <div id="map-canvas" class="mapa with-photos"> </div>

  <p class="text-center volta_lista "> <a class="btn btn-primary" href="index.php"><img src="images/dog.png" width="32" height="32" border="0"> </a></p>

  <!--nav//-->
  <footer class="nav nav_mapa">
    <div class=" more-notes centre">
      <span id="legenda"></span>
    </div>

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
    <script>
      /// DADOS DE PARTIDA
      //L.marker(['39.408809', ' -9.137201'],{icon: herteIcon} ).addTo(map)
      const mapcanvas = document.getElementById('map-canvas')
      let lat = "<?= $lat ?>" //'40.208494' 
      let lng = "<?= $lng ?>" //' -8.419078'
      let radius = "<?= $radius ?>"
      const map = L.map('map-canvas').setView([lat, lng], 14);


      let buildingLayers = L.layerGroup().addTo(map);
      let postsContainer = document.getElementById('tabs')
      let legenda = document.getElementById('legenda')

      let url = '/json_map.php'


      // load a tile layer
      let CartoDB_Voyager = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
      });
      CartoDB_Voyager.addTo(map);

      var myIcon = L.icon({
        iconUrl: '/images/bolinha.png',
        iconAnchor: [15, 0],
        // popupAnchor: [15, 0],
        // iconRetinaUrl: myURL + 'images/pin48.png',

      });
      var herteIcon = L.icon({
        iconUrl: '/images/myfav.png',
        iconAnchor: [15, 0],
        // popupAnchor: [15, 0],
        // iconRetinaUrl: myURL + 'images/pin48.png',

      });
      var posIcon = L.icon({
        iconUrl: '/images/dog.png',
        iconAnchor: [15, 0],
        // popupAnchor: [15, 0],
        // iconRetinaUrl: myURL + 'images/pin48.png',

      });


      ///// ADD CRUZ
      function add_cruz() {
        a = document.createElement('div');
        b = document.createElement('div');
        c = document.getElementById('map').appendChild(a);
        a.classList.add('cib');
        m = document.querySelector('.cib').appendChild(b);
        b.classList.add('croix');
      }

      //// REMOVE CRUZ
      function remove_cruz() {
        var d = document.getElementById("map");
        var d_nested = document.querySelector('.cib');
        d.removeChild(d_nested);
        buildingLayers.clearLayers()

      }

      //// ADD MARKER POSITION //
      function ad_position(pos) {
        let my = L.marker(pos, {
          icon: posIcon
        }).addTo(map)
        buildingLayers.addLayer(my);

      }

      //// MAP ADD CIBLE CENTRE 

      map.on("dragstart", () => {
        add_cruz()
        console.log('adiciona crus')
      })

      ////// MAP DRAGED///
      map.on("dragend", () => {
        set_markers_fav(map)
        remove_cruz();
        let pos = map.getCenter()
        buildingLayers.clearLayers()
        postsContainer.innerHTML = ''
        console.log(pos)
        ad_position(pos)
        LoadPosts(url, pos.lat, pos.lng, radius)

      });


      function LoadPosts(url, lat, lng, radius) {
        set_markers_fav(map)

        // Creating a new map
        let thisLayer = L.popup({
          maxWidth: '350px'
        })
        // var bounds = L.latLngBounds()

        fetch(url + '?' + 'lat=' + lat + '&lng=' + lng + '&radius=' + radius)
          .then(response => {
            if (response.status == 200 && response.ok == true) {
              response.json()
                .then(posts => {
                  if (posts.length > 0) {
                    legenda.innerHTML = 'Numero de fotos por aqui <strong>' + posts.length + '</strong> a ' + radius * 1000 + ' metros'
                    for (let i = 0; i < posts.length; i++) {
                      let nimage = posts.length
                      let post = posts[i]
                      let foto = document.createElement('img')
                      let num = document.createElement('span')
                      let link = document.createElement('a')
                      let labels = "" + (i + 1);
                      let src = '<img src="/images_guia/' + post.foto_mini + '" style="width:120px; height:auto;">'
                      let icon_img = "/images/bolinha.png"
                      foto.src = '/images_guia/' + post.foto_mini
                      let content_window = labels + '<div><a href="/foto.php?id=' + post
                        .id + '" target="blank">' + post.title + '» ' + src + '</a></div>'

                      postsContainer.appendChild(foto)
                      num.innerHTML = i + 1
                      foto.parentNode.appendChild(num)

                      ///map
                      latlng = [post.lat, post.lng]
                      // bounds.extend(latlng)

                      //marker(latlng)
                      //.bindPopup(content_window, {maxWidth: '350px'})
                      //.addTo(map)
                      //buildingLayers.clearLayers();
                      markersLayer = L.marker([post.lat, post.lng], {
                        icon: myIcon
                      })
                      buildingLayers.addLayer(markersLayer);

                      markersLayer.addEventListener('click', () => {
                        thisLayer.setLatLng([post.lat, post.lng])
                          .setContent(content_window)

                        //buildingLayers.clearLayers(); // remove existing markers

                        map.panTo([post.lat, post.lng]);
                        //thisLayer.addTo(mymap);
                        buildingLayers.addLayer(thisLayer);
                      })

                      foto.addEventListener('click', () => {
                        thisLayer.setLatLng([post.lat, post.lng])
                          .setContent(content_window)

                        //buildingLayers.clearLayers(); // remove existing markers
                        map.panTo([post.lat, post.lng]);
                        //thisLayer.addTo(mymap);
                        buildingLayers.addLayer(thisLayer);
                        foto.classList.add("visit");
                      })
                    } //i
                    // map.fitBounds(bounds)
                  } else {
                    legenda.innerHTML = ' SEM FOTOS A APRESENTAR POR AQUI!'
                  }
                }) ///then

            }

          })
      } /////



      ///// ADD CRUZ
      function add_cruz() {
        a = document.createElement('div');
        b = document.createElement('div');
        c = document.getElementById('map-canvas').appendChild(a);
        a.classList.add('cib');
        m = document.querySelector('.cib').appendChild(b);
        b.classList.add('croix');
      }
      //// REMOVE CRUZ
      function remove_cruz() {
        var d = document.getElementById("map-canvas");
        var d_nested = document.querySelector('.cib');
        d.removeChild(d_nested);

      }



      //////////////////////EXTRAS //////////////////

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
      window.load = LoadPosts(url, lat, lng, radius), ad_position([lat, lng])
    </script>
  </footer>
</body>

</html>