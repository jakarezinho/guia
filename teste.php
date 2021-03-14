<?php 

  function clean($string) {
    //$string = str_replace(' ','',$string); 
    $string = strtr(utf8_decode($string), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    $string = strtolower($string); // Convert to lowercase
    return $string;
  }
//procura hastags////
function gethashtags($text){
  //Match the hashtags
   preg_match_all('/(^|[^a-z0-9_])#([a-z0-9_àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ]+)/i', $text, $matchedHashtags);
  $hashtag = '';
  // For each hashtag, strip all characters but alpha numeric
  if(!empty($matchedHashtags[0])) {
    foreach($matchedHashtags[0] as $match) {
      $hashtag .= preg_replace("/[^a-z0-9_àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ]+/i", "", $match).',';
    }
  }
    //to remove last comma in a string
$tag= rtrim($hashtag, ',');
//return $tag;
return clean($tag);
}

///
function convertHashtags($str,$url){
	$regex = "/(\#)([^\s]+)/";
	$str = preg_replace($regex, '<a href="'.$url.'?tag=$2">$0</a>',$str);
	return $str;
}


$t ="asasa Alcobaça alcobaça   leão #alcobaça #ALCOBAÇA #alcobaçanovo";

$r =gethashtags($t);
var_dump($r);
$c =convertHashtags($t,'hastag.php');
var_dump($c);


//$t=  preg_replace('/(\#)([^\s]+)/', '<a href="tag/$2">$2</a>', $t);

//var_dump($t)
 ?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
  <script> 
    var n = Math.sqrt('0.533232353213');

  console.log(n);
  </script>
	<?php echo json_encode([]) ?>
</body>


</body>

</html>