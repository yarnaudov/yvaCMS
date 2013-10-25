<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller {
    
    public function index()
    {        
        
		# add comment to article
		if(isset($_POST['add_comment'])){
			$this->Article->addComment();			
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
    
    public function check_captcha($code = false)
    {
		
        $this->output->enable_profiler(FALSE);
		
        require_once BASEPATH . '../plugins/securimage/securimage.php';
		$image = new Securimage(array('case_sensitive' => false));

		# check via normal request
		if($code != false ){
			if ($image->check($code) == true) {
				return true;
			} else {
				return false;
			}			
		}
		
		# check via ajax without chengin key if correct
        if ($image->getCode() == strtolower($_POST['code'])) {
            echo 1;
        } else {
            echo lang('msg_captcha_code_err');
        }
        
        exit;
        
    }
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */