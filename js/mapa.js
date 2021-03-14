// JavaScript Document
function initialize() {
	///reset zoom////
  zomm =  document.getElementById('volta');

  google.maps.event.addDomListener(zomm, 'click', function() {
   var Z = map.getZoom();
   map.setZoom(Z-2);
 });

  var styles = [{"featureType":"poi.attraction","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]}];
  var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});
	  /// 39.408442, -9.138331
    var latitude = 39.40844,
    longitude = -9.138331,
    center = new google.maps.LatLng(latitude,longitude)
    mapOptions = {
        center: center,
        zoom: 9,
        panControl: true,
        streetViewControl: false,
        mapTypeControlOptions: {
        mapTypeIds: [google.maps.MapTypeId.SATELLITE, 'map_style'],
        style: google.maps.ZoomControlStyle.SMALL,
  	   // position: google.maps.ControlPosition. BOTTOM_LEFT
   },
   mapTypeId: google.maps.MapTypeId.ROADMAP,
   scrollwheel: false
     };

 var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
				 //Associate the styled map with the MapTypeId and set it to display.
         map.mapTypes.set('map_style', styledMap);
         map.setMapTypeId('map_style');


         setMarkers(json,center,map);

       }

       function setMarkers(json,center, map) {


        var infoWindow = new google.maps.InfoWindow()
        var bounds = new google.maps.LatLngBounds();

        var markers = [];
        //loop between each of the json elements
        for (var i = 0, length = json.length; i < length; i++) {
          var data = json[i],
          latLng = new google.maps.LatLng(data.lat, data.lng); 
          bounds.extend(latLng);
                // Creating a marker and putting it on the map
                var marker = new google.maps.Marker({
                  position: latLng,
                  animation: google.maps.Animation.DROP,
                  map: map,
                  icon: "images/bolinha.png",
                  title: "hello"
                });
                markers.push(marker);

                (function(marker, data) {

  // Attaching a click event to the current marker
  google.maps.event.addListener(marker, "click", function(e) {
    infoWindow.setContent(data.content);
    infoWindow.open(map, marker);
  });

})(marker, data);
console.log(data);
}
			//
      var clusterStyles = [
      {
        textColor: 'black',
        textSize: 20,
        url: 'images/closter.png',
        height: 50,
        width: 50,

      }
      ];

      var mcOptions = {
        gridSize: 50,
        styles: clusterStyles,
        maxZoom: 15
      };
      var markerCluster = new MarkerClusterer(map, markers,mcOptions);
      map.fitBounds(bounds);

    }




    google.maps.event.addDomListener(window, 'load', initialize);