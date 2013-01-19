<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
  
    public function getDetails($user_id, $field = null)
    {

        $user_id == "" ? $user_id = $this->session->userdata('user_id') : "";

        $this->db->select('*');
        $this->db->where('user_id', $user_id);

        $user = $this->db->get('users');  	
        $user = $user->result_array();

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

        $query = "SELECT
                    *
                    FROM
                    users
                    WHERE
                    user = '".$user."'";


        $user_d = $this->db->query($query)->result_array();

        if(count($user_d) == 1 && $user_d[0]['pass'] == md5($pass)){          
            $this->session->set_userdata('user_id', $user_d[0]['user_id']);          
        }
        else{
            $this->session->set_userdata('login_error_msg', lang('msg_login_error'));          
        }

    }
  
    public function logout()
    {

        $this->session->unset_userdata('user_id'); 

    }
    
    public function getUsers($filters = array(), $order_by = "", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }
        
        if(substr_count($order_by, 'order')){
            $order_by = "group_id, ".$order_by;
        }      
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){                                
                $filter .= " AND name LIKE '%".$value."%'";                
            }
            elseif($key == 'group'){
                $filter .= " AND group_id = '".$value."' ";
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
                        user_id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $users = $this->db->query($query)->result_array();

        return $users;

    }
    
    public function count($group = "")
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        users
                    WHERE
                        group_id = '".$group."'";
         
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
        $data['group_id']          = $this->input->post('group');
        $data['status']            = $this->input->post('status');
                        
        if($action == 'insert'){
            $data['order'] =  self::getMaxOrder()+1;
            $data['created_by'] =  $this->session->userdata('user_id');
            $data['created_on'] =  now();        
        }
        elseif($action == 'update'){
            $data['updated_by'] =  $this->session->userdata('user_id');
            $data['updated_on'] =  now(); 
        }

        //echo print_r($data);
        return $data;

    }

    public function add()
    {

        $data = self::prepareData('insert');

        $query = $this->db->insert_string('users', $data);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_user'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_user_error'));
        }
        
        $user_id =$this->db->insert_id();
        
        $this->Custom_field->saveFieldsValues($user_id);
        
        return $user_id;

    }

    public function edit($user_id)
    {

        $data = self::prepareData('update');
        $where = "user_id = ".$user_id; 

        $query = $this->db->update_string('users', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_user'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_user_error'));
        }
        
        $this->Custom_field->saveFieldsValues($user_id);
        
        return $user_id;

    }    
    
    public function changeStatus($user_id, $status)
    {   

        $data['status'] = $status;
        $where = "user_id = ".$user_id;

        $query = $this->db->update_string('users', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
        }

    }
    
    public function changeOrder($user_id, $order)
    {   
        
        $old_order   = self::getDetails($user_id, 'order');
        $group_id = self::getDetails($user_id, 'group_id');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND group_id = '".$group_id."'";
        $query1 = $this->db->update_string('users', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "user_id = ".$user_id;
        $query2 = $this->db->update_string('users', $data2, $where2);
        //echo $query2;
        $result2 = $this->db->query($query2);
        
        if($result1 == true && $result2 == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_order_error'));
        }

    }
    
}