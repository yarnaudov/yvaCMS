<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image extends MY_Model {

    public function getDetails($id, $field = null)
    {

        $query = "SELECT 
                      *
                    FROM
                      com_gallery_images cgi
                      LEFT JOIN com_gallery_images_data cgid ON (cgi.id = cgid.image_id AND cgid.language_id = '".$this->trl."')
                    WHERE
                      cgi.id = '".$id."' ";
        
        $image = $this->db->query($query);  	
        $image = $image->result_array();

        if(empty($image)){
            return;
        }

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
            $order_by = "album_id, ".$order_by;
        }      
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND ( title like '%".$value."%' OR description like '%".$value."%' ) ";
            }
            elseif($key == 'album'){
                $filter .= " AND album_id = '".$value."' ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        com_gallery_images cgi
                        LEFT JOIN com_gallery_images_data cgid ON (cgi.id = cgid.image_id AND cgid.language_id = '".$this->trl."')
                    WHERE
                        cgi.id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $images = $this->db->query($query)->result_array();

        return $images;

    }
    
    public function getMaxOrder($album_id)
    {
        
        $this->db->select_max("`order`");
        $this->db->where("album_id", $album_id);
        $max_order = $this->db->get('com_gallery_images')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count($album_id)
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        com_gallery_images
                    WHERE
                        album_id = '".$album_id."'";
         
        //echo $query."<br/>";

        $images = $this->db->query($query)->result_array();    

        return $images[0]['count'];

    }
  
    public function prepareData($action)
    {
                 
        $data['com_gallery_images_data']['title']       = $this->input->post('title');
        $data['com_gallery_images_data']['description'] = $this->input->post('description');
        $data['com_gallery_images_data']['language_id'] = $this->trl;

        $data['com_gallery_images']['album_id']         = $this->input->post('album');
        $data['com_gallery_images']['status']           = $this->input->post('status');      
        $data['com_gallery_images']['show_in_language'] = $this->input->post('show_in_language');
        $data['com_gallery_images']['ext']              =  end(explode(".", $_FILES["file"]["name"]));
        
        if($data['com_gallery_images']['show_in_language'] == 'all'){
            $data['com_gallery_images']['show_in_language'] = NULL;
        }
        if($data['com_gallery_images']['ext'] == ''){
            unset($data['com_gallery_images']['ext']);
        }

        if($action == 'insert'){            
            $data['com_gallery_images']['order']      =  self::getMaxOrder()+1;
            $data['com_gallery_images']['created_by'] =  $_SESSION['user_id'];
            $data['com_gallery_images']['created_on'] =  now();        
        }
        elseif($action == 'update'){            
            $data['com_gallery_images']['updated_by'] =  $_SESSION['user_id'];
            $data['com_gallery_images']['updated_on'] =  now(); 
        }

        return $data;

    }

    public function add()
    {      
        
        $data = self::prepareData('insert');

        $this->db->query('BEGIN');
        
        // save data in com_gallery_images table
        $query = $this->db->insert_string('com_gallery_images', $data['com_gallery_images']);
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_image_error'));
            $this->db->query('ROLLBACK');
            return;
        }
        
        $id = $this->db->insert_id();
        
        // save data in com_gallery_images_data table
        $data['com_gallery_images_data']['image_id'] = $id;
        $query = $this->db->insert_string('com_gallery_images_data', $data['com_gallery_images_data']);
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_image_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
                
        self::upload($id);
        
        $this->session->set_userdata('good_msg', lang('msg_save_image'));
        $this->db->query('COMMIT');
        return $id;

    }

    public function edit($id)
    {

        $data = self::prepareData('update');
        
        $this->db->query('BEGIN');
        
        // save data in com_gallery_images table
        $where = "id = ".$id; 
        $query = $this->db->update_string('com_gallery_images', $data['com_gallery_images'], $where);        
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_image_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        // save data in com_gallery_images_data table
        if(parent::_dataExists('com_gallery_images_data', 'image_id', $id) == 0){
            $data['com_gallery_images_data']['image_id'] = $id;
            $query = $this->db->insert_string('com_gallery_images_data', $data['com_gallery_images_data']);
        }
        else{            
            $where = "image_id = ".$id." AND language_id = ".$this->trl." ";
            $query = $this->db->update_string('com_gallery_images_data', $data['com_gallery_images_data'], $where);            
        }        
        $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_image_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
            
        if($_FILES["file"]["size"] > 0){
            self::upload($id);
        }
        
        $this->session->set_userdata('good_msg', lang('msg_save_image'));
        $this->db->query('COMMIT');
        return $id;

    }

    public function changeStatus($id, $status)
    {   

        $data['status'] = $status;
        $where = "id = ".$id;

        $query = $this->db->update_string('com_gallery_images', $data, $where);
        $result = $this->db->query($query);

        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
            return false;
        }
        
        return true;

    }
    
    public function changeOrder($id, $order)
    {   
        
        $old_order = self::getDetails($id, 'order');
        $album_id  = self::getDetails($id, 'album_id');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND album_id = '".$album_id."'";
        $query1 = $this->db->update_string('com_gallery_images', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "id = ".$id;
        $query2 = $this->db->update_string('com_gallery_images', $data2, $where2);
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
                $result = $this->db->simple_query("DELETE FROM com_gallery_images WHERE id = '".$image."'");
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
    
    public function upload($id)
    {
        
        $this->load->helper('resizeImage');
        
        $images_dir = FCPATH.'../'.$this->config->item('images_dir');
        $thumbs_dir = FCPATH.'../'.$this->config->item('thumbs_dir');
        
        $ext = end(explode(".", $_FILES["file"]["name"]));

        if(!file_exists($images_dir)){
            mkdir($images_dir, 0777);
        }
        if(!file_exists($thumbs_dir)){
            mkdir($thumbs_dir, 0777);
        }
        
        
        resizeImage($_FILES['file']['tmp_name'], $images_dir.'/'.$id.'.'.$ext, $this->input->post('image_width'), $this->input->post('image_height'));
        resizeImage($images_dir.'/'.$id.".".$ext, $thumbs_dir.'/'.$id.'.'.$ext, $this->input->post('thumb_width'), $this->input->post('thumb_height'));
	      
        
    }
    
}