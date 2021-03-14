<?php 
namespace guia;
class App{

const DB_NAME = "guia";
const DB_USER = "root";
const DB_PASS = "";

    static $db = null;

    static function getDatabase(){
        if(!self::$db){
            self::$db = new BaseGuia(self::DB_USER, self::DB_PASS, self::DB_NAME);
        }
        return self::$db;
    }

}
