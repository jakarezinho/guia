  function showlocation() {
				if (navigator.geolocation) {
       // One-shot position request.
				navigator.geolocation.getCurrentPosition(callback, errorHandler)
				
		       
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
           
            }
         
      function callback(position) {
      var lat = position.coords.latitude;
      var lon = position.coords.longitude;
			var l =   document.querySelector('#lat').value = lat;
			var lg =  document.querySelector('#lng').value = lon;
      var load =document.createElement('div');
      load.classList.add('load_local');
      var t = document.createTextNode("A procurar localização...");                     // Create a <p> node
       load.appendChild(t);  
      document.body.appendChild(load);
			console.log(load);
           if ( (l =="")||(lg  =="") ) { alert ('Ops!! dificuldade a localizar try again'); 
		   return false;}else {
      
        setTimeout(function(){ document.querySelector('#local').submit() }, 1000);}
        
		
      }
      
	  function errorHandler(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      alert("User denied the request for Geolocation.");
      break;
    case error.POSITION_UNAVAILABLE:
      alert("Location information is unavailable.");
      break;
    case error.TIMEOUT:
      alert("The request to get user location timed out.");
      break;
    case error.UNKNOWN_ERROR:
      alert("An unknown error occurred.");
      break;
    }
  }
document.querySelector('#envia').addEventListener('click', function() { showlocation()

});