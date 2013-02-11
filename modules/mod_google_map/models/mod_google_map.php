<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_google_map extends CI_Model{
	
    function run($module)
    {

        $data['params'] = $module['params'];

        return module::loadContent($module, $data);

    }
    
}