<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
  
    public function getDetails($category_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('category_id', $category_id);

        $category = $this->db->get('categories');  	
        $category = $category->result_array();

        $category[0]['title']        = $category[0]['title_'.$this->trl];
        $category[0]['description']  = $category[0]['description_'.$this->trl];
        
        if($field == null){
                return $category[0];
        }
        else{  	
            return $category[0][$field];
        }

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
                        categories
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
         
        $this->load->helper('alias');
        
        $data['title_'.$this->trl]       = $this->input->post('title');
        $data['alias']                   = alias($this->input->post('alias'));
        $data['description_'.$this->trl] = $this->input->post('description');

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

        $query = $this->db->insert_string('categories', $data);
        //echo $query;
        $result = $this->db->query($query);
        
        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_category'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_category_error'));
        }
        
        return $this->db->insert_id();

    }

    public function edit($category_id)
    {

        $data = self::prepareData('update');
        $where = "category_id = ".$category_id; 

        $query = $this->db->update_string('categories', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_category'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_category_error'));
        }

        return $category_id;

    }
    
    public function changeStatus($category_id, $status)
    {   

        $data['status'] = $status;
        $where = "category_id = ".$category_id;

        $query = $this->db->update_string('categories', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
        }

    }
    
    public function changeOrder($category_id, $order)
    {   
        
        $old_order   = self::getDetails($category_id, 'order');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND extension = '".$this->extension."'";
        $query1 = $this->db->update_string('categories', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "category_id = ".$category_id;
        $query2 = $this->db->update_string('categories', $data2, $where2);
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