<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner extends CI_Model {

    public function getDetails($id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('id', $id);

        $banner = $this->db->get('banners');  	
        $banner = $banner->result_array();
        
        $banner[0]['params'] = json_decode($banner[0]['params'], true);
        $banner[0]           = array_merge($banner[0], $this->Custom_field->getFieldsValues($id));
        
        if($field == null){
            return $banner[0];
        }
        else{  	
            return $banner[0][$field];
        }

    }
  
    public function getStatistics($filters)
    {

	$this->db->select('DATE_FORMAT(bst.created_on, "%Y-%m-%d") AS date', FALSE);
	$this->db->select('bst.type');
	$this->db->select('count(bst.id) AS views');
	$this->db->from('banners b');
	$this->db->join('banners_statistics bst', 'b.id = bst.banner_id', 'left');
        $this->db->where('bst.banner_id', @$filters['banner']);
        $this->db->where('bst.created_on >=', $filters['start_date'].' 00:00:00');
        $this->db->where('bst.created_on <=', $filters['end_date'].' 23:59:59');
	$this->db->group_by('date, type');
        $this->db->order_by('date', 'asc');
	$statistics = $this->db->get()->result_array();
	
	foreach($statistics as $key => $statistic){
	    $statistics[$key]['details'] = $this->db->query("SELECT * FROM banners_statistics WHERE banner_id = '".$filters['banner']."' AND created_on LIKE '".$statistic['date']."%' ")->result_array();	    
	}
        
	//print_r($statistics);
	
        return $statistics;

    } 
    
    public function getBanners($filters = array(), $order_by = "", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash'"; 
        }
        
        if(substr_count($order_by, 'order')){
            $order_by = "position, ".$order_by;
        }      
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND (title like '%".$value."%' OR description like '%".$value."%')";
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
                        id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $banners = $this->db->query($query)->result_array();

        foreach($banners as $key => $banner){
            $banners[$key]['params'] = json_decode($banner['params'], true);
            $banners[$key]           = array_merge($banners[$key], $this->Custom_field->getFieldsValues($banner['id']));
        }
        
        return $banners;

    }
    
    public function getPositions($positions = array())
    {
        
        $query = "SELECT position FROM banners WHERE `status` != 'trash'; ";
        
        $banners = $this->db->query($query)->result_array();

        foreach($banners as $key => $banner){
            if(!in_array($banner['position'], $positions)){
                $positions[$banner['position']] = $banner['position'];
            }
        }
        
        return $positions;
        
    }
    
    public function getMaxOrder($position)
    {
                
        $this->db->select_max("`order`");
        $this->db->where("position", $position);
        $max_order = $this->db->get('banners')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count($position)
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        banners
                    WHERE
                        position = '".$position."'";
         
        //echo $query."<br/>";

        $banners = $this->db->query($query)->result_array();    

        return $banners[0]['count'];

    }
  
    public function prepareData($action)
    {
                 
        $data['title']            = $this->input->post('title');
        $data['description']      = $this->input->post('description');

        $data['position']         = $this->input->post('position');
        $data['status']           = $this->input->post('status');      
        $data['show_in_language'] = $this->input->post('show_in_language');
        $data['start_publishing'] = $this->input->post('start_publishing');
        $data['end_publishing']   = $this->input->post('end_publishing');
        $data['show_title']       = $this->input->post('show_title');
        $data['css_class_sufix']  = $this->input->post('css_class_sufix');
        $data['params']           = json_encode($this->input->post('params'));

        if($data['show_in_language'] == 'all'){
            $data['show_in_language'] = NULL;
        }
        if(empty($data['start_publishing'])){
            $data['start_publishing'] = NULL;
        }
        if(empty($data['end_publishing'])){
            $data['end_publishing'] = NULL;
        }

        if($action == 'insert'){
            $data['order']      =  self::getMaxOrder($data['position'])+1;
            $data['created_by'] =  $_SESSION['user_id'];
            $data['created_on'] =  now();        
        }
        elseif($action == 'update'){
            $data['updated_by'] =  $_SESSION['user_id'];
            $data['updated_on'] =  now(); 
        }

        return $data;

    }

    public function add()
    {

        $data = self::prepareData('insert');

        $this->db->query('BEGIN');
        
        // save data in banners table
        $query = $this->db->insert_string('banners', $data);
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_banner_error'));
            $this->db->query('ROLLBACK');
            return;
        }
        
        $id = $this->db->insert_id();
        
        // save custom fields data
        $result = $this->Custom_field->saveFieldsValues($id);
        if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_banner_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        $this->session->set_userdata('good_msg', lang('msg_save_banner'));
        $this->db->query('COMMIT');
        return $id;

    }

    public function edit($id)
    {

        $data = self::prepareData('update');
        
        $this->db->query('BEGIN');
        
        $where = "id = ".$id; 
        $query = $this->db->update_string('banners', $data, $where);
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_banner_error'));
            $this->db->query('ROLLBACK');
            return;
        }
        
        // save custom fields data
        $result = $this->Custom_field->saveFieldsValues($id);
        if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_banner_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        $this->session->set_userdata('good_msg', lang('msg_save_banner'));
        $this->db->query('COMMIT');
        return $id;

    }

    public function changeStatus($id, $status)
    {   

        $data['status'] = $status;
        $where = "id = ".$id;

        $query = $this->db->update_string('banners', $data, $where);
        $result = $this->db->query($query);

        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_status_error'));
            return;
        }
        
        return true;

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
        $where1 = "`order` = ".$new_order." AND category_id = '".$category_id."'";
        $query1 = $this->db->update_string('banners', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "id = ".$id;
        $query2 = $this->db->update_string('banners', $data2, $where2);
        //echo $query2;
        $result2 = $this->db->query($query2);
        
        if($result1 != true && $result2 != true){
            $this->session->set_userdata('error_msg', lang('msg_order_error'));
            return;
        }
        
        return true; 

    }
    
    public function delete()
    {
        
        $this->db->query("BEGIN");
        
        $banners = $this->input->post('banners');     
        foreach($banners as $banner){
            
            $status = self::getDetails($banner, 'status');
            
            if($status == 'trash'){
                $result = true;//$this->db->simple_query("DELETE FROM banners WHERE id = '".$banner."'");
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