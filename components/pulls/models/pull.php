<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pull extends CI_Model {

    private $pull_id;
    
    public function getDetails($pull_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('pull_id', $pull_id);

        $pull = $this->db->get('com_pulls');  	
        $pull = $pull->result_array();
        
        $pull[0]['answers'] = self::getAnswers($pull_id);
                                
        if($field == null){
            return $pull[0];
        }
        else{  	
            return $pull[0][$field];
        }

    }
    
    function getAnswers($pull_id)
    {
    	
    	$this->db->select('*');
        $this->db->where('pull_id', $pull_id);
        $this->db->where('status', 'yes');

        $answers = $this->db->get('com_pull_answers');  	
        $answers = $answers->result_array();

        return $answers;

    }
    
    function getAnswer($answer_id, $field = null)
    {
    	
        $this->db->select('*');
        $this->db->where('answer_id', $answer_id);

        $answer = $this->db->get('com_pull_answers');  	
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
    	  
    	  $this->db->query("UPDATE com_pull_answers SET votes = ".$votes." WHERE answer_id = '".$answer_id."'");
    	
    }
    
}