<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image extends CI_Model {

    public function getDetails($id, $field = null)
    {

        $query = "SELECT 
                      *
                    FROM
                      com_gallery_images cgi
                      LEFT JOIN com_gallery_images_data cgid ON (cgi.id = cgid.image_id AND cgid.language_id = '".$this->language_id."')
                    WHERE
                      cgi.id = '".$id."' ";
        
        $image = $this->db->query($query);  	
        $image = $image->result_array();

        if(empty($image)){
            return;
        }

        if($field == null){
            return $image[0];
        }
        else{  	
            return $image[0][$field];
        }

    }
  
    public function getImages($filters = array(), $order_by = "", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }
        
        if(substr_count($order_by, 'order')){
            $order_by = "album_id, ".$order_by;
        }      
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND ( title like '%".$value."%' OR description like '%".$value."%' ) ";
            }
            elseif($key == 'album'){
                $filter .= " AND album_id = '".$value."' ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        com_gallery_images cgi
                        LEFT JOIN com_gallery_images_data cgid ON (cgi.id = cgid.image_id AND cgid.language_id = '".$this->language_id."')
                    WHERE
                        cgi.id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $images = $this->db->query($query)->result_array();

        return $images;

    }
    
}