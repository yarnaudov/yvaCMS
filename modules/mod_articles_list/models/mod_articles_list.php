<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_articles_list extends CI_Model{
	
    function run($module)
    {
	  	
        $data['article_alias'] = $this->article_alias;
	
		if(isset($module['params']['categories'])){

			if(in_array('most_popular', $module['params']['categories'])){
				$data['articles'] = $this->Article->getMostPopular($module['params']['order'], $module['params']['number'], 'module'.$module['id']);
			}
			elseif(in_array('most_commented', $module['params']['categories'])){
				$data['articles'] = $this->Article->getMostCommented($module['params']['order'], $module['params']['number'], 'module'.$module['id']);
			}
			else{
				$data['articles'] = $this->Article->getByCategory($module['params']['categories'], $module['params']['order'], $module['params']['number'], 'module'.$module['id']);
			}

		}
		else{
			$data['articles'] = array();
		}
	
        return module::loadContent($module, $data);
	  	
    }
    
}

