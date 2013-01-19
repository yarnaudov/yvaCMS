<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom_field extends CI_Model {
  
    public function getDetails($custom_field_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('custom_field_id', $custom_field_id);

        $custom_field = $this->db->get('custom_fields');  	
        $custom_field = $custom_field->result_array();

        if($field == null){
                return $custom_field[0];
        }
        else{  	
            return $custom_field[0][$field];
        }

    }
  
    public function getCustomFields($filters = array(), $order_by = "", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }     
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                
                $filter .= " AND (title like '%".$value."%'
                            OR
                            description  like '%".$value."%' )";            

            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        custom_fields
                    WHERE
                        extension = '".$this->extension."'
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $custom_fields = $this->db->query($query)->result_array();

        return $custom_fields;

    }
    
    public function getMaxOrder()
    {
                
        $this->db->select_max("`order`");
        $this->db->where("extension", $this->extension);
        $max_order = $this->db->get('custom_fields')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function prepareData($action)
    {
         
        $this->load->helper('alias');
        
        $data['title']       = $this->input->post('title');
        $data['description'] = $this->input->post('description');
        $data['type']        = $this->input->post('type');
        $data['value']       = $this->input->post('value');
        $data['multilang']   = $this->input->post('multilang');

        if($action == 'insert'){
            $data['extension']  = $this->extension;
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

        $query = $this->db->insert_string('custom_fields', $data);
        //echo $query;
        $result = $this->db->query($query);
        
        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_custom_field'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_custom_field_error'));
        }
        
        return $this->db->insert_id();

    }

    public function edit($custom_field_id)
    {

        $data = self::prepareData('update');
        $where = "custom_field_id = ".$custom_field_id; 

        $query = $this->db->update_string('custom_fields', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_custom_field'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_custom_field_error'));
        }

        return $custom_field_id;

    }
    
    public function delete()
    {
        
        $this->db->query("BEGIN");
        
        $custom_fields = $this->input->post('custom_fields');     
        foreach($custom_fields as $custom_field){
            
            $status = self::getDetails($custom_field, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM custom_fields WHERE custom_field_id = '".$custom_field."'");
                if($result == true){
                  $result = $this->db->simple_query("DELETE FROM custom_fields_values WHERE custom_field_id = '".$custom_field."'");
                }
            }
            else{
                $result = self::changeStatus($custom_field, 'trash');
            }
            
            if($result != true){
                $this->db->query("ROLLBACK");
                return false;
            }
            
        }
        
        $this->db->query("COMMIT");
        return true;
        
    }
    
    public function changeStatus($custom_field_id, $status)
    {   

        $data['status'] = $status;
        $where = "custom_field_id = ".$custom_field_id;

        $query = $this->db->update_string('custom_fields', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
        }

    }
    
    public function changeOrder($custom_field_id, $order)
    {   
        
        $old_order   = self::getDetails($custom_field_id, 'order');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND extension = '".$this->extension."'";
        $query1 = $this->db->update_string('custom_fields', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "custom_field_id = ".$custom_field_id;
        $query2 = $this->db->update_string('custom_fields', $data2, $where2);
        //echo $query2;
        $result2 = $this->db->query($query2);
        
        if($result1 == true && $result2 == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_order_error'));
        }

    }
    
    public function saveFieldsValues($element_id)
    {
               
        $custom_fields = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        
        if(count($custom_fields) == 0){
            return array();
        }
        
        foreach($custom_fields as $custom_field){
            
            $data['translation'] = $custom_field['multilang'] == "yes" ? $this->trl : NULL;            
            $data['value']       = $this->input->post('field'.$custom_field['custom_field_id']);
            
            $where_translation = $data['translation'] == NULL ? "translation IS NULL" : "translation = '".$data['translation']."'";
            
            $where = "custom_field_id = ".$custom_field['custom_field_id']." 
                     AND 
                      element_id = ".$element_id."
                     AND
                      ".$where_translation." "; 
            
            $query = "SELECT 
                          COUNT(*) as `count`
                        FROM
                          custom_fields_values
                        WHERE
                          ".$where."";

            //echo $query."<br/>";
            //exit();

            $count = $this->db->query($query)->result_array();    

            if($count[0]['count'] == 1){
                $query = $this->db->update_string('custom_fields_values', $data, $where);
                //echo $query;
                $this->db->query($query);
            }
            else{
                $data['custom_field_id'] = $custom_field['custom_field_id'];
                $data['element_id']      = $element_id;
            
                $query = $this->db->insert_string('custom_fields_values', $data);
                //echo $query;
                $this->db->query($query);
            }   
            
            
        }
        
    }
    
    public function getFieldsValues($element_id)
    {
        
        $custom_fields = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        
        if(count($custom_fields) == 0){
            return array();
        }
        
        foreach($custom_fields as $custom_field){
            $custom_fields_ids[] = $custom_field['custom_field_id'];
        }
        
        $query = "SELECT 
                      *
                    FROM
                      custom_fields_values
                    WHERE
                      element_id = ".$element_id."
                     AND
                      custom_field_id IN (".implode(',', $custom_fields_ids).")
                     AND
                      (translation = '".$this->trl."' || translation IS NULL)";
        
        $custom_fields = $this->db->query($query)->result_array();
        
        $data = array();
        
        foreach($custom_fields as $custom_field){
                       
            $data['field'.$custom_field['custom_field_id']]  = $custom_field['value'];
                  
            
        }
        
        return $data;
        
    }
    
}