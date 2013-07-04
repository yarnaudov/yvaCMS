<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poll extends CI_Model {

    private $id;
    
    public function getDetails($id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('id', $id);

        $poll = $this->db->get('com_polls');  	
        $poll = $poll->result_array();
        
        $poll[0]['answers'] = self::getAnswers($id);
                                
        if($field == null){
            return $poll[0];
        }
        else{  	
            return $poll[0][$field];
        }

    }
    
    function getAnswers($poll_id)
    {
    	
    	$this->db->select('*');
        $this->db->where('poll_id', $poll_id);
        $this->db->where('status', 'yes');

        $answers = $this->db->get('com_poll_answers');  	
        $answers = $answers->result_array();

        return $answers;

    }
    
    function getAnswer($id, $field = null)
    {
    	
        $this->db->select('*');
        $this->db->where('id', $id);

        $answer = $this->db->get('com_poll_answers');  	
        $answer = $answer->result_array();
        
        if(!isset($answer[0])){
            return;
        }
        
        if($field == null){
            return $answer[0];
        }
        else{  	
            return $answer[0][$field];
        }
      

    }
    
    function vote($answer_id)
    {
    	  
    	  $votes = self::getAnswer($answer_id, 'votes');    	  
    	  $votes++;
    	  
    	  $this->db->query("UPDATE com_poll_answers SET votes = ".$votes." WHERE id = '".$answer_id."'");
    	
    }
    
}