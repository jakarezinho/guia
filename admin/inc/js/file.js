  //////API file///
  window.onload = function() {
      if (window.File && window.FileReader && window.FileList && window.Blob) {
          // Great success! All the File APIs are supported.

          var fileInput = document.getElementById('photoimg');
          var fileDisplayArea = document.getElementById('output');


          if (fileInput) {
              fileInput.addEventListener('change', function(e) {
                  var file = fileInput.files[0];
                  var imageType = /image.jpeg/;
                  if (file.type.match(imageType)) {
                      var reader = new FileReader();

                      reader.onload = function(e) {
                          fileDisplayArea.innerHTML = "";

                          var img = new Image();
                          img.src = reader.result;

                          fileDisplayArea.appendChild(img);
                      }

                      reader.readAsDataURL(file);

                  } else {
                      fileDisplayArea.innerHTML = "<h3 class='error'>File not supported!</h3>"
                  }
              });
          }
          ///fin test
      } else {
          document.getElementById('SubmitButton').style.display = "block";
          alert('The File APIs are not fully supported in this browser.');
      }

  }

  /////////////MAPA///////////////////////

  //// VERIFIER PAGE MAP
  if (document.body.contains(document.getElementById('lat')) && document.body.contains(document.getElementById('lng'))) {
      let map
      let latDepart = document.getElementById('lat')
      let lngDepart = document.getElementById('lng')
      let localisation = document.getElementById('localiza')
      let verifica = document.getElementById("verifier")
      let marker
      let r = L.layerGroup()
      if (latDepart.value.length == 0 && lngDepart.value.length == 0 && localisation.value.length == 0) {
          map = L.map('mapa').setView([40.91, -96.63], 4);
          initMapa().addTo(map)
          r.addTo(map);
          console.log('sem lalng')

      } else if (latDepart.value.length > 0 && lngDepart.value.length > 0) {
          map = L.map('mapa').setView([latDepart.value, lngDepart.value], 18);
          // map.panTo([latDepart,lngDepart]);
          initMapa().addTo(map)
          r.addTo(map);
          marker = L.marker([latDepart.value, lngDepart.value], {
              draggable: true
          })
          r.addLayer(marker);
          marker.on('dragend', function(e) {
              latDepart.value = marker.getLatLng().lat;
              lngDepart.value = marker.getLatLng().lng;
              console.log('lat lang')
          })
          console.log('if else')

      }

      ///// DEPART 


      var searchControl = L.esri.Geocoding.geosearch().addTo(map);

      searchControl.on('results', function(data) {
          r.clearLayers();
          for (var i = data.results.length - 1; i >= 0; i--) {
              localisation.value = data.text;
              let marker = L.marker(data.results[i].latlng, {
                  draggable: true
              })
              r.addLayer(marker);
              marker.on('dragend', function(e) {
                  reversse(marker.getLatLng().lat, marker.getLatLng().lng)
                  latDepart.value = marker.getLatLng().lat;
                  lngDepart.value = marker.getLatLng().lng;

              })

          }
      });

      /////LOCALIZA
      if (document.body.contains(document.getElementById('verifier'))) {
          verifica.addEventListener("click", () => {
              console.log('hello morada')
              morada(r)

          })
      }


      ////// ENCONTRA MORADA COM LAT LNG 

      function morada(r) {
          r.clearLayers()
          if (localisation.value.length == 0) {
              return;
          }
          var adresse = localisation.value;
          console.log(adresse);
          L.esri.Geocoding.geocode().text(adresse).run(function(err, results, response) {
              if (err) {
                  console.log(err);
                  return;
              }


              Object.keys(results).map(function(objectKey, index) {
                  var value = results[objectKey][index].latlng;
                  console.log(latDepart.value, lngDepart.value);
                  latDepart.value = value.lat;
                  lngDepart.value = value.lng;
                  marker = L.marker([latDepart.value, lngDepart.value], {
                      draggable: true
                  })

                  r.addLayer(marker);
                  map.fitBounds([value])
                  marker.on('dragend', function(e) {
                      reversse(marker.getLatLng().lat, marker.getLatLng().lng)
                      latDepart.value = marker.getLatLng().lat;
                      lngDepart.value = marker.getLatLng().lng;
                  })

              });


          });
      }

      ///////////// REVERSSE LAT LNG  MORADA

      function reversse(lat, lng) {
          L.esri.Geocoding.reverseGeocode()
              .latlng([lat, lng])
              .run(function(error, result, response) {
                  console.log(response['address'])
                  localisation.value = response['address']['Match_addr']
              });
      }
      ////// INIT MAP LAYER
      function initMapa() {
          return L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
          })
      }

  } //existe




  ///////////jQuery///////////////
  jQuery(function() {
      function removeAnexo(toto) {
          var id = $(toto).parent("li").attr('id');

          console.log(id);
          // Removendo arquivo do servidor
          var r = confirm("Quer mesmo apagar este check-in?")
          if (r == true) {
              $.post("apaga.php", {
                  acao: 'removeAnexo',
                  id: id
              }, function(data) {
                  if (data == 1) {
                      $(toto).parent("li").toggle("slow");
                  }
                  if (data == 0) {
                      alert("Error !");
                  }
              });
          }

      } /////

      var p = $("p.delete");
      p.on("click", function() {
          removeAnexo(this);
      });

      /////
  });