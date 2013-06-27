<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_articles_list extends CI_Model{
	
    function run($module)
    {
	  	
        $data['article_alias'] = $this->article_alias;
	$data['articles'] = array();

	$data['articles'] = $this->Article->getByCategory($module['params']['categories'], $module['params']['order'], $module['params']['number'], 'module'.$module['id']);
	
	if(in_array('most_popular', $module['params']['categories'])){
	    $data['articles'] = $data['articles']+$this->Article->getMostPopular($module['params']['order'], $module['params']['number'], 'module'.$module['id']);
	}
	
        return module::loadContent($module, $data);
	  	
    }
    
}

