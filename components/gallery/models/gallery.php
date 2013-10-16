<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Model {
    
    public function run($data)
    {
        	
	$this->load->language('components/gallery');
	$this->load->model('Album');
        $this->load->model('Image');
	 
        $this->jquery_ext->add_library('../components/gallery/js/gallery.js');
        
	$templates = isset($this->Content->templates['gallery']) ? $this->Content->templates['gallery'] : array();
	
	$menu = $this->Menu->getDetails($this->menu_id);
        $menu_link = $this->Module->menu_link($menu);
        
        $uri = str_replace($menu_link.'/', '', current_url());	
        $uri = explode('/', $uri);    
        
        if(key($data) == 'album_id' || $uri[0] == 'album'){
            
            if(isset($uri[0]) && $uri[0] == 'album'){
                $album_id  = $uri[1];
            }
            else{
                $album_id  = $data['album_id'];
                $image = $this->uri->segment(2);
            }
                        
            $images = $this->Image->getImages(array('album_id' => $album_id));
            
            if( (isset($uri[0]) && $uri[0] == 'image') || (isset($uri[2]) && $uri[2] == 'image') ){
		
                $image_id  = isset($uri[3]) ? $uri[3] : $uri[1]; 
                $image     = $this->Image->getDetails($image_id);
                $image_key = array_search($image, $images);
		$url2      = array($uri[0], $uri[1]);
		
		$view = 'image';
		if(isset($templates['image'])){
		    $view = $templates['image'];
		}
		
                return $this->load->view($view, compact('menu_link', 'images', 'image_id', 'image', 'image_key', 'url2'), true);
            }
            else{   
		
		$view = 'images';
		if(isset($templates[$view])){
		    $view = $templates[$view];
		}
		
		$url1 = $data;
		
                return $this->load->view($view, compact('menu_link', 'images', 'url1'), true);
            }
            
        }
        elseif(key($data) == 'albums'){
            
            if($data['albums'][0] == '*'){
                $albums = $this->Album->getAlbums();                
            }
            else{
                foreach($data['albums'] as $key => $album){
                    $albums[$key] = $this->Album->getDetails($album);
                }
            }
            
	    $view = 'albums';
	    if(isset($templates[$view])){
		$view = $templates[$view];
	    }

            return $this->load->view($view, compact('menu_link', 'albums'), true);
            
	}
	
    }
    
    public function get_image($image_id, $width = null, $height = null)
    {
	
	$image = $this->Image->getDetails($image_id);
	$album = $this->Album->getDetails($image['album_id']);
	
	if($album['params']['water_mark_type'] == ""){
	    return $this->Image->getImageUrl($image_id, $width, $height);
	}
	
	$image_dir = 'images/'.$width.'x'.$height.'/';
	if(file_exists(FCPATH . $image_dir . $image_id . '.' . $image['ext'])){
	    return $this->Image->getImageUrl($image_id, $width, $height);
	}
	    
	$this->load->library('image_lib');
	$config['source_image'] = $this->Image->getImageUrl($image_id, $width, $height, true);
	$config['create_thumb'] = FALSE;
	$config['wm_padding']   = '0';
	
	switch($album['params']['water_mark_position']){
	    case 'top_left':
		$config['wm_vrt_alignment'] = 'top';
		$config['wm_hor_alignment'] = 'left';
	    break;

	    case 'top_right':
		$config['wm_vrt_alignment'] = 'top';
		$config['wm_hor_alignment'] = 'right';
	    break;

	    case 'bottom_left':
		$config['wm_vrt_alignment'] = 'bottom';
		$config['wm_hor_alignment'] = 'left';
	    break;

	    case 'bottom_right':
		$config['wm_vrt_alignment'] = 'bottom';
		$config['wm_hor_alignment'] = 'right';
	    break;

	}

	if($album['params']['water_mark_type'] == "text"){
	    
	    $config['wm_text']          = $album['params']['water_mark_text'];
	    $config['wm_type']          = 'text';
	    $config['wm_font_path']     = $album['params']['water_mark_font'] == "" ? "fonts/arial.ttf" : $album['params']['water_mark_font'];
	    $config['wm_font_size']     = $album['params']['water_mark_size'];
	    $config['wm_font_color']    = 'ffffff';
	    

	    $this->image_lib->initialize($config);
	    $this->image_lib->watermark();
	    
	}
	elseif($album['params']['water_mark_type'] == "image"){
	    
	    if($album['params']['water_mark_image'] == ""){
		return $this->Image->getImageUrl($image_id, $width, $height);
	    }
	    
	    $config['wm_type']         = 'overlay';
	    $config['wm_overlay_path'] = $album['params']['water_mark_image'];

	    $this->image_lib->initialize($config);
	    $this->image_lib->watermark();
	    
	}
	
	return $this->Image->getImageUrl($image_id, $width, $height);
	
    }
    
    private function _load_image($image_src)
    {
	
	$image_type = exif_imagetype($image_src);
	    
	if($image_type == IMAGETYPE_JPEG){
	    return imagecreatefromjpeg($image_src);
	}
	elseif($image_type == IMAGETYPE_PNG){
	    return imagecreatefrompng($image_src);
	}
	elseif($image_type == IMAGETYPE_GIF){
	    return imagecreatefromgif($image_src);
	}
	
    }
    
}