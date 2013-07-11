<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends MY_Model {
    
    private $menus;

    public function getDetails($id, $field = null)
    {

        $query = "SELECT 
                      *
                    FROM
                      menus m
                      LEFT JOIN menus_data md ON (m.id = md.menu_id AND md.language_id = '".$this->language_id."')
                    WHERE
                      m.id = '".$id."' ";
        
        $menu = $this->db->query($query);  	
        $menu = $menu->row_array();

        if(empty($menu)){
            return;
        }
        
        $menu['params'] = json_decode($menu['params'], true);   
        $menu           = array_merge($menu, $this->Custom_field->getFieldsValues($id));

        if($field == null){
            return $menu;
        }
        else{  	
            return $menu[$field];
        }

    }
    
    public function setFilters($filters, $order_by)
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash' "; 
        }
        
        if(substr_count($order_by, 'order')){
            $order_by = "category_id, ".$order_by;
        }      
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND ( title like '%".$value."%' OR description like '%".$value."%' OR alias like '%".$value."%' ) ";
            }
            elseif($key == 'category'){
                $filter .= " AND category_id = '".$value."' ";
            }
            elseif(preg_match('/^!{1}/', $value)){
                $filter .= " AND `".$key."` != '".preg_replace('/^!{1}/', '', $value)."' ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }
        
        return array('filter' => $filter, 'order_by' => $order_by);
        
    }
    
    public function checkChildren($id)
    {
        
        $query = "SELECT 
                      *
                    FROM
                      menus
                    WHERE
                      parent_id = '".$id."' ";
         
        //echo $query."<br/>";

        $menus = $this->db->query($query)->result_array();
        
        if(count($menus) > 0){
            return true;
        }
        else{
            return false;
        }
        
    }
    
    public function getChildren($id, $lavel, $filters = array(), $order_by = "`order`")
    {
        
        $filter_arr = self::setFilters($filters, $order_by);
        
        $query = "SELECT 
                      *
                    FROM
                      menus m
                      LEFT JOIN menus_data md ON (m.id = md.menu_id AND md.language_id = '".$this->language_id."')
                    WHERE
                      m.parent_id = '".$id."'                      
                      ".$filter_arr['filter']."
                    ORDER BY
                      ".$filter_arr['order_by']."";
         
        //echo $query."<br/>";

        $menus = $this->db->query($query)->result_array();
        foreach($menus as $menu){
            
            $menu['lavel']  = $lavel;
            $menu['params'] = json_decode($menu['params'], true);
            $this->menus[]  = $menu;
            
            if(self::checkChildren($menu['id']) == true){
                self::getChildren($menu['id'], ($lavel+1), $filters, $order_by);
            }
            
        }
        
    }
    
    public function getMenus($filters = array(), $order_by = "`order`")
    {
        
        $this->menus = array();
        
        $filter_arr = self::setFilters($filters, $order_by);

        $query = "SELECT 
                        *
                    FROM
                        menus m
                        LEFT JOIN menus_data md ON (m.id = md.menu_id AND md.language_id = '".$this->language_id."')
                    WHERE
                        m.parent_id IS NULL
                        ".$filter_arr['filter']."
                    ORDER BY
                        ".$filter_arr['order_by']."";
         
        //echo $query."<br/>";

        $menus = $this->db->query($query)->result_array();
        
        foreach($menus as $menu){
            
            $menu['lavel']  = 1;
            $menu['params'] = json_decode($menu['params'], true);
            $this->menus[]  = $menu;
            
            if(self::checkChildren($menu['id']) == true){
                self::getChildren($menu['id'], 2, $filters, $order_by);
            }
            
        }
        
        return $this->menus;

    }
    
    public function getMenusByCategory($filters = array(), $order_by = "`order`")
    {
        
        $menus = self::getMenus($filters, $order_by);
        
        foreach($menus as $menu){
            
            $lavel = "";
            for($i = 1; $i < $menu['lavel']; $i++){
                $lavel .= "- ";
            }
            
            $menus_arr[$this->Category->getDetails($menu['category_id'], 'title')][$menu['id']] = $lavel.$menu['title'];
            
        }
        
        return $menus_arr;
        
    }
    
    public function getForDropdown($filters = array(), $order_by = "`order`")
    {
        
        $menus = self::getMenus($filters, $order_by);
        
        $menus_arr = array();
        
        foreach($menus as $menu){
            $lavel = "";
            for($i = 1; $i < $menu['lavel']; $i++){
                $lavel .= "- ";
            }
            $menus_arr[$menu['id']] = $lavel.$menu['title'];
        }
        
        return $menus_arr;
        
    }
    
    public function getMaxOrder($category_id, $parent_id)
    {
        
        if($parent_id == NULL){
            $parent = "parent_id IS NULL";
        }
        else{
            $parent = "parent_id = '".$parent_id."'";
        }
        
        $query = "SELECT 
                        MAX(`order`) as `order`
                    FROM
                        menus
                    WHERE
                        category_id = '".$category_id."'
                      AND
                        ".$parent."";
         
        //echo $query."<br/>";
        //exit;

        $max_order = $this->db->query($query)->result_array();    

        return $max_order[0]['order'];

    }
    
    public function count($category_id, $parent_id)
    {
    
        if($parent_id == NULL){
            $parent = "parent_id IS NULL";
        }
        else{
            $parent = "parent_id = '".$parent_id."'";
        }
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        menus
                    WHERE
                        category_id = '".$category_id."'
                      AND
                        ".$parent."";
         
        //echo $query."<br/>";

        $menus = $this->db->query($query)->result_array();    

        return $menus[0]['count'];
        
    }
    
    public function prepareData($action)
    {
         
        $this->load->helper('alias');
        
        $data['menus_data']['title']                = $this->input->post('title');
        $data['menus_data']['description']          = $this->input->post('description');
        $data['menus_data']['meta_description']     = $this->input->post('meta_description');
        $data['menus_data']['meta_keywords']        = $this->input->post('meta_keywords');
        $data['menus_data']['language_id']          = $this->language_id;

        $data['menus']['alias']                     = alias($this->input->post('alias'));        
        $data['menus']['category_id']               = $this->input->post('category');
        $data['menus']['status']                    = $this->input->post('status');      
        $data['menus']['show_in_language']          = $this->input->post('show_in_language');
        $data['menus']['access']                    = $this->input->post('access');
        $data['menus']['target']                    = $this->input->post('target');
        $data['menus']['parent_id']                 = $this->input->post('parent');
        $data['menus']['image']                     = $this->input->post('image');
        $data['menus']['main_template']             = $this->input->post('main_template');
        $data['menus']['content_template']          = $this->input->post('content_template');
        $data['menus']['default']                   = $this->input->post('default');
        $data['menus']['description_as_page_title'] = $this->input->post('description_as_page_title');
	$data['menus']['type']                      = $this->input->post('type');
        $data['menus']['params']                    = json_encode($this->input->post('params'));
        
        if($data['menus']['default'] == ""){
            unset($data['menus']['default']);
        }

        if($data['menus']['show_in_language'] == 'all'){
            $data['menus']['show_in_language'] = NULL;
        }
        
        if($data['menus']['parent_id'] == 'none'){
            $data['menus']['parent_id'] = NULL;
        }

        if($action == 'insert'){
            $data['menus']['order']      =  self::getMaxOrder($data['menus']['category_id'], $data['menus']['parent_id'])+1;
            $data['menus']['created_by'] =  $_SESSION['user_id'];
            $data['menus']['created_on'] =  now();        
        }
        elseif($action == 'update'){
            $data['menus']['updated_by'] =  $_SESSION['user_id'];
            $data['menus']['updated_on'] =  now(); 
        }

        //print_r($data);
        //exit;
        
        return $data;

    }

    public function add()
    {

        $data = self::prepareData('insert');
        
        $this->db->query('BEGIN');
        
        if($data['menus']['default'] == 'yes'){
            $query = $this->db->update_string('menus', array('default' => 'no'), "`default` = 'yes'");
            $this->db->query($query);
        }
        
        // save data in menus table
        $query = $this->db->insert_string('menus', $data['menus']);
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_menu_error'));
            $this->db->query('ROLLBACK');
            return;
        }
        
        $id = $this->db->insert_id();
        
        // save data in menus_data table
        $data['menus_data']['menu_id'] = $id;
        $query = $this->db->insert_string('menus_data', $data['menus_data']);
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_menu_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }

        // save custom fields data
        $result = $this->Custom_field->saveFieldsValues($id);
        if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_menu_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        $this->session->set_userdata('good_msg', lang('msg_save_menu'));
        $this->db->query('COMMIT');
        return $id;

    }

    public function edit($id)
    {
   
        $data = self::prepareData('update');
        
        $this->db->query('BEGIN');
        
        if(isset($data['menus']['default']) && $data['menus']['default'] == 'yes'){
            $query = $this->db->update_string('menus', array('default' => 'no'), "`default` = 'yes'");
            $this->db->query($query);
        }
        
        $where = "id = ".$id;
        $query = $this->db->update_string('menus', $data['menus'], $where);
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_menu_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        // save data in menus_data table
        if(parent::_dataExists('menus_data', 'menu_id', $id) == 0){
            $data['menus_data']['menu_id'] = $id;
            $query = $this->db->insert_string('menus_data', $data['menus_data']);
        }
        else{            
            $where = "menu_id = ".$id." AND language_id = ".$this->language_id." ";
            $query = $this->db->update_string('menus_data', $data['menus_data'], $where);            
        }        
        $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_menu_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        // save custom fields data
        $result = $this->Custom_field->saveFieldsValues($id);
        if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_menu_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        $this->session->set_userdata('good_msg', lang('msg_save_menu'));
        $this->db->query('COMMIT');
        return $id;

    }
    
    public function delete()
    {
        
        $this->db->query("BEGIN");
        
        $menus = $this->input->post('menus');     
        foreach($menus as $menu){
            
            $menu = self::getDetails($menu);
            
            if($menu['default'] == 'yes'){
                $this->session->set_userdata('error_msg', lang('msg_delete_default_menu_error'));
                $result = true;
            }            
            elseif($menu['status'] == 'trash'){
                $result = true;//$this->db->simple_query("DELETE FROM menus WHERE id = '".$menu."'");
            }
            else{
                $result = self::changeStatus($menu['id'], 'trash');
            }
            
            if($result != true){
                $this->db->query("ROLLBACK");
                return false;
            }
            
        }
        
        $this->db->query("COMMIT");
        return true;
        
    }
    
    public function copy()
    {
        
        $this->db->query("BEGIN");
        
        $menus = $this->input->post('menus');     
        foreach($menus as $menu_id){
            
	    $menu = $this->db->get_where('menus', array('id' => $menu_id))->row_array();
	    
	    $menu['alias'] = $menu['alias']."_copy";
	    $menu['order'] = self::getMaxOrder($menu['category_id'], $menu['parent_id'])+1;
	    $menu['created_by'] = $_SESSION['user_id'];
            $menu['created_on'] = now();
	    unset($menu['id'], $menu['updated_by'], $menu['updated_on']);
	    
            $result = $this->db->insert('menus', $menu);                        
            if($result != true){
		$this->session->set_userdata('error_msg', lang('msg_copy_menu_error'));
                $this->db->query("ROLLBACK");
                return false;
            }
	    
	    $id = $this->db->insert_id();
            
	    $menu_data = $this->db->get_where('menus_data', array('menu_id' => $menu_id))->result_array();
	    foreach($menu_data as $data){
		$data['menu_id'] = $id;
		$result = $this->db->insert('menus_data', $data);                        
		if($result != true){
		    $this->session->set_userdata('error_msg', lang('msg_copy_menu_error'));
		    $this->db->query("ROLLBACK");
		    return false;
		}
	    }
	    
	    $custom_fields = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
	    foreach($custom_fields as $custom_field){
		
		$custom_field_data = $this->db->get_where('custom_fields_values', array('custom_field_id' => $custom_field['id'], 'element_id' => $menu_id))->result_array();
		
		foreach($custom_field_data as $data){
		    
		    $data['element_id'] = $id;
		    
		    $result = $this->db->insert('custom_fields_values', $data);                        
		    if($result != true){
			$this->session->set_userdata('error_msg', lang('msg_copy_menu_error'));
			$this->db->query("ROLLBACK");
			return false;
		    }
		
		}
		
	    }
	    
        }
        
	$this->session->set_userdata('good_msg', lang('msg_copy_menu'));
        $this->db->query("COMMIT");
        return true;
        
    }
    
    public function changeStatus($id, $status)
    {   

        $data['status'] = $status;
        $where = "id = ".$id;

        $query = $this->db->update_string('menus', $data, $where);
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
        $category_id = self::getDetails($id, 'category_id');
        $parent_id   = self::getDetails($id, 'parent_id');
        
        $parent = $parent_id == NULL ? "parent_id IS NULL" : "parent_id = '".$parent_id."'";
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND category_id = '".$category_id."' AND ".$parent."";
        $query1 = $this->db->update_string('menus', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "id = ".$id;
        $query2 = $this->db->update_string('menus', $data2, $where2);
        //echo $query2;
        $result2 = $this->db->query($query2);
        
        if($result1 == true && $result2 == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_order_error'));
        }

    }
    
    public function makeDefault($id)
    {   

        $this->db->query("BEGIN");
        
        $query = $this->db->update_string('menus', array('default' => 'no'), "`default` = 'yes'");
        $result = $this->db->query($query);
        if($result != true){
            $this->db->query("ROLLBACK");
            return false;
        }
        
        $query = $this->db->update_string('menus', array('default' => 'yes'), "id = ".$id);
        $result = $this->db->query($query);
        if($result != true){
            $this->db->query("ROLLBACK");
            return false;
        }
        
        $this->db->query("COMMIT");
        return true;

    }
    
}