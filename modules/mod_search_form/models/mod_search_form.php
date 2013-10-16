<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_search_form extends CI_Model{
	
    function run($module)
    {

	$data['module'] = $module;

	return module::loadContent($module, $data);

    }
    
}

