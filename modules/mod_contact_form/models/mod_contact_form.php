<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_contact_form extends CI_Model{
	
    function run($module)
    {
	
		$this->load->language('components/com_cf');
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		$this->load->add_package_path(COMPONENTS_DIR.'/contact_forms/');
		$this->load->model('Contact_forms');
		$this->load->language('components/com_cf');
		
		if($this->input->get_post('contact_form_id')){
			$contact_form_id = $this->input->get_post('contact_form_id');
		}
		else{
			$contact_form_id = $module['params']['contact_form_id'];
		}
		
		$contact_form = $this->Contact_forms->getDetails($contact_form_id);

		if($this->input->post('send')){
            $this->Contact_forms->send($contact_form);
        }

		$this->jquery_ext->add_plugin('validation');
		$this->jquery_ext->add_library('check_captcha.js');
        $this->jquery_ext->add_library('../components/contact_forms/js/contacts.js');
	
        $data['contact_form'] = $contact_form;
                       
        return module::loadContent($module, $data);
	  	
    }
    
}

