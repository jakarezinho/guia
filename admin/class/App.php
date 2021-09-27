<?php

namespace Login;

class App
{


    const DB_NAME = "guia";
    const DB_USER = "root";
    const DB_PASS = "";


    static $db = null;

    static function getDatabase()
    {
        if (!self::$db) {
            self::$db = new Database(self::DB_USER , self::DB_PASS, self::DB_NAME);
        }
        return self::$db;
    }

    static function getAuth()
    {
        return new Auth(Session::getInstance(), ['restriction_msg' => 'De volta']);
    }

    static function redirect($page)
    {
        header("Location: $page");
        exit();
    }
}
