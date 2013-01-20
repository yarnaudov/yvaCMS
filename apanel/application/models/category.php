<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Model {

    function __construct()
    {
        parent::__construct();
    }
  
    public function getDetails($id, $field = null)
    {

        $query = "SELECT 
                      *
                    FROM
                      categories c
                      LEFT JOIN categories_data cd ON (c.id = cd.category_id AND cd.language_id = '".$this->trl."')
                    WHERE
                      c.id = '".$id."' ";
        
        $category = $this->db->query($query);  	
        $category = $category->result_array();
        
        if(empty($category)){
            return;
        }
        
        if($field == null){
            return $category[0];
        }
        else{  	
            return $category[0][$field];
        }

    }
    
    public function getForDropdown()
    {
        
        $query = "SELECT 
                      c.id,
                      cd.title
                    FROM
                      categories c
                      JOIN categories_data cd ON (c.id = cd.category_id)
                    WHERE
                      c.extension = '".$this->extension."'
                     AND
                      cd.language_id = ".$this->trl."
                     AND 
                      c.status = 'yes'
                    ORDER BY c.`order`";
    
        $categories = $this->db->query($query)->result_array();
        
        $categories_arr = array();
        foreach($categories as $category){
            $categories_arr[$category['id']] = $category['title'];
        }
        
        return $categories_arr;
        
    }
    
    public function getCategories($filters = array(), $order_by = "", $limit = "")
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
                        categories c
                        LEFT JOIN categories_data cd ON (c.id = cd.category_id AND cd.language_id = ".$this->trl.")
                    WHERE
                        extension = '".$this->extension."'
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $categories = $this->db->query($query)->result_array();

        return $categories;

    }
    
    public function getMaxOrder()
    {
                
        $this->db->select_max("`order`");
        $this->db->where("extension", $this->extension);
        $max_order = $this->db->get('categories')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function prepareData($action)
    {
                 
        $data['categories_data']['title']       = $this->input->post('title');
        $data['categories_data']['description'] = $this->input->post('description');
        $data['categories_data']['language_id'] = $this->trl;
        
        if($action == 'insert'){
            $data['categories']['extension']    = $this->extension;
            $data['categories']['order']        =  self::getMaxOrder()+1;
            $data['categories']['created_by']   =  $_SESSION['user_id'];
            $data['categories']['created_on']   =  now();        
        }
        elseif($action == 'update'){
            $data['categories']['updated_by']   =  $_SESSION['user_id'];
            $data['categories']['updated_on']   =  now(); 
        }

        return $data;

    }

    public function add()
    {

        $data = self::prepareData('insert');
        
        $this->db->query('BEGIN');
        
        // save data in categories table
        $query = $this->db->insert_string('categories', $data['categories']);
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_category_error'));
            $this->db->query('ROLLBACK');
            return;
        }
        
        $id = $this->db->insert_id();
        
        // save data in categories_data table
        $data['categories_data']['category_id'] = $id;
        $query = $this->db->insert_string('categories_data', $data['categories_data']);
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_category_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        $this->session->set_userdata('good_msg', lang('msg_save_category'));
        $this->db->query('COMMIT');
        return $this->db->insert_id();

    }

    public function edit($id)
    {

        $data = self::prepareData('update');
                
        $this->db->query('BEGIN');
        
        // save data in categories table
        $where = "id = ".$id; 
        $query = $this->db->update_string('categories', $data['categories'], $where);
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_category_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }

        // save data in categories_data table
        if(parent::_dataExists('categories_data', 'category_id', $id) == 0){
            $data['categories_data']['category_id'] = $id;
            $query = $this->db->insert_string('categories_data', $data['categories_data']);
        }
        else{            
            $where = "category_id = ".$id." AND language_id = ".$this->trl." ";
            $query = $this->db->update_string('categories_data', $data['categories_data'], $where);            
        }        
        $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_category_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        $this->session->set_userdata('good_msg', lang('msg_save_category'));
        $this->db->query('COMMIT');
        return $id;

    }
    
    public function changeStatus($id, $status)
    {   

        $data['status'] = $status;
        $where = "id = ".$id;

        $query = $this->db->update_string('categories', $data, $where);
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
        }

        return true;
        
    }
    
    public function changeOrder($id, $order)
    {   
        
        $this->db->query('BEGIN');
        
        $old_order = self::getDetails($id, 'order');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1  = "`order` = ".$new_order." AND extension = '".$this->extension."'";
        $query1  = $this->db->update_string('categories', $data1, $where1);
        $result1 = $this->db->query($query1);
        if($result1 != true){
            $this->session->set_userdata('error_msg', lang('msg_order_error'));
            $this->db->query('ROLLBACK');
            return;
        }        
        
        $data2['order'] = $new_order;
        $where2  = "category_id = ".$category_id;
        $query2  = $this->db->update_string('categories', $data2, $where2);
        $result2 = $this->db->query($query2);
        if($result2 != true){
            $this->session->set_userdata('error_msg', lang('msg_order_error'));
            $this->db->query('ROLLBACK');
            return $id; 
        }

        $this->db->query('COMMIT');
        return true;

    }
    
    public function delete()
    {
        
        $this->db->query("BEGIN");
        
        $categories = $this->input->post('categories');     
        foreach($categories as $category){
            
            $status = self::getDetails($category, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM categories WHERE category_id = '".$category."'");
            }
            else{
                $result = self::changeStatus($category, 'trash');
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