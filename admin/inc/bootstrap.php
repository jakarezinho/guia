<?php
/*
spl_autoload_register('app_autoload');

function app_autoload($class){
    require "class/$class.php";
	var_dump($class);
}
*/
namespace Login;
class Autoloader{

    /**
     * Enregistre notre autoloader
     */
    static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Inclue le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
	
    static function autoload($class){
        if (strpos($class, __NAMESPACE__ . '\\') === 0){
            $class = str_replace(__NAMESPACE__ . '\\', '', $class);
            $class = str_replace('\\', '/', $class);
			require 'class/' . $class . '.php';
			
        }
    }
}