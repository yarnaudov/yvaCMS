<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Album extends CI_Model {

    public function getDetails($album_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('album_id', $album_id);

        $album = $this->db->get('com_gallery_albums');  	
        $album = $album->result_array();

        if($field == null){
                return $album[0];
        }
        else{  	
            return $album[0][$field];
        }

    }
  
    public function getAlbums($filters = array(), $order_by = "", $limit = "")
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
                        com_gallery_albums
                    WHERE
                        album_id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $albums = $this->db->query($query)->result_array();

        return $albums;

    }
    
    public function getMaxOrder()
    {
                
        $this->db->select_max("`order`");
        $max_order = $this->db->get('com_gallery_albums')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count()
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        com_gallery_albums";
         
        //echo $query."<br/>";

        $albums = $this->db->query($query)->result_array();    

        return $albums[0]['count'];

    }
  
    public function prepareData($action)
    {
                 
        $data['title_'.$this->trl]        = $this->input->post('title');
        $data['description_'.$this->trl]  = $this->input->post('description');

        $data['status']            = $this->input->post('status');      
        $data['language_id']       = $this->input->post('language');
        
        if($data['language_id'] == 'all'){
            $data['language_id'] = NULL;
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

        $query = $this->db->insert_string('com_gallery_albums', $data);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_album'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_album_error'));
        }
        
        $album_id = $this->db->insert_id();
                
        return $album_id;

    }

    public function edit($album_id)
    {

        $data = self::prepareData('update');
        $where = "album_id = ".$album_id; 

        $query = $this->db->update_string('com_gallery_albums', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_album'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_album_error'));
        }
                   
        return $album_id;

    }

    public function changeStatus($album_id, $status)
    {   

        $data['status'] = $status;
        $where = "album_id = ".$album_id;

        $query = $this->db->update_string('com_gallery_albums', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            return true; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
        }

    }
    
    public function changeOrder($album_id, $order)
    {   
        
        $old_order   = self::getDetails($album_id, 'order');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order;
        $query1 = $this->db->update_string('com_gallery_albums', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "album_id = ".$album_id;
        $query2 = $this->db->update_string('com_gallery_albums', $data2, $where2);
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
        
        $albums = $this->input->post('albums');     
        foreach($albums as $album){
            
            $status = self::getDetails($album, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM com_gallery_albums WHERE album_id = '".$album."'");
            }
            else{
                $result = self::changeStatus($album, 'trash');
            }
            
            if($result != true){
                $this->db->query("ROLLBACK");
                return false;
            }
            
        }
        
        $this->db->query("COMMIT");
        return true;
        
    }
    
    public function getImage($album_id)
    {
        
        $this->db->select('*');
        $this->db->where('album_id', $album_id);
        $this->db->order_by('`order`');

        $image = $this->db->get('com_gallery_images');  	
        $image = $image->result_array();
	
        return $image[0];
        
    }
    
}