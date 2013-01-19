<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_language_switch extends CI_Model{
	
	  function run($module)
	  {
	  	
	  	  $data['module']    = $module;
        $data['languages'] = $this->Language->getAll();
        
        return module::loadContent($module, $data);
	  	
	  }
    
}

