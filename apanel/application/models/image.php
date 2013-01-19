<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image extends CI_Model {

    public function getDetails($image_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('image_id', $image_id);

        $image = $this->db->get('images');  	
        $image = $image->result_array();

        if($field == null){
                return $image[0];
        }
        else{  	
            return $image[0][$field];
        }

    }
  
    public function getImages($filters = array(), $order_by = "", $limit = "")
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
            elseif($key == 'article'){
                $filter .= " AND article_id = '".$value."' ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        images
                    WHERE
                        image_id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $images = $this->db->query($query)->result_array();

        return $images;

    }
    
    public function getimagesByCategory($filters = array(), $order_by = "")
    {
        
        $images = self::getimages($filters, $order_by);
        
        foreach($images as $image){
            
            $images_arr[$this->Category->getDetails($image['category_id'], 'title_'.$this->Language->getDefault())][$image['image_id']] = $image['title_'.$this->Language->getDefault()];
            
        }
        
        return $images_arr;
        
    }
    
    public function getMaxOrder($category = "")
    {
        
        $category == "" ? $category = $this->input->post('category') : "";
        
        $this->db->select_max("`order`");
        $this->db->where("category_id", $category);
        $max_order = $this->db->get('images')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count($category = "")
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        images
                    WHERE
                        category_id = '".$category."'";
         
        //echo $query."<br/>";

        $images = $this->db->query($query)->result_array();    

        return $images[0]['count'];

    }
  
    public function prepareData($action)
    {
         
        $this->load->helper(array('alias', 'string'));
        
        $data['title_'.$this->trl]        = $this->input->post('title');
        $data['description_'.$this->trl]  = $this->input->post('description');

        $data['category_id']       = $this->input->post('category');
        $data['status']            = $this->input->post('status');      
        $data['language_id']       = $this->input->post('language');
        $data['article_id']        = $this->input->post('article');
        $data['ext']               =  end(explode(".", $_FILES["file"]["name"]));
        
        if($data['language_id'] == 'all'){
            $data['language_id'] = NULL;
        }
        if($data['article_id'] == ''){
            $data['article_id'] = NULL;
        }
        if($data['ext'] == ''){
            unset($data['ext']);
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

        $query = $this->db->insert_string('images', $data);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_image'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_image_error'));
        }
        
        $image_id = $this->db->insert_id();
        
        $this->Custom_field->saveFieldsValues($image_id);
        
        self::upload($image_id);
        
        return $image_id;

    }

    public function edit($image_id)
    {

        $data = self::prepareData('update');
        $where = "image_id = ".$image_id; 

        $query = $this->db->update_string('images', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_image'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_image_error'));
        }
        
        $this->Custom_field->saveFieldsValues($image_id);
        
        if($_FILES["file"]["size"] > 0){
            self::upload($image_id);
        }
        
        return $image_id;

    }

    public function changeStatus($image_id, $status)
    {   

        $data['status'] = $status;
        $where = "image_id = ".$image_id;

        $query = $this->db->update_string('images', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
        }

    }
    
    public function changeOrder($image_id, $order)
    {   
        
        $old_order   = self::getDetails($image_id, 'order');
        $category_id = self::getDetails($image_id, 'category_id');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND category_id = '".$category_id."'";
        $query1 = $this->db->update_string('images', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "image_id = ".$image_id;
        $query2 = $this->db->update_string('images', $data2, $where2);
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
        
        $images = $this->input->post('images');     
        foreach($images as $image){
            
            $status = self::getDetails($image, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM images WHERE image_id = '".$image."'");
            }
            else{
                $result = self::changeStatus($image, 'trash');
            }
            
            if($result != true){
                $this->db->query("ROLLBACK");
                return false;
            }
            
        }
        
        $this->db->query("COMMIT");
        return true;
        
    }
    
    public function upload($image_id){
        
        $this->load->helper('resizeImage');
        
        $images_dir = realpath(dirname(__FILE__).'/../../../').'/'.$this->config->item('images_dir');
        $thumbs_dir = realpath(dirname(__FILE__).'/../../../').'/'.$this->config->item('thumbs_dir');
        
        $ext = end(explode(".", $_FILES["file"]["name"]));

        if(!file_exists($images_dir)){
            mkdir($images_dir, 0777);
        }
        if(!file_exists($thumbs_dir)){
            mkdir($thumbs_dir, 0777);
        }
        
        
        resizeImage($_FILES['file']['tmp_name'], $images_dir.'/'.$image_id.'.'.$ext, $this->input->post('image_width'), $this->input->post('image_height'));
        resizeImage($images_dir.'/'.$image_id.".".$ext, $thumbs_dir.'/'.$image_id.'.'.$ext, $this->input->post('thumb_width'), $this->input->post('thumb_height'));
	      
        
    }
    
}