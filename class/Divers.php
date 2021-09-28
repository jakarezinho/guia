<?php

namespace guia;

use \PDO;

class Divers
{


	private $db;
	public function __construct()
	{
		$this->db = App::getDatabase();
	}

	//// GET PHOTO

	public function foto($local_id)
	{
		$n = $this->db->query("SELECT * FROM hastag WHERE id= '$local_id'  AND public='1'");
		return $n;
	}



	////TOTAL ARTICLES
	public function total($hastag = false, $refer = false)
	{
		if ($hastag && $refer) {
			$total = $this->db->query("SELECT * FROM hastag  WHERE hastag LIKE '%$hastag%' AND hastag LIKE '%$refer%'  AND public='1' ");
		} elseif ($hastag) {
			$total = $this->db->query("SELECT * FROM hastag  WHERE hastag LIKE '%$hastag%' AND public='1'");
		} else {
			$total = $this->db->query("SELECT * FROM hastag WHERE public='1'");
		}
		return $total->rowCount();
	}




	/////// TOTAL ARTICLES

	public function articles($perPage = 25, $Cpage, $hastag = false, $refer = false)
	{
		$paged = ($Cpage - 1) * $perPage;
		if ($hastag && $refer) {
			$articles = $this->db->query("SELECT * FROM hastag WHERE hastag LIKE '%$hastag%' AND hastag LIKE '%$refer%'  AND public='1' ORDER BY id DESC  LIMIT $paged ,$perPage");
		} elseif ($hastag) {
			$articles = $this->db->query("SELECT  * FROM hastag WHERE hastag LIKE '%$hastag%' AND public='1' ORDER BY id DESC  LIMIT $paged ,$perPage");
		} else {
			$articles = $this->db->query("SELECT  * FROM hastag WHERE public='1' ORDER BY id DESC  LIMIT $paged ,$perPage");
		}

		return $articles;
	}

	/// PAGINATE 
	public function paginate($nbPages, $Cpage, $url)
	{
		for ($i = 1; $i <= $nbPages; $i++) {
			if ($i == $Cpage) {
				echo "$i /";
			} else {
				echo "<a class='paged' href='$url?p=$i'>$i</a>";
			}
		}
	}


	//// MUMERO DE PAGES
	public function nb_Pages($nbArt, $perPage = 25)
	{
		$nbPages = ceil($nbArt / $perPage);
		return $nbPages;
	}


	/// PAGE PAGED
	public function page_page($p, $nbPages)
	{
		if (isset($p)  && $p > 0 && $p <= $nbPages) {
			return $page = $p;
		} else {
			return $page = 1;
		}
	}



	/// LINKS HASTAGS
	public function hastag_links($url, $hastag = false, $refer = false, $limite = 100)
	{
		if ($hastag) {
			$h = $this->db->query("SELECT DISTINCT hastag FROM hastag WHERE hastag LIKE '%$hastag%' AND public='1' ORDER BY hastag ASC LIMIT $limite ");
		} else {
			$h = $this->db->query("SELECT DISTINCT hastag FROM hastag WHERE  hastag !='' AND public='1' ORDER BY hastag ASC LIMIT $limite ");
		}
		if ($h->rowCount() > 0) {
			$nota_hastag = $h->fetchAll(PDO::FETCH_OBJ);
			foreach ($nota_hastag as $item) {
				$states[] = $item->hastag;
			}
			////
			$s = array_unique($states);
			$str = implode(' ', $states);
			$word_array = preg_split('/[\s?:;,.]+/', $str, -1, PREG_SPLIT_NO_EMPTY);
			$unique_word_array = array_unique($word_array);
			foreach ($unique_word_array as $value) {

				$corrent = $value == $hastag ? 'visite' : '';
				if ($refer) {
					echo "<li class='$corrent'><a class='paged' href='$url?hastag=$value&refer=$refer'>$value</a>" . " " . " </li>";
				} else {
					echo "<li class='$corrent'><a class='paged' href='$url?hastag=$value'>$value</a>" . " " . " </li>";
				}
			}
		}
	}



	////REFER LINK

	public function refer($link)
	{
		$states = explode('guia', $link, -1);
		$str = implode(' ', $states);
		if (isset($link) && $str == "https://www.pequeno.eu/") {
			return true;
		} else {
			return false;
		}
	}


	//////DETECT URL 

	public function detect($prov, $id = null)
	{
		if ($prov == "foto") {
			$url = "https://www.pequeno.eu/guia/foto.php?id=$id";
		} else if ($prov == "hastag") {
			$url = "https://www.pequeno.eu/guia/hastag.php?hastag=$id";
		} else {
			$url = "https://www.pequeno.eu/guia/";
		}
		return trim($url);
	}


	///// TOTAL LOCAIS POR PERTO

	public  function total_porperto($lat, $lng, $radius)
	{
		$total_perto = $this->db->query("SELECT id,( 6371 * acos( cos( radians('$lat') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$lng') ) + sin( radians('$lat') ) * sin( radians( lat ) ) ) ) AS distance FROM hastag WHERE public='1' HAVING distance < '$radius' ORDER BY distance")->rowCount();
		return $total_perto;
	}


	///lOCAIS POR PERTO
	public function porperto($lat, $lng, $radius, $limite = 5)
	{
		$perto = $this->db->query("SELECT  *,( 6371 * acos( cos( radians('$lat') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$lng') ) + sin( radians('$lat') ) * sin( radians( lat ) ) ) ) AS distance FROM hastag WHERE public='1' HAVING distance < '$radius' ORDER BY distance LIMIT 0 , $limite");
		return $perto;
	}

	///LOCAIS POR PERTO PAGINATION 
	public function porperto_feed($lat, $lng, $radius, $perpage, $page)
	{
		$paged = ($page - 1) * $perpage;
		$perto_feed = $this->db->query("SELECT  *,( 6371 * acos( cos( radians('$lat') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$lng') ) + sin( radians('$lat') ) * sin( radians( lat ) ) ) ) AS distance FROM hastag WHERE public='1' HAVING distance < '$radius' ORDER BY distance LIMIT $paged ,$perpage");
		return $perto_feed;
	}

	//////POR PERTO JSON //////

	public function porperto_json($lat, $lng, $radius, $limite = 5)
	{
		$p = $this->db->query("SELECT  *,( 6371 * acos( cos( radians('$lat') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$lng') ) + sin( radians('$lat') ) * sin( radians( lat ) ) ) ) AS distance FROM hastag WHERE public='1' HAVING distance < '$radius' ORDER BY distance LIMIT 0 , $limite");
		$array = $p->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($array);
	}

	//// IMAGE UTILITIES

	public function img_utilities($img)
	{
		return  getimagesize($img);
	}
}//class
