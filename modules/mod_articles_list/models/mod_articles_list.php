<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_articles_list extends CI_Model{
	
    function run($module)
    {
	  	
        $data['article_alias'] = $this->article_alias;
	$data['articles'] = array();

	foreach($module['params']['categories'] as $category){
	
	    if($category == 'most_popular'){
		$data['articles'] = $data['articles']+$this->Article->getMostPopular();
	    }
	    else{
		$data['articles'] = $data['articles']+$this->Article->getByCategory($category);
	    }
               
	}
	
        return module::loadContent($module, $data);
	  	
    }
    
}

