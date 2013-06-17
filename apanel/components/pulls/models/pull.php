<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pull extends CI_Model {

    private $id;
    
    public function getDetails($id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('id', $id);

        $pull = $this->db->get('com_pulls');  	
        $pull = $pull->result_array();
             
        $answers = self::getAnswers($id);
        foreach($answers as $key => $answer){
            $pull[0]['answers'][$key+1]['id']     = $answer['id'];
            $pull[0]['answers'][$key+1]['title']  = $answer['title'];
            $pull[0]['answers'][$key+1]['votes']  = $answer['votes'];
            $pull[0]['answers'][$key+1]['status'] = $answer['status'];
        }
                                
        if($field == null){
            return $pull[0];
        }
        else{  	
            return $pull[0][$field];
        }

    }
    
    public function getPulls($filters = array(), $order_by = "", $limit = "")
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
                        com_pulls
                    WHERE
                        id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $pulls = $this->db->query($query)->result_array();

        return $pulls;
        
    }
    
    public function getMaxOrder()
    {
                
        $this->db->select_max("`order`");
        $max_order = $this->db->get('com_pulls')->result_array();      
        $order = $max_order[0]['order'];

        return $order;
        
    }
    
    public function count()
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        com_pulls
                    WHERE
                        status != 'trash'";
         
        //echo $query."<br/>";

        $pulls = $this->db->query($query)->result_array();    

        return $pulls[0]['count'];

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
        
        $query = $this->db->insert_string('com_pulls', $data);
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

        $query = $this->db->update_string('com_pulls', $data, $where);
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

        $query = $this->db->update_string('com_pulls', $data, $where);
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
        $query1 = $this->db->update_string('com_pulls', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "id = ".$id;
        $query2 = $this->db->update_string('com_pulls', $data2, $where2);
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
        
        $pulls = $this->input->post('pulls');     
        foreach($pulls as $pull){
            
            $status = self::getDetails($pull, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM com_pulls WHERE id = '".$pull."'");
            }
            else{
                $result = self::changeStatus($pull, 'trash');
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
    	  
        //$this->db->query("DELETE FROM com_pull_answers WHERE id = '".$id."'");
    	    	
        foreach($answers as $answer){
    	  	
            if(empty($answer)){
                continue;
            }
    	    
            $data['title']  = $answer['title'];
            $data['status'] = $answer['status'];
            
            if(!empty($answer['id'])){
                $where = "id = ".$answer['id']; 
                $query = $this->db->update_string('com_pull_answers', $data, $where);
            }
            else{
                $data['pull_id'] = $id;
                $query = $this->db->insert_string('com_pull_answers', $data);
            }
            
            //echo $query;
            $result = $this->db->query($query);
            
            $answers_ids[] = !empty($answer['id']) ? $answer['id'] : $this->db->insert_id();
            
        }
        
        $this->db->query("DELETE FROM com_pull_answers WHERE pull_id = '".$id."' AND id NOT IN (".implode(',', $answers_ids).")");

    }
    
    function getAnswers($id)
    {
    	
    	$this->db->select('*');
        $this->db->where('pull_id', $id);

        $answers = $this->db->get('com_pull_answers');  	
        $answers = $answers->result_array();
      
        return $answers;

    }
    
}