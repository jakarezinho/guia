<?php 
use \guia\Autoloader;
use \guia\App;
use \guia\factory;
require 'class/Autoloader.php'; 
Autoloader::register(); 

$db= App::getDatabase();

$d = new factory($db);
var_dump($d->teste()->fetchAll());

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

</body>
</html>