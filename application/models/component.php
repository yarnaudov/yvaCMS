<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Component extends CI_Model { 
    
    public function run($component, $data = array())
    {
	
	$this->load->add_package_path(COMPONENTS_DIR.'/'.$component.'/');	
	$this->load->model($component);
	
	return $this->{$component}->run($data);
    }
    
}