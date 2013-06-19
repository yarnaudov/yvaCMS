<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics extends MY_Controller {

    public  $extension;
    public  $page; 
    
    function __construct()
    {
  	
        parent::__construct();
        
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->extension = $this->uri->segment(3);
        
    }
    
    public function _remap($method)
    {
        
        if(!method_exists($this, $method)){
            $this->extension = $method;
            $method = 'index';
        }
        
        $this->jquery_ext->add_library("select_active_menu.js");
        $this->$method();

    }
    
    public function index()
    {
                
        // create sub actions menu
        $parent_id = $this->Ap_menu->getDetails($this->current_menu, 'parent_id');
        $data['sub_menu'] = $this->Ap_menu->getSubActions($parent_id);
        
        
        $content = $this->load->view('articles/statistics', $data, true);
        $this->load->view('layouts/default', compact('content'));
        
    }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */