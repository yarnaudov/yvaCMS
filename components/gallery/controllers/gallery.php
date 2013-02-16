<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends MY_Controller {

    
    function __construct() {
        parent::__construct();
        
        $this->load->language('gallery');
        
        $this->load->model('Album');
        $this->load->model('Image');
                
        $this->jquery_ext->add_library('../components/gallery/js/gallery.js');
        $this->jquery_ext->add_css('../components/gallery/css/gallery.css');
        
    }
    
    public function _remap($method)
    {        
        if(!method_exists($this, $method)){
            $url = $method;
            $method = 'index';
        }
                
        $this->$method($url);
        
    }
    
    public function index($url)
    {
        
        $menu = $this->Menu->getDetails($this->menu_id);
        $menu_link = $this->Module->menu_link($menu);
        
        $url1 = explode(':', $url);
        
        $uri = str_replace($menu_link.'/', '', current_url());        
        $uri = explode('/', $uri);
        
        $url2 = current($uri);
        $url2 = explode(':', $url2);     
        
        if(current($url1) == 'album' || current($url2) == 'album'){
            
            if(current($url2) == 'album'){
                $album_id  = end($url2);
                $image = next($uri);
            }
            else{
                $album_id  = end($url1);
                $image = $this->uri->segment(2);
            }
            
            $image = explode(':', $image);
            
            $images = $this->Image->getImages(array('album_id' => $album_id), '`order`');
            
            if(current($image) == 'image'){
                $image_id  = end($image); 
                $image     = $this->Image->getDetails($image_id);
                $image_key = array_search($image, $images);
                $this->data['content'] = $this->load->view('image', compact('menu_link', 'images', 'image_id', 'image', 'image_key', 'url2'), true);
            }
            else{                
                $this->data['content'] = $this->load->view('images', compact('menu_link', 'images', 'url1'), true);
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
            
            $this->data['content'] = $this->load->view('albums', compact('menu_link', 'albums'), true);
            
        }
                
        echo parent::_parseTemplateFile();
        
    }
    
    public function getRoute($menu)
    {

        if($menu['params']['albums'][0] == "*"){
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