<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_tags extends CI_Model{
	
    function run($module)
    {
            
        $data = array();

        return module::loadContent($module, $data);

    }
    
}

