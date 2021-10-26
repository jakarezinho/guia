<?php

namespace guia;

use \PDO;
use Login\App;

class Hastag
{

  private $db;
    public function __construct()
    {
        $this->db = App::getDatabase();
    }



  /// clean string ///
  public function clean($string)
  {
    //$string = str_replace(' ','',$string); 
    $string = strtr(utf8_decode($string), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    $string = strtolower($string); // Convert to lowercase
    return $string;
  }


  //// function cherche hastags ///

  public function cherche_hastag($hastag, $limite = 10)
  {

    $hastag_clean = $this->clean($hastag);
    $ch = $this->db->query("SELECT DISTINCT hastag FROM hastag WHERE hastag LIKE '%$hastag_clean%' AND public='1' ORDER BY id DESC  LIMIT $limite");
    if ($ch->rowCount() > 0) {
      $results = $ch->fetchAll(PDO::FETCH_OBJ);
      foreach ($results as $tag) {
        $username = $tag->hastag;

        $b_username =  '<a href="hastag.php?hastag=' . $hastag_clean . '"> ' . $hastag_clean . '</a>';

        $final_username = str_ireplace($hastag_clean, $b_username, $username);
        echo  '<li>' . $final_username . '</li>';
      }
    } else {
      echo "no found";
    }
  }


  
  /////GET HASTAGS/////
  public function gethashtags($text)
  {
    //Match the hashtags
    preg_match_all('/(^|[^a-z0-9_])#([a-z0-9_]+)/i', $text, $matchedHashtags);
    $hashtag = '';
    // For each hashtag, strip all characters but alpha numeric
    if (!empty($matchedHashtags[0])) {
      foreach ($matchedHashtags[0] as $match) {
        $hashtag .= preg_replace("/[^a-z0-9]+/i", "", $match) . ',';
      }
    }
    //to remove last comma in a string
    return rtrim($hashtag, ',');
  }


  /// CONVERT EM HASTAG ////
  public function convertHashtags($str, $url)
  {
    $regex = "/(\#)([^\s]+)/";
    $str = preg_replace($regex, '<a href="' . $url . '?hastag=$2">$0</a>', $str);
    return $str;
  }


  ///////////// CHERCHE pesquisa//////
  public function cherche($text, $url, $limite = 10)
  {

    $ch = $this->db->query("SELECT id, title, hastag FROM hastag WHERE message LIKE '%$text%' AND public='1' ORDER BY title  LIMIT $limite");
    if ($ch->rowCount() > 0) {
      $results = $ch->fetchAll(PDO::FETCH_OBJ);
      foreach ($results as $tag) {

        echo  "<li> <a href ='$url?id=$tag->id'>$tag->title</a> <br><small># $tag->hastag</small></li>";
      }
    } else {
      echo "Sem reultados!";
    }
  }

  ////
}
