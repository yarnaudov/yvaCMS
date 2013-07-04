<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_poll extends CI_Model{
	
    function run($module)
    {
	  	  
        $this->load->model('polls/Poll');
        $this->load->language('modules/mod_poll');

        $data['poll'] = $this->Poll->getDetails($module['params']['poll_id']);
                
        $show_votes = false;
        if(isset($_COOKIE['polls'][$data['poll']['id']])){
            $show_votes = true;
            $answer_id  = $_COOKIE['polls'][$data['poll']['id']];
        }
        elseif(isset($_REQUEST['show_votes']) && $_REQUEST['show_votes'] == $data['poll']['id']){
            $show_votes = true;
            $answer_id  = false;
        }
        
        if($show_votes == true){
        	  
            $data['answer_id'] = $answer_id;

            $data['votes'] = 0;
            foreach($data['poll']['answers'] as $answer){
                $data['votes'] += $answer['votes'];	
            }
        	  
            $module['template'] = '../../modules/mod_poll/views/mod_poll_graphics';
            
        }
                
        return module::loadContent($module, $data);
	  	
    }
    
}

