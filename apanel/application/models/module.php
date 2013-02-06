<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends MY_Model {

    public function getDetails($id, $field = null)
    {

        $query = "SELECT 
                      *
                    FROM
                      modules m
                      LEFT JOIN modules_data md ON (m.id = md.module_id AND md.language_id = '".$this->language_id."')
                    WHERE
                      m.id = '".$id."' ";
        
        $module = $this->db->query($query);  	
        $module = $module->result_array();

        $module[0]['params'] = json_decode($module[0]['params'], true); 
        $module[0]           = array_merge($module[0], $this->Custom_field->getFieldsValues($id));
        
        if(empty($module)){
            return;
        }

        if($field == null){
            return $module[0];
        }
        else{  	
            return $module[0][$field];
        }

    }
  
    public function getModules($filters = array(), $order_by = "`order`", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }
                            
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND ( title like '%".$value."%' OR description like '%".$value."%' ) ";
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
                        modules m
                        LEFT JOIN modules_data md ON (m.id = md.module_id AND md.language_id = ".$this->language_id.")
                    WHERE
                        m.id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $modules = $this->db->query($query)->result_array();

        foreach($modules as $key => $module){
            $modules[$key]['params'] = json_decode($module['params'], true);
            $modules[$key]           = array_merge($modules[$key], $this->Custom_field->getFieldsValues($module['id']));
        }
        
        return $modules;

    }
    
    public function getModulesByCategory($filters = array(), $order_by = "")
    {
        
        $modules = self::getModules($filters, $order_by);
        
        foreach($modules as $module){
            
            $modules_arr[$this->Category->getDetails($module['category_id'], 'title_'.$this->Language->getDefault())][$module['id']] = $module['title_'.$this->Language->getDefault()];
            
        }
        
        return $modules_arr;
        
    }
    
    public function getPositions($positions = array())
    {
        
        $query = "SELECT position FROM modules WHERE `status` != 'trash'; ";
        
        $modules = $this->db->query($query)->result_array();

        foreach($modules as $key => $module){
            if(!in_array($module['position'], $positions)){
                $positions[$module['position']] = $module['position'];
            }
        }
        
        return $positions;
        
    }
    
    public function getMaxOrder($position)
    {
        
        $this->db->select_max("`order`");
        $this->db->where("position", $position);
        $max_order = $this->db->get('modules')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count($position)
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        modules
                    WHERE
                        position = '".$position."'";
         
        //echo $query."<br/>";

        $modules = $this->db->query($query)->result_array();    

        return $modules[0]['count'];

    }
  
    public function prepareData($id, $action)
    {
        
        $data['modules_data']['title']       = $this->input->post('title');
        $data['modules_data']['description'] = $this->input->post('description');
        $data['modules_data']['language_id'] = $this->language_id;

        $data['modules']['position']         = $this->input->post('position');
        $data['modules']['status']           = $this->input->post('status');      
        $data['modules']['show_in_language'] = $this->input->post('show_in_language');
        $data['modules']['start_publishing'] = $this->input->post('start_publishing');
        $data['modules']['end_publishing']   = $this->input->post('end_publishing');
        $data['modules']['access']           = $this->input->post('access');
        $data['modules']['css_class_sufix']  = $this->input->post('css_class_sufix');
        $data['modules']['params']           = $this->input->post('params');

        if($data['modules']['show_in_language'] == 'all'){
            $data['modules']['show_in_language'] = NULL;
        }
        if(empty($data['modules']['start_publishing'])){
            $data['modules']['start_publishing'] = NULL;
        }
        if(empty($data['modules']['end_publishing'])){
            $data['modules']['end_publishing'] = NULL;
        }

        if($action == 'insert'){
            $data['modules']['order']      =  self::getMaxOrder($data['modules']['position'])+1;
            $data['modules']['created_by'] =  $_SESSION['user_id'];
            $data['modules']['created_on'] =  now();        
        }
        elseif($action == 'update'){
            
            $params = self::getDetails($id, 'params');
            if($params['type'] == $data['modules']['params']['type']){
                foreach($data['modules']['params'] as $key1 => $value1){
                    if(!is_array($value1) || $key1 == 'display_menus'){
                        $params[$key1] = $value1;
                    }
                    else{
                        foreach($value1 as $key2 => $value2){
                            $params[$key1][$key2] = $value2;
                        }
                    }                    
                }
                
                $data['modules']['params'] = $params;
                
            }
            
            $data['modules']['updated_by'] =  $_SESSION['user_id'];
            $data['modules']['updated_on'] =  now(); 
        }
        
        $data['modules']['params'] = json_encode($data['modules']['params']);
        
        //print_r($this->input->post('params'));
        //echo print_r($data);
        //exit;
        
        return $data;

    }

    public function add()
    {

        $data = self::prepareData('', 'insert');

        $this->db->query('BEGIN');
        
        // save data in modules table
        $query = $this->db->insert_string('modules', $data['modules']);
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_module_error'));
            $this->db->query('ROLLBACK');
            return;
        }
        
        $id = $this->db->insert_id();
        
        // save data in modules_data table
        $data['modules_data']['module_id'] = $id;
        $query = $this->db->insert_string('modules_data', $data['modules_data']);
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_module_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        // save custom fields data
        $result = $this->Custom_field->saveFieldsValues($id);
        if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_module_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        $this->session->set_userdata('good_msg', lang('msg_save_module'));
        $this->db->query('COMMIT');
        return $id;

    }

    public function edit($id)
    {

        $data = self::prepareData($id, 'update');
        
        $this->db->query('BEGIN');
        
        // save data in modules table
        $where = "id = ".$id; 
        $query = $this->db->update_string('modules', $data['modules'], $where);        
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_module_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        // save data in modules_data table
        if(parent::_dataExists('modules_data', 'module_id', $id) == 0){
            $data['modules_data']['module_id'] = $id;
            $query = $this->db->insert_string('modules_data', $data['modules_data']);
        }
        else{            
            $where = "module_id = ".$id." AND language_id = ".$this->language_id." ";
            $query = $this->db->update_string('modules_data', $data['modules_data'], $where);            
        }        
        $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_module_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        // save custom fields data
        $result = $this->Custom_field->saveFieldsValues($id);
        if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_module_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }        
        
        $this->session->set_userdata('good_msg', lang('msg_save_module'));
        $this->db->query('COMMIT');
        return $id;
        
    }

    public function changeStatus($id, $status)
    {   

        $data['status'] = $status;
        $where = "id = ".$id;

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
    
    public function changeOrder($id, $order)
    {   
        
        $old_order = self::getDetails($id, 'order');
        $position  = self::getDetails($id, 'position');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND position = '".$position."'";
        $query1 = $this->db->update_string('modules', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "id = ".$id;
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
                $result = $this->db->simple_query("DELETE FROM modules WHERE id = '".$module."'");
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