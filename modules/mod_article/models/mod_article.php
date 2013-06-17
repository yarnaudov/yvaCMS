<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_article extends CI_Model{
	
    function run($module)
    {

        $data['article'] = $this->Article->getDetails($module['params']['article_id']);

        return module::loadContent($module, $data);

    }
    
}

