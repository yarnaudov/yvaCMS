<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_pull extends CI_Model{
	
    function run($module)
    {
	  	  
        $this->load->model('pulls/Pull');
        $this->load->language('mod_pull');

        $data['pull'] = $this->Pull->getDetails($module['params']['pull_id']);
                
        $show_votes = false;
        if(isset($_COOKIE['pulls'][$data['pull']['id']])){
            $show_votes = true;
            $answer_id  = $_COOKIE['pulls'][$data['pull']['id']];
        }
        elseif(isset($_REQUEST['show_votes']) && $_REQUEST['show_votes'] == $data['pull']['id']){
            $show_votes = true;
            $answer_id  = false;
        }
        
        if($show_votes == true){
        	  
            $data['answer_id'] = $answer_id;

            $data['votes'] = 0;
            foreach($data['pull']['answers'] as $answer){
                $data['votes'] += $answer['votes'];	
            }
        	  
            $module['template'] = '../../modules/mod_pull/views/mod_pull_graphics';
            
        }
                
        return module::loadContent($module, $data);
	  	
    }
    
}

