<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
  
    public function getDefault()
    {
        $language = $this->db->query("SELECT id FROM languages WHERE `default` = 'yes'")->result_array();
        return $language[0]['id'];

    }
  
    public function getDetails($id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('id', $id);

        $language = $this->db->get('languages');  	
        $language = $language->result_array();

        if(empty($language)){
            return;
        }
        
        if($field == null){
            return $language[0];
        }
        else{
            return $language[0][$field];
        }

    }
  
    public function getLanguages($filters = array(), $order_by = "", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }
             
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND (title like '%".$value."%' OR description like '%".$value."%' ) ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        languages
                    WHERE
                        id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $languages= $this->db->query($query)->result_array();

        return $languages;

    }
    
     public function getMaxOrder()
    {
                
        $this->db->select_max("`order`");
        $max_order = $this->db->get('languages')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function prepareData($action)
    {
        
        $data['title']        = $this->input->post('title');
        $data['abbreviation'] = $this->input->post('abbreviation');
        $data['description']  = $this->input->post('description');
        $data['status']       = $this->input->post('status'); 
        $data['image']        = $this->input->post('image');
        $data['default']      = $this->input->post('default');
        
        if($data['default'] == ""){
            unset($data['default']);
        }
        
        if($action == 'insert'){
            $data['order']      = self::getMaxOrder()+1;
            $data['created_by'] = $_SESSION['user_id'];
            $data['created_on'] = now();        
        }
        elseif($action == 'update'){            
            $data['updated_by'] = $_SESSION['user_id'];
            $data['updated_on'] = now(); 
        }

        //echo print_r($data);
        return $data;

    }

    public function add()
    {

        $data = self::prepareData('insert');
        
        if($data['default'] == 'yes'){
            $query = $this->db->update_string('languages', array('default' => 'no'), "`default` = 'yes'");
            $this->db->query($query);
        }
        
        $query = $this->db->insert_string('languages', $data);
        //echo $query;
        $result = $this->db->query($query);
        
        if($result == true){
            $id = $this->db->insert_id();
            $this->session->set_userdata('good_msg', lang('msg_save_language'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_language_error'));
        }
        
        return $id;

    }

    public function edit($id)
    {

        $data = self::prepareData('update');
        
        if($data['default'] == 'yes'){
            $query = $this->db->update_string('languages', array('default' => 'no'), "`default` = 'yes'");
            $this->db->query($query);
        }
        
        $where = "id = ".$id; 
        $query = $this->db->update_string('languages', $data, $where);
        //echo $query;
        
        $old_abbr = self::getDetails($id, 'abbreviation');
        
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_language'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_language_error'));
        }

        return $id;

    }
    
    public function changeStatus($id, $status)
    {   

        $data['status'] = $status;
        $where = "id = ".$id;

        $query = $this->db->update_string('languages', $data, $where);
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
        $query1 = $this->db->update_string('languages', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "id = ".$id;
        $query2 = $this->db->update_string('languages', $data2, $where2);
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
        
        $languages = $this->input->post('languages');     
        foreach($languages as $language){
            
            $language = self::getDetails($language);
            
            if($language['default'] == 'yes'){
                $this->session->set_userdata('error_msg', lang('msg_delete_default_language_error'));
                $result = true;
            }
            elseif($status == 'trash'){
                $result = true;//$this->db->simple_query("DELETE FROM languages WHERE id = '".$language['id']."'");
            }
            else{
                $result = self::changeStatus($language['id'], 'trash');
            }
            
            if($result != true){
                $this->db->query("ROLLBACK");
                return false;
            }
            
        }
        
        $this->db->query("COMMIT");

        return true;
        
    }
    
    public function makeDefault($id)
    {   

        $this->db->query("BEGIN");
        
        $query = $this->db->update_string('languages', array('default' => 'no'), "`default` = 'yes'");
        $result = $this->db->query($query);
        if($result != true){
            $this->db->query("ROLLBACK");
            return false;
        }
        
        $query = $this->db->update_string('languages', array('default' => 'yes'), "id = ".$id);
        $result = $this->db->query($query);
        if($result != true){
            $this->db->query("ROLLBACK");
            return false;
        }
        
        $this->db->query("COMMIT");
        return true;

    }
    
}
