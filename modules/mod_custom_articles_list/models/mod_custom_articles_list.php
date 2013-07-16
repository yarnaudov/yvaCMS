<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_custom_articles_list extends CI_Model{
	
    function run($module)
    {
	
	$data['article_alias'] = $this->article_alias;
	
	$data['articles'] = $this->Article->getByIds(isset($module['params']['custom_articles']) ? $module['params']['custom_articles'] : array());
			
        return module::loadContent($module, $data);
	  	
    }
    
}

