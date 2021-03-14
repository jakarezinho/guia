<?php 
namespace Login\Guia;
class Divers {
	
////function total articles///
	public function total ($db,$public,$text=false){
        if ($text){

            $total= $db->query("SELECT * FROM hastag WHERE public = $public AND message LIKE '%$text%'");
            
        }else { $total= $db->query("SELECT * FROM hastag WHERE public=$public"); }

        $nbArt= $total->rowCount();
        return $nbArt;
    }


	////function nombre de pages///
    public function nb_Pages($nbArt,$perPage =25){
     $nbPages = ceil($nbArt / $perPage);
     return $nbPages;
 }




		///function articles///>articles ($db,$total,$perPage,$Cpage,$nbPages,$public);
 public function articles ($db,$perPage =25,$Cpage,$public){

   $paged = ($Cpage-1) * $perPage;
   $r= $db->query("SELECT * FROM hastag WHERE public=$public ORDER BY id DESC  LIMIT $paged ,$perPage");
   return $r;
}

	///function paged///
public function paginate ($nbPages,$Cpage,$url){
    for ($i=1; $i<=$nbPages; $i++){
       if ($i==$Cpage) { echo "$i /";
   }else {
    echo "<a class='paged' href='$url?p=$i'>$i</a> / ";
}
}

}

//function public ////
public function publicado ($public){
	if ($public=="1")
        {return "publicadas";}
        elseif ($public== "0") {
           return "Não publicado";
        }elseif($public=="non"){
          return "sem recomendação";
        }elseif ($public=="yes"){
          return "recomendado";
        }
  }


////function paginate ////
  public function paginate_num($item_per_page, $current_page, $total_records, $total_pages, $page_url){
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination">';

        $right_links    = $current_page + 3;
        $previous       = $current_page - 3; //previous link
        $next           = $current_page + 1; //next link
        $first_link     = true; //boolean var to decide our first link

        if($current_page > 1){
            $previous_link = $current_page - 1;
            $pagination .= '<li class="first page-item"><a class="page-link" href="'.$page_url.'?p=1" title="First">&laquo;</a></li>'; //first link
            $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'?p='.$previous_link.'" title="Previous">&larr;</a></li>'; //previous link
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li class="page-item"><a class="page-link"  href="'.$page_url.'?p='.$i.'">'.$i.'</a></li>';
                    }
                }  
            $first_link = false; //set first link to false
        }

        if($first_link){ //if current active page is first link
            $pagination .= '<li class="active page-item"><a class="page-link" href="#">'.$current_page.'</a></li>';
        }elseif($current_page == $total_pages){ //if it's the last active link
        $pagination .= '<li class="active page-item"><a class="page-link" href="#">'.$current_page.'</a></li>';
        }else{ //regular current link
            $pagination .= '<li class="active page-item"><a class="page-link"  href="#">'.$current_page.'</a></li>';
        }

        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li class="page-item"><a class="page-link"  href="'.$page_url.'?p='.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){
            $next_link = $current_page + 1;
                $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'?p='.$next_link.'" > &rarr;</a></li>'; //next link
                $pagination .= '<liv class="last"><a class="page-link"  href="'.$page_url.'?p='.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
            }

            $pagination .= '</ul>';
        }
    return $pagination; //return pagination links
}

///////////QUERY EDITE FOTO ////////

public function select_id ($db,$id){

   $query =$db->query("SELECT * FROM hastag WHERE id='$id'");
   return $query;
}

/////// PESQUISA GERAL  ////////
public function cherche($db,$text,$perPage =25,$Cpage,$public){

   $paged = ($Cpage-1) * $perPage;
   $r= $db->query("SELECT * FROM hastag  WHERE public=$public AND message LIKE '%$text%' ORDER BY title LIMIT $paged ,$perPage");
   return $r;
}

	}//class
