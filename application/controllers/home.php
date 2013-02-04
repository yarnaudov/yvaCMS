<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
    
    public function index()
    {        
        
        echo parent::_parseTemplateFile();
        //$this->load->view('../../templates/'.$this->template.'/index', isset($this->data) ? $this->data : '');
        
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
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */