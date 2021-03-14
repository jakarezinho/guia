jQuery(function(){

  $(".hamburger").click(function(){
    $("#nav").animate({width: 'toggle'});
  });
  document.querySelector('.hamburger').addEventListener('click', function() {
    this.classList.toggle('active');
  });
  ///////
 ////
 var offset = 500,
 offset_opacity = 1200,
 scroll_top_duration = 700,
 $back_to_top = $('.cd-top');

 $(window).scroll(function(){
  ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
  if( $(this).scrollTop() > offset_opacity ) { 
   $back_to_top.addClass('cd-fade-out');
 }
});

 $back_to_top.on('click', function(event){
  event.preventDefault();
  $('body,html').animate({
   scrollTop: 0 ,
 }, scroll_top_duration
 );
});
	////////serch list
  function Serch_list() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("serchImput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("hastag_list");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
      a = li[i].getElementsByTagName("a")[0];
      if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
        li[i].style.display = "";
      } else {
        li[i].style.display = "none";

      }
    }
  }

  document.getElementById("serchImput").addEventListener('keyup', function() {Serch_list()});

/////////serch 


var x = $(".x");
var r =$("#result");
$(".search").keyup(function() { 
  var searchid = $(this).val();
  var dataString = 'search='+ searchid;
  if( searchid.length > 3 ){
    x.show()
    $.ajax({
      type: "POST",
      url: "cherche.php",
      data: dataString,
      cache: false,
      success: function(html){
        r.html(html).show();
      }
    });
  }else { r.hide();
   x.hide();
 }
 return false;    
});
x.click(function(){
  $(".search").val('');
  r.hide();
  x.hide();
});



});

 //<![CDATA[
       $(window).on('load', function () {// makes sure the whole site is loaded
            $('#status').fadeOut(); // will first fade out the loading animation
            $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
            $('body').delay(350).css({'overflow':'visible'});
          })
    //]]>
    
//////////

////////////////////////////COOKIES/////////////////////////

///////// FUNCTION CRIA COOKIE
function setCookie(cname, cvalue, exdays) {
  let d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  let expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + "; " + expires;
}
     ///////// FUNCTION VER COOKIE
     function getCookie(cname) {
      let name = cname + "=";
      let ca = document.cookie.split(';');
      for(let i=0; i<ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
      }
      return "";
    }

    function checkCookie(cnam) {
      let commentAux = getCookie("myfavori");
      if (commentAux != "") {
       alert("Welcome again ");
       return true;
     } else {
      return false;
    }
  }

/////// FUNCTION REMOVE COOKIE
function removeCokie (name){
  setCookie(name, JSON.stringify([]), 1)
  document.querySelector("#section"). innerHTML=""
  document.querySelector("#my_fav").style.display='none'
  document.querySelector("#my_fav2").style.display='none'
}
/////////// FUNCTION MOSTRA COKIE
function displayFavori(){
  let g = document.querySelector("#section");
  if (getCookie("myfavori")){
   console.log(getCookie("myfavori"))
   g.innerHTML=''
   let htmlContent = ''
   let flex = g.appendChild(document.createElement("div"))
   flex.classList.add('flex-container')
   let l = JSON.parse(getCookie("myfavori"))
   conta()
   for(let i=0; i< l.length; i++) {
    let fvi ='<img src="/images_guia/pequena'+l[i]["id"]+'.jpg">'
    htmlContent =  '<a href="'+ l[i]["url"] +'">'+l[i]["title"]+fvi+'</a> <span id="'+i+'"> X </span>'
    let title = flex.appendChild(document.createElement("p"))
    title.innerHTML=htmlContent
    removeFavori(i)
    insert=false

  }

}

}
//////ACTUALIZA CONTADOR
function conta(){
  let c1 = document.querySelector("#cont_fav");
  let f1 = document.querySelector("#my_fav");
  let c2 = document.querySelector("#cont_fav2");
  let f2 = document.querySelector("#my_fav2");
  if (getCookie("myfavori")){
    let lc = JSON.parse(getCookie("myfavori"))
    if(lc.length> 0){
      f1.style.display='block'
      f2.style.display='block'
      c1.innerHTML=lc.length
      c2.innerHTML=lc.length
    }else{
      f1.style.display='none'
      f2.style.display='none'
    }

  }

}  
/////////// FUNCTION ADITIONA FAVORITO

function addFavori() {
  let idf=document.querySelector('.modal_my')
  let xurl =document.URL
  let history = getCookie("myfavori");
  let xlat =idf.dataset.lat
  let xlng = idf.dataset.lng
  let xtitle = idf.dataset.title
  let xid = idf.dataset.id
  let comment = {
    title:xtitle,
    url:xurl,
    lat:xlat,
    lng:xlng,
    id:xid
  };

  if (history != "") {
    let insert = true;
    let sp = JSON.parse(history)

    for(let i=0; i< sp.length; i++) {

     if (sp[i]['url'] == xurl) {
      insert=false
    }

  }

  if(insert) {
    comments = JSON.parse(history);
    comments.push(comment);
    setCookie("myfavori", JSON.stringify(comments), 1);}

  }else {
   setCookie("myfavori", JSON.stringify([comment]), 1);


 }

}////end


///////// DETECT FAVORITO EXISTE 
function getfav(id){
 if (getCookie("myfavori")){
   let l = JSON.parse(getCookie("myfavori"))
   for(let i=0; i< l.length; i++) {
    if(l[i]["id"]==id){ return l[i]["id"]
  }

}
}               
}

/////////////DETEC FAV EXISTE INDEX 
function favIndex(){
  let fok = document.querySelectorAll('.favOK')
  let sf 
  for(let i=0; i< fok.length; i++){
    sf= getfav(fok[i].id)
    if(sf != undefined){
     fok[i].style.display="block"  
   }else{fok[i].style.display="none"}

 }

}

/////// APAGA ITEM FAVORITO
function removeFavori(index){
 let p = document.getElementById(index)
 p.addEventListener('click', function(){

  console.log(index +'index')
  if(index ==0){
    document.querySelector("#my_fav").style.display='none'}
    let data = JSON.parse(getCookie("myfavori"))
    console.log(data)
    data.splice(index,1)
    console.log(data)
    setCookie("myfavori", JSON.stringify(data), 1);
    p.parentNode.remove()
    conta()
  })

}

//////////// COKKIES
document.querySelector("#remove").addEventListener('click', function(){

  removeCokie('myfavori');
})


//// COMMANDES 
document.querySelector("#fecha_fav").addEventListener('click', function(){
  document.querySelector("#fav").style.display='none'
})
document.querySelector("#my_fav").addEventListener('click', function(){
  displayFavori()
  document.querySelector("#fav").style.display='block'
})
document.querySelector("#my_fav2").addEventListener('click', function(){
  displayFavori()
  document.querySelector("#fav").style.display='block'
})
///INIT