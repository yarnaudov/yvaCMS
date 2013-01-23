<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
  
    public function getDetails($id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('id', $id);

        $user = $this->db->get('users');  	
        $user = $user->result_array();
        
        if(empty($user)){
            return;
        }
        
        if($field == null){
            return $user[0];
        }
        else{  	
            return $user[0][$field];
        }

    }

    public function login()
    {

        $user = $this->input->post('user');
        $pass = $this->input->post('pass');

        $query = "SELECT * FROM users WHERE user = '".$user."' AND status = 'yes'";

        $user_d = $this->db->query($query)->result_array();

        if(count($user_d) == 1 && $user_d[0]['pass'] == md5($pass)){
            $_SESSION['user_id'] = $user_d[0]['id'];
        }
        else{
            $this->session->set_userdata('login_error_msg', lang('msg_login_error'));          
        }

    }
  
    public function logout()
    {

        unset($_SESSION['user_id']);

    }
    
    public function getUsers($filters = array(), $order_by = "", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }
        
        if(substr_count($order_by, 'order')){
            $order_by = "user_group_id, ".$order_by;
        }      
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){                                
                $filter .= " AND name LIKE '%".$value."%'";                
            }
            elseif($key == 'group'){
                $filter .= " AND user_group_id = '".$value."' ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        users
                    WHERE
                        id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $users = $this->db->query($query)->result_array();

        return $users;

    }
    
    public function getMaxOrder($user_group = "")
    {
        
        $user_group == "" ? $group = $this->input->post('user_group') : "";
        
        $this->db->select_max("`order`");
        $this->db->where("user_group_id", $group);
        $max_order = $this->db->get('users')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count($user_group = "")
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        users
                    WHERE
                        user_group_id = '".$user_group."'";
         
        //echo $query."<br/>";

        $users = $this->db->query($query)->result_array();    

        return $users[0]['count'];

    }
    
    public function prepareData($action)
    {
                 
        $data['name']              = $this->input->post('name');
        $data['user']              = $this->input->post('user');
        
        $data['pass']              = trim($this->input->post('pass'));
        if(!empty($data['pass'])){
            $data['pass']          = md5($data['pass']);
        }
        else{
            unset($data['pass']);
        }
        
        $data['description']       = $this->input->post('description');
        $data['user_group_id']     = $this->input->post('user_group');
        $data['status']            = $this->input->post('status');
                        
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

        $data = self::prepareData('insert');

        $this->db->query('BEGIN');
        
        // save data in users table
        $query = $this->db->insert_string('users', $data);
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_user_error'));
            $this->db->query('ROLLBACK');
            return;
        }
        
        $id = $this->db->insert_id();
        
        // save custom fields data
        $result = $this->Custom_field->saveFieldsValues($id);
        if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_user_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        $this->session->set_userdata('good_msg', lang('msg_save_user'));
        $this->db->query('COMMIT');
        return $id;

    }

    public function edit($id)
    {

        $data = self::prepareData('update');
        
        $this->db->query('BEGIN');
        
        $where = "id = ".$id; 
        $query = $this->db->update_string('users', $data, $where);
        $result = $this->db->query($query);
        if($result == true){
            $this->session->set_userdata('error_msg', lang('msg_save_user_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        // save custom fields data
        $result = $this->Custom_field->saveFieldsValues($id);
        if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_user_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        $this->session->set_userdata('good_msg', lang('msg_save_user'));
        $this->db->query('COMMIT');
        return $id;

    }    
    
    public function changeStatus($id, $status)
    {   

        $data['status'] = $status;
        $where = "id = ".$id;

        $query = $this->db->update_string('users', $data, $where);
        //echo $query;
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
        }
        
        return true;

    }
    
    public function changeOrder($id, $order)
    {   
        
        $old_order     = self::getDetails($id, 'order');
        $user_group_id = self::getDetails($id, 'user_group_id');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND user_group_id = '".$user_group_id."'";
        $query1 = $this->db->update_string('users', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "id = ".$id;
        $query2 = $this->db->update_string('users', $data2, $where2);
        //echo $query2;
        $result2 = $this->db->query($query2);
        
        if($result1 != true && $result2 != true){
            $this->session->set_userdata('error_msg', lang('msg_order_error'));
        }

        return true;
        
    }
    
    public function delete()
    {
        
        $this->db->query("BEGIN");
        
        $users = $this->input->post('users');     
        foreach($users as $user){
            
            $status = self::getDetails($user, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM users WHERE id = '".$user."'");
            }
            else{
                $result = self::changeStatus($user, 'trash');
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