<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="description" content="PICTURE GUIDE guia das cidades em fotografias">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="144x144" href="images/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="57x57" href="images/apple-touch-icon-57x57.png">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">


<link rel="stylesheet" type="text/css" href="css/app.css">
<style> 
.r{
	display:none;
	}
	.r ul li  a {
	color: #00C;
	 font-weight:bold;	
		}
</style>

<!-- Latest compiled and minified JavaScript -->



</head>

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<div class="content">
<input type="text" class="search" id="searchid" placeholder="pesquisar" />&nbsp; &nbsp; Ex: <b><i>caldasdarainha nazare obidos</i></b><br /> 
<div id="result" class="r"></div>
</div>
<script type="text/javascript">
$(function(){
$(".search").keyup(function() { 
var searchid = $(this).val();
var dataString = 'search='+ searchid;
if( searchid.length > 1 ){
    $.ajax({
    type: "POST",
    url: "cherche.php",
    data: dataString,
    cache: false,
    success: function(html){
    $("#result").html(html).show();
    }
    });
}else { $("#result").hide()}return false;    
});
 
});

</script>
</body>
</html>