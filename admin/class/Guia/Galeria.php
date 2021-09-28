<?php

namespace Login\Guia;

use Login\App;
use \PDO;

class Galeria
{

    private $db;
    public function __construct()
    {
        $this->db = App::getDatabase();
        
    }


    ////// INDEX HISTORY
    public function history_index($id)
    {
        $history = $this->db->query("SELECT * FROM galeria WHERE post_id = ?", [$id])->fetchAll(PDO::FETCH_OBJ);
        return $history;
    }

    ////// INSERT PHOTO HISTORY
    public function insert_history($foto, $my_save_dir, $post_id, $size = 900)
    {
        $t = time();
        $resize = new Rezise_w();
        $image_pequena = "pequena_" . $post_id . "_" . $t . ".jpg";
        $image_grande = "grande_" . $post_id . "_ " . $t . ".jpg";
        $pequena = $resize->ResizImage("jpg", $foto, $my_save_dir, $image_pequena, "400");
        $grande = $resize->ResizImage("jpg", $foto, $my_save_dir, $image_grande, $size);

        //reduit image
        if ($pequena && $grande) {
            $insert_photo = $this->db->query("INSERT INTO galeria SET foto_grande=?, foto_pequena=?, post_id=?,date=NOW()", [$grande, $pequena, $post_id]);
            return  $insert_photo = true  ? true : false;
        } //peque/grande


    }

    ////// DELETE HISTORY 

    public function delete_history($foto_id, $my_save_dir, $grande, $pequena)
    {
        $secure = md5(time() + rand());
        $p = $my_save_dir . $pequena;
        $g = $my_save_dir . $grande;
        if (isset($secure) && $secure == $secure) {
            $result = $this->db->query("DELETE from galeria WHERE id=?", [$foto_id]);
           

            if (file_exists($g) && file_exists($p) && $result) {
                unlink($g);
                unlink($p);
                clearstatcache();
                return  $result = true  ? true : false;

            }
 
        }
    }
}
