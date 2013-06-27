<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_article extends CI_Model{
	
    function run($module)
    {

        if(!empty($module['params']['article_id'])){
                    
            $article = $this->Article->getDetails($module['params']['article_id']);                    
       
        }
        else{
            $article['show_title'] = 'no';
            $article['text'] = lang('msg_article_not_selected');
        }
                    
        $data['article'] = $article;

        return module::loadContent($module, $data);

    }
    
}

