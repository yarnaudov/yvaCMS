<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner extends CI_Model {

    public function getDetails($banner_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('banner_id', $banner_id);

        $banner = $this->db->get('banners');  	
        $banner = $banner->result_array();

        if($field == null){
                return $banner[0];
        }
        else{  	
            return $banner[0][$field];
        }

    }
  
    public function getBanners($filters = array(), $order_by = "", $limit = "")
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
                $filter .= " AND (title like '%".$value."%' OR description like '%".$value."%')";
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
                        banners
                    WHERE
                        banner_id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $banners = $this->db->query($query)->result_array();

        return $banners;

    }
    
    public function getBannersByCategory($filters = array(), $order_by = "")
    {
        
        $banners = self::getBanners($filters, $order_by);
        
        foreach($banners as $banner){
            
            $banners_arr[$this->Category->getDetails($banner['category_id'], 'title_'.$this->Language->getDefault())][$banner['banner_id']] = $banner['title_'.$this->Language->getDefault()];
            
        }
        
        return $banners_arr;
        
    }
    
    public function getMaxOrder($category = "")
    {
        
        $category == "" ? $category = $this->input->post('category') : "";
        
        $this->db->select_max("`order`");
        $this->db->where("category_id", $category);
        $max_order = $this->db->get('banners')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count($category = "")
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        banners
                    WHERE
                        category_id = '".$category."'";
         
        //echo $query."<br/>";

        $banners = $this->db->query($query)->result_array();    

        return $banners[0]['count'];

    }
  
    public function prepareData($action)
    {
                 
        $data['title']            = $this->input->post('title');
        $data['description']      = $this->input->post('description');

        $data['category_id']      = $this->input->post('category');
        $data['status']           = $this->input->post('status');      
        $data['language_id']      = $this->input->post('language');
        $data['start_publishing'] = $this->input->post('start_publishing');
        $data['end_publishing']   = $this->input->post('end_publishing');
        $data['show_title']       = $this->input->post('show_title');
        $data['type']             = $this->input->post('type');
        $data['display_in']       = $this->input->post('display_in');
        $data['params']           = json_encode($this->input->post('params'));

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
            $data['updated_by'] =  $_SESSION['user_id'];
            $data['updated_on'] =  now(); 
        }

        //echo print_r($data);
        return $data;

    }

    public function add()
    {

        $data = self::prepareData('insert');

        $query = $this->db->insert_string('banners', $data);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_banner'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_banner_error'));
        }
        
        $banner_id = $this->db->insert_id();
        
        $this->Custom_field->saveFieldsValues($banner_id);
        
        return $banner_id;

    }

    public function edit($banner_id)
    {

        $data = self::prepareData('update');
        $where = "banner_id = ".$banner_id; 

        $query = $this->db->update_string('banners', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_banner'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_banner_error'));
        }
        
        $this->Custom_field->saveFieldsValues($banner_id);
        
        return $banner_id;

    }

    public function changeStatus($banner_id, $status)
    {   

        $data['status'] = $status;
        $where = "banner_id = ".$banner_id;

        $query = $this->db->update_string('banners', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
        }

    }
    
    public function changeOrder($banner_id, $order)
    {   
        
        $old_order   = self::getDetails($banner_id, 'order');
        $category_id = self::getDetails($banner_id, 'category_id');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND category_id = '".$category_id."'";
        $query1 = $this->db->update_string('banners', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "banner_id = ".$banner_id;
        $query2 = $this->db->update_string('banners', $data2, $where2);
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
        
        $banners = $this->input->post('banners');     
        foreach($banners as $banner){
            
            $status = self::getDetails($banner, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM banners WHERE banner_id = '".$banner."'");
            }
            else{
                $result = self::changeStatus($banner, 'trash');
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