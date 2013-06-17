<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_articles_list extends CI_Model{
	
    function run($module)
    {
	  	
        $data['article_alias'] = $this->article_alias;
	
	if($module['params']['category_id'] == 'most_popular'){
	    $data['articles'] = $this->Article->getMostPopular();
	}
	else{
	    $data['articles'] = $this->Article->getByCategory($module['params']['category_id']);
	}
               
        return module::loadContent($module, $data);
	  	
    }
    
}

