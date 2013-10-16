<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_poll extends CI_Model{
	
    function run($module)
    {
	
	$this->load->add_package_path(COMPONENTS_DIR.'/polls/');
	
        $this->load->model('Poll');
        $this->load->language('modules/mod_poll');
	
	$data['poll'] = $this->Poll->getDetails($module['params']['poll_id']);
	
	if($this->input->post('poll_id') == $data['poll']['id'] && $this->input->post('answer_id')){

	    $poll_id   = $this->input->post('poll_id');
	    $answer_id = $this->input->post('answer_id');
	    
	    if($data['poll']['end_publishing'] != ""){
		$expire = strtotime($data['poll']['end_publishing'])+60*60*24;
	    }
	    else{
		$expire = time()+60*60*24*30;
	    }
		    
	    setcookie("polls[".$poll_id."]", $answer_id, $expire, "/");

	    $this->Poll->vote($answer_id);
	    exit;
	    
	}
	
	$this->jquery_ext->add_library('../modules/mod_poll/js/mod_poll.js');
        
        $show_votes = false;
        if(isset($_COOKIE['polls'][$data['poll']['id']])){
            $show_votes = true;
            $answer_id  = $_COOKIE['polls'][$data['poll']['id']];
        }
        elseif(isset($_GET['show_votes']) && $_GET['show_votes'] == $data['poll']['id']){
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

