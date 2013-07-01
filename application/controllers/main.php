<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller {
    
    public function index()
    {        
        
	if(isset($_POST['add_comment'])){
	    //echo "add comment to article";
	    $this->Article->addComment();
	    exit;
	}
	
        echo parent::_parseTemplateFile();
        
    }
    
    public function load($type, $id)
    {
        
        switch($type){
            
            case 'module':
                $content = Module::_load_module($id);
            break;
        
            case 'article':
                $content = Article::_load_article($id);
            break;
        
        }
        
        $this->load->view('layouts/simple_ajax', compact('content'));
        
    }
    
    public function ajax($action)
    {
        
        switch($action){
            
            case "load":
                
                $this->load->view($this->input->get('view'));
                
            break;
                    
        }
        
    }
    
    public function banners($id)
    {

	$this->Banner->statistic($id, 2);
	
	redirect($this->input->get('url'));
	
    }
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */