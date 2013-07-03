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
        
        $query = $_GET['query'];
        
        $articles = $this->Article->search($query);
        
        $this->data['content'] = $this->load->view('search', compact('query', 'articles'), true);
                
        echo parent::_parseTemplateFile();
        
    }
    
    public function getRoute($menu)
    {
        
        return $menu['params']['component']."/$2";
                
    }
    
}