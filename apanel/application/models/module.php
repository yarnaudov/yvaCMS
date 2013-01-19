<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends CI_Model {

    public function getDetails($module_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('module_id', $module_id);

        $module = $this->db->get('modules');  	
        $module = $module->result_array();

        if($field == null){
                return $module[0];
        }
        else{  	
            return $module[0][$field];
        }

    }
  
    public function getModules($filters = array(), $order_by = "", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }
        
        if(substr_count($order_by, 'order')){
            $order_by = "category_id, ".$order_by;
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
                                text_".$language['abbreviation']."  like '%".$value."%'";
                }
                
                $filter .= " ) ";

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
                        modules
                    WHERE
                        module_id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $modules = $this->db->query($query)->result_array();

        return $modules;

    }
    
    public function getModulesByCategory($filters = array(), $order_by = "")
    {
        
        $modules = self::getModules($filters, $order_by);
        
        foreach($modules as $module){
            
            $modules_arr[$this->Category->getDetails($module['category_id'], 'title_'.$this->Language->getDefault())][$module['module_id']] = $module['title_'.$this->Language->getDefault()];
            
        }
        
        return $modules_arr;
        
    }
    
    public function getMaxOrder($category = "")
    {
        
        $category == "" ? $category = $this->input->post('category') : "";
        
        $this->db->select_max("`order`");
        $this->db->where("category_id", $category);
        $max_order = $this->db->get('modules')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count($category = "")
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        modules
                    WHERE
                        category_id = '".$category."'";
         
        //echo $query."<br/>";

        $modules = $this->db->query($query)->result_array();    

        return $modules[0]['count'];

    }
  
    public function prepareData($module_id, $action)
    {
        
        $data['title_'.$this->trl]       = $this->input->post('title');
        $data['description_'.$this->trl] = $this->input->post('description');

        $data['category_id']             = $this->input->post('category');
        $data['status']                  = $this->input->post('status');      
        $data['language_id']             = $this->input->post('language');
        $data['start_publishing']        = $this->input->post('start_publishing');
        $data['end_publishing']          = $this->input->post('end_publishing');
        $data['access']                  = $this->input->post('access');
        $data['show_title']              = $this->input->post('show_title');
        $data['type']                    = $this->input->post('type');
        $data['display_in']              = $this->input->post('display_in');
        $data['params']                  = $this->input->post('params');

        if($data['language_id'] == 'all'){
            $data['language_id'] = NULL;
        }
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
            
            if($data['type'] == 'search_form'){
                $params = json_decode(self::getDetails($module_id, 'params'), true);
                $languages = Language::getLanguages();
                foreach($languages as $key => $language){
                    if($this->trl == $language['abbreviation']){
                        continue;
                    }
                    $data['params']['label_'.$language['abbreviation']]       = $params['label_'.$language['abbreviation']];
                    $data['params']['field_text_'.$language['abbreviation']]  = $params['field_text_'.$language['abbreviation']];
                    $data['params']['button_text_'.$language['abbreviation']] = $params['button_text_'.$language['abbreviation']];
                }
            }
            elseif($data['type'] == 'breadcrumb'){
                $params = json_decode(self::getDetails($module_id, 'params'), true);
                $languages = Language::getLanguages();
                foreach($languages as $key => $language){
                    if($this->trl == $language['abbreviation']){
                        continue;
                    }
                    $data['params']['text_'.$language['abbreviation']]  = $params['text_'.$language['abbreviation']];
                }
            }
            
            $data['updated_by'] =  $_SESSION['user_id'];
            $data['updated_on'] =  now(); 
        }
        
        $data['params'] = json_encode($data['params']);
        
        //echo print_r($data);
        return $data;

    }

    public function add()
    {

        $data = self::prepareData('', 'insert');

        $query = $this->db->insert_string('modules', $data);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_module'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_module_error'));
        }
        
        $module_id =$this->db->insert_id();
        
        $this->Custom_field->saveFieldsValues($module_id);
        
        return $module_id;

    }

    public function edit($module_id)
    {

        $data = self::prepareData($module_id, 'update');
        $where = "module_id = ".$module_id; 

        $query = $this->db->update_string('modules', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_module'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_module_error'));
        }
        
        $this->Custom_field->saveFieldsValues($module_id);
        
        return $module_id;

    }

    public function changeStatus($module_id, $status)
    {   

        $data['status'] = $status;
        $where = "module_id = ".$module_id;

        $query = $this->db->update_string('modules', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
        }

    }
    
    public function changeOrder($module_id, $order)
    {   
        
        $old_order   = self::getDetails($module_id, 'order');
        $category_id = self::getDetails($module_id, 'category_id');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND category_id = '".$category_id."'";
        $query1 = $this->db->update_string('modules', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "module_id = ".$module_id;
        $query2 = $this->db->update_string('modules', $data2, $where2);
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
        
        $modules = $this->input->post('modules');     
        foreach($modules as $module){
            
            $status = self::getDetails($module, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM modules WHERE module_id = '".$module."'");
            }
            else{
                $result = self::changeStatus($module, 'trash');
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