<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_form extends MY_Model {
    
    public function getDetails($id, $field = null)
    {
        
        $query = "SELECT 
                      *
                    FROM
                      com_contacts_forms ccf
                      LEFT JOIN com_contacts_forms_data ccfd ON (ccf.id = ccfd.contact_form_id AND ccfd.language_id = '".$this->language_id."')
                    WHERE
                      ccf.id = '".$id."' ";
        
        $contact_form = $this->db->query($query);  	
        $contact_form = $contact_form->result_array();

        if(empty($contact_form)){
            return;
        }

        $contact_form[0]['fields'] = json_decode($contact_form[0]['fields'], true);
        
        if($field == null){            
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
                $filter .= " AND (title like '%".$value."%' OR description like '%".$value."%' OR text_above like '%".$value."%' OR text_under like '%".$value."%') ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        com_contacts_forms ccf
                      LEFT JOIN com_contacts_forms_data ccfd ON (ccf.id = ccfd.contact_form_id AND ccfd.language_id = '".$this->language_id."')
                    WHERE
                        ccf.id IS NOT NULL
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
    
    public function prepareData($action)
    {
        
        $data['com_contacts_forms_data']['title']       = $this->input->post('title');
        $data['com_contacts_forms_data']['description'] = $this->input->post('description');      
	$data['com_contacts_forms_data']['text_above']  = $this->input->post('text_above');
	$data['com_contacts_forms_data']['text_under']  = $this->input->post('text_under');
	$data['com_contacts_forms_data']['msg_success'] = $this->input->post('msg_success');
	$data['com_contacts_forms_data']['msg_error']   = $this->input->post('msg_error');
        $data['com_contacts_forms_data']['language_id'] = $this->language_id;
        
        $fields = $this->input->post('fields');
        //unset($fields[0]);
        $data['com_contacts_forms_data']['fields']      = json_encode($fields);
        
        $data['com_contacts_forms']['status']           = $this->input->post('status');    
        $data['com_contacts_forms']['to']               = $this->input->post('to');  
        $data['com_contacts_forms']['cc']               = $this->input->post('cc');  
        $data['com_contacts_forms']['bcc']              = $this->input->post('bcc');  
        
        if($action == 'insert'){
            $data['com_contacts_forms']['order']      = self::getMaxOrder()+1;
            $data['com_contacts_forms']['created_by'] = $_SESSION['user_id'];
            $data['com_contacts_forms']['created_on'] = now();        
        }
        elseif($action == 'update'){            
            $data['com_contacts_forms']['updated_by'] = $_SESSION['user_id'];
            $data['com_contact_forms']['updated_on'] = now();             
        }
        
        return $data;
        
    }
    
    public function add()
    {
        
        $data = self::prepareData('insert');

        $this->db->query('BEGIN');
        
        // save data in com_contact_forms table
        $query = $this->db->insert_string('com_contacts_forms', $data['com_contacts_forms']);
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_contact_form_error'));
            $this->db->query('ROLLBACK');
            return;
        }
        
        $id = $this->db->insert_id();
        
        // save data in com_contact_forms_data table
        $data['com_contacts_forms_data']['contact_form_id'] = $id;
        $query = $this->db->insert_string('com_contacts_forms_data', $data['com_contacts_forms_data']);
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_contact_form_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
                
        $this->session->set_userdata('good_msg', lang('msg_save_contact_form'));
        $this->db->query('COMMIT');
        return $id;
        
    }
    
    public function edit($id)
    {
        
        $data = self::prepareData('update');
        
        $this->db->query('BEGIN');
        
        // save data in com_contacts_forms table
        $where = "id = ".$id; 
        $query = $this->db->update_string('com_contacts_forms', $data['com_contacts_forms'], $where);        
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_contact_form_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        // save data in com_contacts_forms_data table
        if(parent::_dataExists('com_contacts_forms_data', 'contact_form_id', $id) == 0){
            $data['com_contacts_forms_data']['contact_form_id'] = $id;
            $query = $this->db->insert_string('com_contacts_forms_data', $data['com_contacts_forms_data']);
        }
        else{            
            $where = "contact_form_id = ".$id." AND language_id = ".$this->language_id." ";
            $query = $this->db->update_string('com_contacts_forms_data', $data['com_contacts_forms_data'], $where);            
        }        
        $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_contact_form_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
                   
        $this->session->set_userdata('good_msg', lang('msg_save_contact_form'));
        $this->db->query('COMMIT');
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