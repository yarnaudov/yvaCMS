<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_image extends CI_Model{
	
    function run($module)
    {

        $data['image'] = base_url($module['params']['image']);

        return module::loadContent($module, $data);

    }
    
}

