<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller {

    public  $trl;
    
    function __construct() {
        
        parent::__construct();
        
        $this->load->model('Setting');
        
        /*
         * get current translation
         */
        $this->trl = $this->session->userdata('trl') == "" ? Language::getDefault() : $this->session->userdata('trl');
        $this->session->unset_userdata('trl');
        if(isset($_POST['translation'])){         
            $this->trl = $_POST['translation'];
            if(isset($_POST['uset_posts'])){                
                $this->input->post = array();
                $_POST = array();
            }
        }
        
        $this->load->helper('form');
        //$this->load->library('form_validation'); 
            
        //$this->jquery_ext->add_library("select_active_menu.js");
        
    }    
    
    public function _remap($method)
    {
        
        if ($method == 'index')
        {        
            $script = "$('#sub_actions li.first').attr('class', 'current')";
            $this->jquery_ext->add_script($script);
            $method = 'general';   
        }
        
        $this->$method();
        
    }
    
    public function general()
    {
        
        $this->load->helper('isJson');
        
        if(isset($_POST['save']) || isset($_POST['apply'])){ 
            
            $this->Setting->save();
            
            if(isset($_POST['save'])){
                redirect('');
            }
            elseif(isset($_POST['apply'])){
                /*
                 * save translation in cookie and use it to restore the correct translation
                 */
                $this->session->set_userdata('trl', $_POST['translation']);
                redirect('settings');                
            }
            exit();
            
        }
        
        $script = "$('select[name=translation]').bind('change', function(){
                       $('form').append('<input type=\"hidden\" name=\"uset_posts\" value=\"true\" >');
                       $('form').submit();
                   });";
        
        $this->jquery_ext->add_script($script);
        
        $data['settings'] = $this->Setting->getSettings();
        
        $content["content"] = $this->load->view('settings/index', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
    public function mail()
    {
        
        $this->load->helper('isJson');
        
        if(isset($_POST['save']) || isset($_POST['apply'])){ 
            
            $this->Setting->save();
            
            if(isset($_POST['save'])){
                redirect('');
            }
            elseif(isset($_POST['apply'])){
                redirect('settings/mail');
            }
            exit();
            
        }
        
        $data['settings'] = $this->Setting->getSettings();
        
        $content["content"] = $this->load->view('settings/mail', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */