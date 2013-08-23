<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller {

    
    function __construct() {
        
        parent::__construct();
        
        $this->load->language('components/search');
        
        if(!isset($_GET['query'])){
            redirect(current_url().'?query='.urlencode($this->input->post('search_v')));
        }
        
    }
    
    function _remap($method)
    {
    
        if($method == ""){
            $method = "index";
        }
    	  
        $this->$method();
    	  
    }
    
    public function index()
    {
                
        echo parent::_parseTemplateFile();
        
    }
    
    public function getContent()
    {
	
	$templates = isset($this->Content->templates['search']) ? $this->Content->templates['search'] : array();
	
	$query = $this->input->get('query', TRUE);
        $articles = $this->Article->search($query);
	
	$view = 'search';
	if(isset($templates['search'])){
	    $view = $templates['search'];
	}
	
        return $this->load->view($view, compact('query', 'articles'), true);
	
    }
    
    public function getRoute($menu)
    {
        
        return $menu['params']['component']."/$2";
                
    }
    
}