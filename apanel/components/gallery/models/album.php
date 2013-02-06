<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Album extends MY_Model {

    public function getDetails($id, $field = null)
    {

        $query = "SELECT 
                      *
                    FROM
                      com_gallery_albums cga
                      LEFT JOIN com_gallery_albums_data cgad ON (cga.id = cgad.album_id AND cgad.language_id = '".$this->language_id."')
                    WHERE
                      cga.id = '".$id."' ";
        
        $album = $this->db->query($query);  	
        $album = $album->result_array();

        if(empty($album)){
            return;
        }

        if($field == null){
            return $album[0];
        }
        else{  	
            return $album[0][$field];
        }

    }
  
    public function getForDropdown()
    {
        
        $query = "SELECT 
                      cga.id,
                      cgad.title
                    FROM
                      com_gallery_albums cga
                      JOIN com_gallery_albums_data cgad ON (cga.id = cgad.album_id)
                    WHERE
                      cgad.language_id = ".$this->language_id."
                     AND 
                      cga.status = 'yes'
                    ORDER BY cga.`order`";
    
        $albums = $this->db->query($query)->result_array();
        
        $albums_arr = array();
        foreach($albums as $album){
            $albums_arr[$album['id']] = $album['title'];
        }
        
        return $albums_arr;
        
    }
    
    public function getAlbums($filters = array(), $order_by = "", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }     
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND ( title like '%".$value."%' OR description like '%".$value."%' ) ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        *
                    FROM
                        com_gallery_albums cga
                        LEFT JOIN com_gallery_albums_data cgad ON (cga.id = cgad.album_id AND cgad.language_id = '".$this->language_id."')
                    WHERE
                        cga.id IS NOT NULL
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
                 
        $data['com_gallery_albums_data']['title']       = $this->input->post('title');
        $data['com_gallery_albums_data']['description'] = $this->input->post('description');
        $data['com_gallery_albums_data']['language_id'] = $this->language_id;

        $data['com_gallery_albums']['status']           = $this->input->post('status');      
        $data['com_gallery_albums']['show_in_language'] = $this->input->post('show_in_language');
        
        if($data['com_gallery_albums']['show_in_language'] == 'all'){
            $data['com_gallery_albums']['show_in_language'] = NULL;
        }

        if($action == 'insert'){            
            $data['com_gallery_albums']['order']      =  self::getMaxOrder()+1;
            $data['com_gallery_albums']['created_by'] =  $_SESSION['user_id'];
            $data['com_gallery_albums']['created_on'] =  now();        
        }
        elseif($action == 'update'){            
            $data['com_gallery_albums']['updated_by'] =  $_SESSION['user_id'];
            $data['com_gallery_albums']['updated_on'] =  now(); 
        }

        return $data;

    }

    public function add()
    {      
        
        $data = self::prepareData('insert');

        $this->db->query('BEGIN');
        
        // save data in com_gallery_albums table
        $query = $this->db->insert_string('com_gallery_albums', $data['com_gallery_albums']);
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_album_error'));
            $this->db->query('ROLLBACK');
            return;
        }
        
        $id = $this->db->insert_id();
        
        // save data in com_gallery_albums_data table
        $data['com_gallery_albums_data']['album_id'] = $id;
        $query = $this->db->insert_string('com_gallery_albums_data', $data['com_gallery_albums_data']);
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_album_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
                
        $this->session->set_userdata('good_msg', lang('msg_save_album'));
        $this->db->query('COMMIT');
        return $id;

    }

    public function edit($id)
    {

        $data = self::prepareData('update');
        
        $this->db->query('BEGIN');
        
        // save data in com_gallery_albums table
        $where = "id = ".$id; 
        $query = $this->db->update_string('com_gallery_albums', $data['com_gallery_albums'], $where);        
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_album_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        // save data in com_gallery_albums_data table
        if(parent::_dataExists('com_gallery_albums_data', 'album_id', $id) == 0){
            $data['com_gallery_albums_data']['album_id'] = $id;
            $query = $this->db->insert_string('com_gallery_albums_data', $data['com_gallery_albums_data']);
        }
        else{            
            $where = "album_id = ".$id." AND language_id = ".$this->language_id." ";
            $query = $this->db->update_string('com_gallery_albums_data', $data['com_gallery_albums_data'], $where);            
        }        
        $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_album_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
                   
        $this->session->set_userdata('good_msg', lang('msg_save_album'));
        $this->db->query('COMMIT');
        return $id;

    }

    public function changeStatus($id, $status)
    {   

        $data['status'] = $status;
        $where = "id = ".$id;

        $query = $this->db->update_string('com_gallery_albums', $data, $where);
        //echo $query;
        $result = $this->db->query($query);

        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
            return false;
        }
        
        return true; 

    }
    
    public function changeOrder($id, $order)
    {   
        
        $old_order   = self::getDetails($id, 'order');
        
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
        $where2 = "id = ".$id;
        $query2 = $this->db->update_string('com_gallery_albums', $data2, $where2);
        //echo $query2;
        $result2 = $this->db->query($query2);
        
        if($result1 != true && $result2 != true){
            $this->session->set_userdata('error_msg', lang('msg_order_error'));
            return false;
        }
        
        return true;

    }
    
    public function delete()
    {
        
        $this->db->query("BEGIN");
        
        $albums = $this->input->post('albums');     
        foreach($albums as $album){
            
            $status = self::getDetails($album, 'status');
            
            if($status == 'trash'){
                $result = $this->db->simple_query("DELETE FROM com_gallery_albums WHERE id = '".$album."'");
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
    
}