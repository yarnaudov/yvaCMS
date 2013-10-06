<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends MY_Controller {

    private $url;
    
    function __construct() {
        parent::__construct();
        
        $this->load->language('components/gallery');
        
        $this->load->model('Album');
        $this->load->model('Image');
                
        $this->jquery_ext->add_library('../components/gallery/js/gallery.js');
        //$this->jquery_ext->add_css('../components/gallery/css/gallery.css');
        
    }
    
    public function _remap($method,  $params = array())
    {        	
	
        if($this->uri->segment(2) == 'get_image'){
            $params[0] = $this->uri->segment(3);
            $params[1] = $this->uri->segment(4);
            $params[2] = $this->uri->segment(5);
            $this->get_image($params);
        }
        elseif(!method_exists($this, $method)){
            $this->index($method);
        }
        else{  
	    $this->$method($params);
	}
        
    }     
        
    public function index($url)
    {
        	
        $this->url = $url;
                
        echo parent::_parseTemplateFile();
        
    }
    
    public function getContent()
    {
	
	$templates = isset($this->Content->templates['gallery']) ? $this->Content->templates['gallery'] : array();
	
	$menu = $this->Menu->getDetails($this->menu_id);
        $menu_link = $this->Module->menu_link($menu);
        
        $url1 = explode(':', $this->url);
        
        $uri = str_replace($menu_link.'/', '', current_url());	
        $uri = explode('/', $uri);    
        
        if(current($url1) == 'album' || @$uri[0] == 'album'){
            
            if(isset($uri[0]) && $uri[0] == 'album'){
                $album_id  = $uri[1];
            }
            else{
                $album_id  = end($url1);
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
		
                return $this->load->view($view, compact('menu_link', 'images', 'url1'), true);
            }
            
        }
        elseif(current($url1) == 'albums'){
            
            if(end($url1) == '*'){
                $albums = $this->Album->getAlbums();                
            }
            else{
                $albums = end($url1);
                $albums = explode(',', $albums);
                foreach($albums as $key => $album){
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
    
    public function get_image($params)
    {

	$image_id = $params[0];
	$width    = isset($params[1]) ? $params[1] : null;
	$height   = isset($params[2]) ? $params[2] : null;
	
	$image_src = $this->Image->getImageUrl($image_id, $width, $height);

	$image = $this->Image->getDetails($image_id);
	$album = $this->Album->getDetails($image['album_id']);
	
	header('Content-type: image/png');
	
	if($album['params']['water_mark_type'] == ""){
	    echo file_get_contents($image_src);
	}
	elseif($album['params']['water_mark_type'] == "text"){
	    
	    $imagesize   = getimagesize($image_src);
	    $width       = $imagesize[0];
	    $height      = $imagesize[1];
	    $text        = $album['params']['water_mark_text'];
	    $font_size   = $album['params']['water_mark_size'];
	    $font_file   = $album['params']['water_mark_font'] == "" ? "fonts/arial.ttf" : $album['params']['water_mark_font'];
	    $angle       = 0;
	    
	    $bbox = imagettfbbox($font_size, 0, $font_file, $text);
	 
	    switch($album['params']['water_mark_position']){
		case 'top_left':
		    $margin_left = 10;
	            $margin_top  = $font_size+10;
		break;
	    
		case 'top_right':
		    $margin_left = $width - $bbox[2] -10; //$width  - ($font_size * mb_strlen($text));
	            $margin_top  = $font_size+10;
		break;
	    
		case 'bottom_left':
		    $margin_left = 10;
	            $margin_top  = $height -10;
		break;
	    
		case 'bottom_right':
		    $margin_left = $width - $bbox[2] -10;
	            $margin_top  = $height - 10;
		break;
	    
	    }

	    $image = self::_load_image($image_src);
	    
	    $text_color = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);//text color-white

	    imagefttext($image, $font_size, $angle, $margin_left, $margin_top, $text_color, $font_file, $text);
	    
	    imagepng($image);
	    imagedestroy($image);
	
	}
	elseif($album['params']['water_mark_type'] == "image"){
	    
	    if($album['params']['water_mark_image'] == ""){
		echo file_get_contents($image_src);
		exit;
	    }
	    
	    $imagesize = getimagesize($image_src);
	    $width     = $imagesize[0];
	    $height    = $imagesize[1];

	    $logo = self::_load_image($album['params']['water_mark_image']);
	    
	    imagecolortransparent($logo, imagecolorat($logo, 0, 0));
	    $logo_x = imagesx($logo);
	    $logo_y = imagesy($logo);
		    
	    switch($album['params']['water_mark_position']){
		case 'top_left':
		    $margin_left = 10;
	            $margin_top  = 10;
		break;
	    
		case 'top_right':
		    $margin_left = $width  - ($logo_x+10);
	            $margin_top  = 10;
		break;
	    
		case 'bottom_left':
		    $margin_left = 10;
	            $margin_top  = $height - ($logo_y+10);
		break;
	    
		case 'bottom_right':
		    $margin_left = $width  - ($logo_x+10);
	            $margin_top  = $height - ($logo_y+10);
		break;
	    
	    }	    

	    $image = self::_load_image($image_src);

	    //$margin_left = $width  - ($logo_x+10);
	    //$margin_top  = $height - ($logo_y+10);

	    imagecopymerge($image, $logo, $margin_left, $margin_top, 0, 0, $logo_x, $logo_y, 100);

	    imagejpeg($image);
	    imagedestroy($image);
	    
	}
	
	exit;
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
    
    public function getRoute($menu)
    {

        if(isset($menu['params']['album_id'])){
            return $menu['params']['component']."/album:".$menu['params']['album_id']."/$2";
        }
        elseif($menu['params']['albums'][0] == "*"){
            return $menu['params']['component']."/albums:*$2";
        }
        elseif(count($menu['params']['albums']) == 1){
            return $menu['params']['component']."/album:".$menu['params']['albums'][0]."/$2";
        }
        else{
            $albums = implode(",", $menu['params']['albums']);
            return $menu['params']['component']."/albums:".$albums."/$2";
        }
                
    }
    
}