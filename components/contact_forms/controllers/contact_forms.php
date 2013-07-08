<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_forms extends MY_Controller {

    public $data;
    
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Contact_form');
        
        $this->load->language('components/com_cf');
        
        
        $this->jquery_ext->add_plugin('validation');
	$this->jquery_ext->add_library('check_captcha.js');
        $this->jquery_ext->add_library('../components/contact_forms/js/contacts.js');
        //$this->jquery_ext->add_css('../components/contact_forms/css/contacts.css');
        
        
        /*
         * load validation library language file
         */
        $file = 'validation/localization/messages_'.get_lang().'.js';
        if(file_exists('js/'.$file)){
            $this->jquery_ext->add_library($file);
        }
        
        
        /*
         * Load jquery_ui and search for .datepicker
         */
        $this->jquery_ext->add_plugin('jquery_ui');
        $script = "$('.datepicker').datepicker({
                        showOn: 'button',
                        dateFormat: 'yy-mm-dd',
                        buttonImage: '".base_url('components/contact_forms/img/iconCalendar.png')."',
                        buttonImageOnly: true,              
                        buttonText: '".lang('label_cf_select_date')."'
                    });";
        $this->jquery_ext->add_script($script);
        
    }
    
    public function _remap($method)
    {
        
        if(!method_exists($this, $method)){
            $contact_form_id = $method;
            $method = 'index';
        }
                
        $this->$method(isset($contact_form_id) ? $contact_form_id : '');
        
    }
    
    public function index($contact_form_id)
    {
   	
        if(isset($_POST['send'])){
            $this->Contact_form->send($contact_form_id);
        }
        
        
        $contact_form = $this->Contact_form->getDetails($contact_form_id);
        
        $this->data['content'] = $this->load->view('contact_form', compact('contact_form'), true);
        
        echo parent::_parseTemplateFile();
        
    }
    
    public function getRoute($menu)
    {
        
        return $menu['params']['component']."/".$menu['params']['contact_form_id'];
                
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */