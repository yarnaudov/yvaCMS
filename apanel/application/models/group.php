<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group extends CI_Model {

    public function getDetails($group_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('group_id', $group_id);

        $group = $this->db->get('groups');  	
        $group = $group->result_array();

        if(empty($group)){
            return;
        }
        
        if($field == null){
                return $group[0];
        }
        else{  	
            return $group[0][$field];
        }

    }
  
    public function getGroups($filters = array(), $order_by = "", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }     
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND title like '%".$value."%' ";
            }
            elseif($key == 'category'){
                $filter .= " AND category_id = '".$value."' ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        groups
                    WHERE
                        group_id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $groups = $this->db->query($query)->result_array();

        return $groups;

    }
    
    public function getMaxOrder()
    {
        
        $this->db->select_max("`order`");
        $max_order = $this->db->get('groups')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count($category = "")
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        groups
                    WHERE
                        category_id = '".$category."'";
         
        //echo $query."<br/>";

        $groups = $this->db->query($query)->result_array();    

        return $groups[0]['count'];

    }
  
    public function prepareData($action)
    {
        
        $data['title']        = $this->input->post('title');
        $data['description']  = $this->input->post('description');
        $data['status']       = $this->input->post('status');
        
        if(!$this->input->post('no_access')){
        		$data['access']   = json_encode($this->input->post('access'));
        }
        
        if($action == 'insert'){
            $data['order']      =  self::getMaxOrder()+1;
            $data['created_by'] =  $_SESSION['user_id'];
            $data['created_on'] =  now();        
        }
        elseif($action == 'update'){
            $data['updated_by'] =  $_SESSION['user_id'];
            $data['updated_on'] =  now(); 
        }

        //echo print_r($data);
        return $data;

    }

    public function add()
    {

        $data = self::prepareData('insert');

        $query = $this->db->insert_string('groups', $data);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_group'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_group_error'));
        }
        
        $group_id =$this->db->insert_id();
        
        $this->Custom_field->saveFieldsValues($group_id);
        
        return $group_id;

    }

    public function edit($group_id)
    {

        $data = self::prepareData('update');
        $where = "group_id = ".$group_id; 

        $query = $this->db->update_string('groups', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_group'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_group_error'));
        }
        
        $this->Custom_field->saveFieldsValues($group_id);
        
        return $group_id;

    }

    public function changeStatus($group_id, $status)
    {   

        $data['status'] = $status;
        $where = "group_id = ".$group_id;

        $query = $this->db->update_string('groups', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
        }

    }
    
    public function changeOrder($group_id, $order)
    {   
        
        $old_order   = self::getDetails($group_id, 'order');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order;
        $query1 = $this->db->update_string('groups', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "group_id = ".$group_id;
        $query2 = $this->db->update_string('groups', $data2, $where2);
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
        
        $groups = $this->input->post('groups');     
        foreach($groups as $group){
            
            $status = self::getDetails($group, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM groups WHERE group_id = '".$group."'");
            }
            else{
                $result = self::changeStatus($group, 'trash');
            }
            
            if($result != true){
                $this->db->query("ROLLBACK");
                return false;
            }
            
        }
        
        $this->db->query("COMMIT");
        return true;
        
    }
    
}