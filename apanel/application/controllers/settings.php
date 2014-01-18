<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller {

    public  $trl;
    
    private $sub_menu;
    
    function __construct() 
    {
        
        parent::__construct();
        
        $this->load->model('Setting');
        
        $this->load->helper('form');
        
        $this->templates = parent::_getTemplates();
        
    }    
    
    public function _remap($method)
    {
        
        if ($method == 'index')
        {        
            $script = "$('#sub_actions li.first').attr('class', 'current')";
            $this->jquery_ext->add_script($script);
            $method = 'general';   
        }
        
        // create sub actions menu
        $parent_id = $this->Ap_menu->getDetails($this->current_menu, 'parent_id');
        if(empty($parent_id)){
            $parent_id = $this->current_menu;
        }
        $this->sub_menu = $this->Ap_menu->getSubActions($parent_id);        
        $current_key = key($this->sub_menu);
        unset($this->sub_menu[$current_key]);
        
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
        $data['sub_menu'] = $this->sub_menu;
        
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
        $data['sub_menu'] = $this->sub_menu;
        
        $content["content"] = $this->load->view('settings/mail', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
    public function database()
    {     
        
        if(isset($_POST['save']) || isset($_POST['apply'])){ 
            
            $settings['DB_HOST'] = $this->input->post('db_host');
            $settings['DB_NAME'] = $this->input->post('db_name');
            $settings['DB_USER'] = $this->input->post('db_user');
            $settings['DB_PASS'] = $this->input->post('db_pass');
            $settings['DB_TYPE'] = $this->input->post('db_type');
        
            if($settings['DB_TYPE'] == 'mysqli'){
                $db_test = @mysqli_connect($settings['DB_HOST'], $settings['DB_USER'], $settings['DB_PASS'], $settings['DB_NAME']);
            }
            else{
                $db_test = @mysql_connect($settings['DB_HOST'], $settings['DB_USER'], $settings['DB_PASS'], $settings['DB_NAME']);
            }

            if($db_test == false){
                $this->session->set_userdata('error_msg', lang('msg_save_db_settings_error'));
            }
            else{                
                
                $file = file_get_contents(FCPATH . '../settings.php');
                foreach($settings as $name => $value){
                    $file = preg_replace("/$name', '(.*)'/", "$name', '$value'", $file);
                }
                
                if(file_put_contents(FCPATH . '../settings.php', $file) == false){
                    $this->session->set_userdata('error_msg', lang('msg_save_settings_error'));
                }
                else{
                    $this->session->set_userdata('good_msg', lang('msg_save_settings'));
                }
                
                if(isset($_POST['save'])){
                    redirect('');
                }
                elseif(isset($_POST['apply'])){
                    redirect('settings/database');
                }
                exit();
            }
            
        }
        
        $data['sub_menu'] = $this->sub_menu;
        
        $content["content"] = $this->load->view('settings/database', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */