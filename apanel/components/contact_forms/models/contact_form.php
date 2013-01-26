<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_form extends CI_Model {

    private $id;
    
    public function getDetails($id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('id', $id);

        $contact_form = $this->db->get('com_contacts_forms');  	
        $contact_form = $contact_form->result_array();

        if($field == null){
            $contact_form[0]['fields'] = json_decode($contact_form[0]['fields'], true);
            return $contact_form[0];
        }
        else{  	
            return $contact_form[0][$field];
        }

    }
    
    public function getContactForms($filters = array(), $order_by = "", $limit = "")
    {
                
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }     
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND ( ";
                $languages = Language::getLanguages();
                foreach($languages as $key => $language){
                    if($key > 0){
                        $filter .= " OR ";
                    }
                    $filter .= "title_".$language['abbreviation']." like '%".$value."%'
                                OR
                                description_".$language['abbreviation']."  like '%".$value."%'";
                }
                
                $filter .= " ) ";

            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        com_contacts_forms
                    WHERE
                        id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $contacts_forms = $this->db->query($query)->result_array();

        return $contacts_forms;
        
    }
    
    public function getMaxOrder()
    {
                
        $this->db->select_max("`order`");
        $max_order = $this->db->get('com_contacts_forms')->result_array();      
        $order = $max_order[0]['order'];

        return $order;
        
    }
    
    public function count()
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        com_contacts_forms
                    WHERE
                        status != 'trash'";
         
        //echo $query."<br/>";

        $contact_forms = $this->db->query($query)->result_array();    

        return $contact_forms[0]['count'];

    }
    
    public function prepareData($id, $action)
    {
        
        $data['title_'.$this->trl]       = $this->input->post('title');
        $data['description_'.$this->trl] = $this->input->post('description');
        $data['status']                  = $this->input->post('status');    
        $data['to']                      = $this->input->post('to');  
        $data['cc']                      = $this->input->post('cc');  
        $data['bcc']                     = $this->input->post('bcc');  
        $data['fields']                  = $this->input->post('fields');
        
        unset($data['fields'][0]);
        
        if($action == 'insert'){
            $data['order'] =  self::getMaxOrder()+1;
            $data['created_by'] =  $_SESSION['user_id'];
            $data['created_on'] =  now();        
        }
        elseif($action == 'update'){
            
            foreach($data['fields'] as $number => $field){
                        
                $fields = json_decode(self::getDetails($id, 'fields'), true);
                $languages = Language::getLanguages();
                foreach($languages as $key => $language){
                    if($this->trl == $language['abbreviation']){
                        continue;
                    }
                    
                    $data['fields'][$number]['label_'.$language['abbreviation']]  = $fields[$number]['label_'.$language['abbreviation']];
                    $data['fields'][$number]['value_'.$language['abbreviation']]  = $fields[$number]['value_'.$language['abbreviation']];

                }
                
            }
            $data['fields'] = json_encode($data['fields']);
            
            $data['updated_by'] =  $_SESSION['user_id'];
            $data['updated_on'] =  now(); 
        }
        
        return $data;
        
    }
    
    public function add()
    {
        
        $data = self::prepareData('', 'insert');
        
        $query = $this->db->insert_string('com_contacts_forms', $data);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_contact_form'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_contact_form_error'));
        }
        
        $article_id = $this->db->insert_id();
        
        return $article_id;
        
    }
    
    public function edit($id)
    {
        
        $data = self::prepareData($id, 'update');
        $where = "id = ".$id; 

        $query = $this->db->update_string('com_contacts_forms', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_contact_form'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_contact_form_error'));
        }
                
        return $id;
        
    }
    
    public function changeStatus($id, $status)
    {   

        $data['status'] = $status;
        $where = "id = ".$id;

        $query = $this->db->update_string('com_contacts_forms', $data, $where);
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
        $query1 = $this->db->update_string('com_contacts_forms', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "id = ".$id;
        $query2 = $this->db->update_string('com_contacts_forms', $data2, $where2);
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
        
        $contact_forms = $this->input->post('contact_forms');     
        foreach($contact_forms as $contact_form){
            
            $status = self::getDetails($contact_form, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM com_contacts_forms WHERE id = '".$contact_form."'");
            }
            else{
                $result = self::changeStatus($contact_form, 'trash');
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