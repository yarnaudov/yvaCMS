<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Model {
    
    private $menus;

    public function getDetails($menu_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('menu_id', $menu_id);

        $menu = $this->db->get('menus');  	
        $menu = $menu->result_array();

        if($field == null){
                return $menu[0];
        }
        else{  	
            return $menu[0][$field];
        }

    }
    
    public function setFilters($filters, $order_by)
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
                                description_".$language['abbreviation']."  like '%".$value."%'";
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
        
        return array('filter' => $filter, 'order_by' => $order_by);
        
    }
    
    public function checkChildren($menu_id)
    {
        
        $query = "SELECT 
                        *
                    FROM
                        menus
                    WHERE
                        parent_id = '".$menu_id."' ";
         
        //echo $query."<br/>";

        $menus = $this->db->query($query)->result_array();
        
        if(count($menus) > 0){
            return true;
        }
        else{
            return false;
        }
        
    }
    
    public function getChildren($menu_id, $lavel, $filters = array(), $order_by = "")
    {
        
        $filter_arr = self::setFilters($filters, $order_by);
        
        $query = "SELECT 
                        *
                    FROM
                        menus
                    WHERE
                        parent_id = '".$menu_id."'                      
                        ".$filter_arr['filter']."
                    ORDER BY
                        ".$filter_arr['order_by']."";
         
        //echo $query."<br/>";

        $menus = $this->db->query($query)->result_array();
        foreach($menus as $menu){
            
            $menu['lavel'] = $lavel;
            $this->menus[] = $menu;
            
            if(self::checkChildren($menu['menu_id']) == true){
                self::getChildren($menu['menu_id'], ($lavel+1), $filters, $order_by);
            }
            
        }
        
    }
    
    public function getMenus($filters = array(), $order_by = "")
    {
        
        $this->menus = array();
        
        $filter_arr = self::setFilters($filters, $order_by);

        $query = "SELECT 
                        *
                    FROM
                        menus
                    WHERE
                        parent_id IS NULL
                        ".$filter_arr['filter']."
                    ORDER BY
                        ".$filter_arr['order_by']."";
         
        //echo $query."<br/>";

        $menus = $this->db->query($query)->result_array();
        
        foreach($menus as $menu){
            
            $menu['lavel'] = 1;
            $this->menus[] = $menu;
            
            if(self::checkChildren($menu['menu_id']) == true){
                self::getChildren($menu['menu_id'], 2, $filters, $order_by);
            }
            
        }
        
        return $this->menus;

    }
    
    public function getMenusByCategory($filters = array(), $order_by = "")
    {
        
        $menus = self::getMenus($filters, $order_by);
        
        foreach($menus as $menu){
            
            $lavel = "";
            for($i = 1; $i < $menu['lavel']; $i++){
                $lavel .= "- ";
            }
            
            $menus_arr[$this->Category->getDetails($menu['category_id'], 'title_'.$this->Language->getDefault())][$menu['menu_id']] = $lavel.$menu['title_'.$this->Language->getDefault()];
            
        }
        
        return $menus_arr;
        
    }
    
    public function dropdownListArrray($menus)
    {
        
        $menus_arr = array();
        
        foreach($menus as $menu){
            $lavel = "";
            for($i = 1; $i < $menu['lavel']; $i++){
                $lavel .= "- ";
            }
            $menus_arr[$menu['menu_id']] = $lavel.$menu['title_'.Language::getDefault()];
        }
        
        return $menus_arr;
        
    }
    
    public function getMaxOrder($category = "", $parent = "")
    {
        
        $category == "" ? $category = $this->input->post('category') : "";
        $parent   == "" ? $parent   = $this->input->post('parent') : "";
        
        if($parent == "none"){
            $parent = "parent_id IS NULL";
        }
        else{
            $parent = "parent_id = '".$parent."'";
        }
        
        $query = "SELECT 
                        MAX(`order`) as `order`
                    FROM
                        menus
                    WHERE
                        category_id = '".$category."'
                      AND
                        ".$parent."";
         
        //echo $query."<br/>";

        $max_order = $this->db->query($query)->result_array();    

        return $max_order[0]['order'];

    }
    
    public function count($category = "", $parent = "")
    {
    
        if($parent == NULL){
            $parent = "parent_id IS NULL";
        }
        else{
            $parent = "parent_id = '".$parent."'";
        }
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        menus
                    WHERE
                        category_id = '".$category."'
                      AND
                        ".$parent."";
         
        //echo $query."<br/>";

        $menus = $this->db->query($query)->result_array();    

        return $menus[0]['count'];
        
    }
    
    public function prepareData($action)
    {
         
        $this->load->helper('alias');
        
        $data['title_'.$this->trl]            = $this->input->post('title');
        $data['alias']                        = alias($this->input->post('alias'));
        $data['description_'.$this->trl]      = $this->input->post('description');
        $data['meta_description_'.$this->trl] = $this->input->post('meta_description');
        $data['meta_keywords_'.$this->trl]    = $this->input->post('meta_keywords');

        $data['category_id']       = $this->input->post('category');
        $data['status']            = $this->input->post('status');      
        $data['language_id']       = $this->input->post('language');
        $data['access']            = $this->input->post('access');
        $data['target']            = $this->input->post('target');
        $data['parent_id']         = $this->input->post('parent');
        $data['type']              = $this->input->post('type');
        $data['params']            = json_encode($this->input->post('params'));
        
        $data['default']           = $this->input->post('default');
        if($data['default'] == ""){
            unset($data['default']);
        }

        if($data['language_id'] == 'all'){
            $data['language_id'] = NULL;
        }
        
        if($data['parent_id'] == 'none'){
            $data['parent_id'] = NULL;
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
        
        if($data['default'] == 'yes'){
            $query = $this->db->update_string('menus', array('default' => 'no'), "`default` = 'yes'");
            $this->db->query($query);
        }
        
        $query = $this->db->insert_string('menus', $data);
        //echo $query;
        $result = $this->db->query($query);
        
        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_menu'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_menu_error'));
        }
        
        $menu_id = $this->db->insert_id();
        
        $this->Custom_field->saveFieldsValues($menu_id, $this->trl);
        
        return $menu_id;

    }

    public function edit($menu_id)
    {
   
        $data = self::prepareData('update');
        
        if(isset($data['default']) && $data['default'] == 'yes'){
            $query = $this->db->update_string('menus', array('default' => 'no'), "`default` = 'yes'");
            $this->db->query($query);
        }
        
        $where = "menu_id = ".$menu_id; 

        $query = $this->db->update_string('menus', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_menu'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_menu_error'));
        }
        
        $this->Custom_field->saveFieldsValues($menu_id);
        
        return $menu_id;

    }
    
    public function delete()
    {
        
        $this->db->query("BEGIN");
        
        $menus = $this->input->post('menus');     
        foreach($menus as $menu){
            
            $status = self::getDetails($menu, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM menus WHERE menu_id = '".$menu."'");
            }
            else{
                $result = self::changeStatus($menu, 'trash');
            }
            
            if($result != true){
                $this->db->query("ROLLBACK");
                return false;
            }
            
        }
        
        $this->db->query("COMMIT");
        return true;
        
    }
    
    public function changeStatus($menu_id, $status)
    {   

        $data['status'] = $status;
        $where = "menu_id = ".$menu_id;

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
    
    public function changeOrder($menu_id, $order)
    {   
        
        $old_order   = self::getDetails($menu_id, 'order');
        $category_id = self::getDetails($menu_id, 'category_id');
        $parent_id   = self::getDetails($menu_id, 'parent_id');
        
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
        $where2 = "menu_id = ".$menu_id;
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
    
}