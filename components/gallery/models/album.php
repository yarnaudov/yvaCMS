<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Album extends CI_Model {

    public function getDetails($id, $field = null)
    {

        $query = "SELECT 
                      *
                    FROM
                      com_gallery_albums cga
                      LEFT JOIN com_gallery_albums_data cgad ON (cga.id = cgad.album_id AND cgad.language_id = '".$this->language_id."')
                    WHERE
                      cga.id = '".$id."' ";
        
        $album = $this->db->query($query);  	
        $album = $album->result_array();

        if(empty($album)){
            return;
        }

        if($field == null){
            return $album[0];
        }
        else{  	
            return $album[0][$field];
        }

    }
  
    public function getAlbums($filters = array(), $order_by = "", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }     
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND ( title like '%".$value."%' OR description like '%".$value."%' ) ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        com_gallery_albums cga
                        LEFT JOIN com_gallery_albums_data cgad ON (cga.id = cgad.album_id AND cgad.language_id = '".$this->language_id."')
                    WHERE
                        cga.id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $albums = $this->db->query($query)->result_array();

        return $albums;

    }
    
    public function getImage($id)
    {
        
        $this->db->select('*');
        $this->db->where('album_id', $id);
        $this->db->order_by('`order`');

        $image = $this->db->get('com_gallery_images');  	
        $image = $image->result_array();
	
        return $image[0];
        
    }
    
}