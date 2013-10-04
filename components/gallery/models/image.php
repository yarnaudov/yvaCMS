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
                      cgi.id = '".$id."'
		     AND
		      cgi.status = 'yes'";
        
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
  
    public function getImages($filters = array(), $order_by = "`order`", $limit = "")
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
    
    public function getImageUrl($id, $width = null, $height = null)
    {
        
        $ext = self::getDetails($id, 'ext');
        $image_dir = 'images/'.$width.'x'.$height.'/';
    
	if($width == null || $height == null){
	    return base_url('images/'.$id . '.' . $ext);
	}
        elseif(file_exists(FCPATH . $image_dir . $id . '.' . $ext)){
            
            return base_url($image_dir .  $id . '.' . $ext);
            
        }
        else{
            
            if(!file_exists(FCPATH . $image_dir)){
                mkdir(FCPATH . $image_dir, 0777);
		copy(BASEPATH."/index.html", FCPATH . $image_dir."/index.html");
            }
            
            $config['image_library']  = 'gd2';
            $config['source_image']   = FCPATH . 'images/'.$id.'.'.$ext;
            $config['create_thumb']   = TRUE;
            $config['thumb_marker']   = $width.'x'.$height.'/';
            $config['maintain_ratio'] = TRUE;
            $config['width']	      = $width;
            $config['height']	      = $height;

            
            $this->load->library('image_lib');
	    $this->image_lib->initialize($config);
            $this->image_lib->resize();
            
            return base_url($image_dir .  $id . '.' . $ext);
            
        }
        
    }
    
}