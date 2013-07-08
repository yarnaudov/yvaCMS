<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_contact_form extends CI_Model{
	
    function run($module)
    {
	
	$this->jquery_ext->add_plugin('validation');
	$this->jquery_ext->add_library('check_captcha.js');
        $this->jquery_ext->add_library('../components/contact_forms/js/contacts.js');
	  	  
        $this->load->model('contact_forms/Contact_form');
        $this->load->language('components/com_cf');

        $data['contact_form'] = $this->Contact_form->getDetails($module['params']['contact_form_id']);
                       
        return module::loadContent($module, $data);
	  	
    }
    
}

