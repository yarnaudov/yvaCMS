<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poll extends CI_Model {

    private $id;
    
    public function getDetails($id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('id', $id);

        $poll = $this->db->get('com_polls');  	
        $poll = $poll->result_array();
             
        $answers = self::getAnswers($id);
        foreach($answers as $key => $answer){
            $poll[0]['answers'][$key+1]['id']     = $answer['id'];
            $poll[0]['answers'][$key+1]['title']  = $answer['title'];
            $poll[0]['answers'][$key+1]['votes']  = $answer['votes'];
            $poll[0]['answers'][$key+1]['status'] = $answer['status'];
        }
                                
        if($field == null){
            return $poll[0];
        }
        else{  	
            return $poll[0][$field];
        }

    }
    
    public function getPolls($filters = array(), $order_by = "", $limit = "")
    {
                
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }     
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND ( title like '%".$value."%' OR description like '%".$value."%' ) ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        com_polls
                    WHERE
                        id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $polls = $this->db->query($query)->result_array();

        return $polls;
        
    }
    
    public function getMaxOrder()
    {
                
        $this->db->select_max("`order`");
        $max_order = $this->db->get('com_polls')->result_array();      
        $order = $max_order[0]['order'];

        return $order;
        
    }
    
    public function count()
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        com_polls
                    WHERE
                        status != 'trash'";
         
        //echo $query."<br/>";

        $polls = $this->db->query($query)->result_array();    

        return $polls[0]['count'];

    }
    
    public function prepareData($id, $action)
    {
        
        $data['title']            = $this->input->post('title');
        $data['description']      = $this->input->post('description');
        $data['status']           = $this->input->post('status');
        $data['start_publishing'] = $this->input->post('start_publishing');
        $data['end_publishing']   = $this->input->post('end_publishing');
       
        $data['answers'] = $this->input->post('answers');
        unset($data['answers'][0]);
        
                
        if(empty($data['start_publishing'])){
            $data['start_publishing'] = NULL;
        }
        if(empty($data['end_publishing'])){
            $data['end_publishing'] = NULL;
        }
        
        if($action == 'insert'){
            $data['order'] =  self::getMaxOrder()+1;
            $data['created_by'] =  $_SESSION['user_id'];
            $data['created_on'] =  now();        
        }
        elseif($action == 'update'){            
            $data['updated_by'] =  $_SESSION['user_id'];
            $data['updated_on'] =  now(); 
        }
        
        return $data;
        
    }
    
    public function add()
    {
        
        $data = self::prepareData('', 'insert');
        $answers = $data['answers'];
        unset($data['answers']);
        
        $query = $this->db->insert_string('com_polls', $data);
        //echo $query;
        $result = $this->db->query($query);

        $id = $this->db->insert_id();

        if($result == true){
            $this->saveAnswers($id, $answers);
            $this->session->set_userdata('good_msg', lang('msg_save_article'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
        }
        
        return $id;
        
    }
    
    public function edit($id)
    {
        
        $data = self::prepareData($id, 'update');
        $answers = $data['answers'];
        unset($data['answers']);
        
        $where = "id = ".$id; 

        $query = $this->db->update_string('com_polls', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
	    $this->saveAnswers($id, $answers);
            $this->session->set_userdata('good_msg', lang('msg_save_article'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
        }
                
        return $id;
        
    }
    
    public function changeStatus($id, $status)
    {   

        $data['status'] = $status;
        $where = "id = ".$id;

        $query = $this->db->update_string('com_polls', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
        }

    }
    
    public function changeOrder($id, $order)
    {   
        
        $old_order   = self::getDetails($id, 'order');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order;
        $query1 = $this->db->update_string('com_polls', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "id = ".$id;
        $query2 = $this->db->update_string('com_polls', $data2, $where2);
        //echo $query2;
        $result2 = $this->db->query($query2);
        
        if($result1 == true && $result2 == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_order_error'));
        }

    }
    
    public function delete()
    {
        
        $this->db->query("BEGIN");
        
        $polls = $this->input->post('polls');     
        foreach($polls as $poll){
            
            $status = self::getDetails($poll, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM com_polls WHERE id = '".$poll."'");
            }
            else{
                $result = self::changeStatus($poll, 'trash');
            }
            
            if($result != true){
                $this->db->query("ROLLBACK");
                return false;
            }
            
        }
        
        $this->db->query("COMMIT");
        return true;
        
    }
    
    function saveAnswers($id, $answers)
    {
    	  
    	$answers_ids = array();
	
        foreach($answers as $answer){
    	  	
            if(empty($answer)){
                continue;
            }
    	    
            $data['title']  = $answer['title'];
            $data['status'] = $answer['status'];
            
            if(!empty($answer['id'])){
                $where = "id = ".$answer['id']; 
                $query = $this->db->update_string('com_poll_answers', $data, $where);
            }
            else{
                $data['poll_id'] = $id;
                $query = $this->db->insert_string('com_poll_answers', $data);
            }
            
            //echo $query;
            $result = $this->db->query($query);
            
            $answers_ids[] = !empty($answer['id']) ? $answer['id'] : $this->db->insert_id();
            
        }
        
	if(count($answers_ids) > 0){
	    $this->db->query("DELETE FROM com_poll_answers WHERE poll_id = '".$id."' AND id NOT IN (".implode(',', $answers_ids).")");
	}

    }
    
    function getAnswers($id)
    {
    	
    	$this->db->select('*');
        $this->db->where('poll_id', $id);

        $answers = $this->db->get('com_poll_answers');  	
        $answers = $answers->result_array();
      
        return $answers;

    }
    
}