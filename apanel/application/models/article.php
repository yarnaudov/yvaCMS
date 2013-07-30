<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends MY_Model {

    public function getDetails($id, $field = null)
    {

        $query = "SELECT 
                      *
                    FROM
                      articles a
                      LEFT JOIN articles_data ad ON (a.id = ad.article_id AND ad.language_id = '".$this->language_id."')
                    WHERE
                      a.id = '".$id."' ";
        
        $article = $this->db->query($query);
        $article = $article->row_array();

        if(empty($article)){
            return;
        }
        
	# get categories and make array with category_id
	$article['categories'] = array();
	$article['orders']     = array();
	$categories = $this->db->get_where('articles_categories', array('article_id' => $id))->result_array();
	foreach($categories as $category){
	   $article['categories'][] = $category['category_id'];
	   $article['orders'][$category['category_id']] = $category['order'];
	}
	
        $article['params']     = json_decode($article['params'], true); 
        $article               = array_merge($article, json_decode(json_encode($this->Custom_field->getFieldsValues($id)), true));
   
        if($field == null){
            return $article;
        }
        else{  	
            return $article[$field];
        }

    }
    
    public function getHistoryDetails($id, $updated_on, $field = null)
    {

        $this->db->select('*');
        $this->db->where('article_id', $id);
        $this->db->where('updated_on', $updated_on);

        $article = $this->db->get('articles_history');  	
        $article = $article->row_array();
	
	$article['params'] = json_decode($article['params'], true); 
        $article           = array_merge($article, json_decode($article['custom_fields'], true));

	$categories = json_decode($article['categories'], true);
	$article['categories'] = array();
	foreach($categories as $key => $category){
	   $article['categories'][] = $category['category_id'];
	}
	
        if($field == null){
            return $article;
        }
        else{  	
            return $article[$field];
        }

    }
    
    public function getHistory($id)
    {

        $this->db->select('*');
        $this->db->where('article_id', $id);
        $this->db->order_by('updated_on DESC');

        $article = $this->db->get('articles_history');  	
        $article = $article->result_array();
        
        return $article;

    }
    
    public function getStatistics($filters)
    {

	$this->db->select('DATE_FORMAT(ast.created_on, "%Y-%m-%d") AS date', FALSE);
	$this->db->select('count(ast.id) AS views');
	$this->db->from('articles a');
	$this->db->join('articles_statistics ast', 'a.id = ast.article_id', 'left');
        $this->db->where('ast.article_id', @$filters['article']);
        $this->db->where('ast.created_on >=', $filters['start_date'].' 00:00:00');
        $this->db->where('ast.created_on <=', $filters['end_date'].' 23:59:59');
	$this->db->group_by('date');
        $this->db->order_by('date', 'asc');
	$statistics = $this->db->get()->result_array();
	
	foreach($statistics as $key => $statistic){
	    $statistics[$key]['details'] = $this->db->query("SELECT * FROM articles_statistics WHERE article_id = '".$filters['article']."' AND created_on LIKE '".$statistic['date']."%' ")->result_array();	    
	}
        
	//print_r($statistics);
	
        return $statistics;

    }    
    
    public function getArticles($filters = array(), $order_by = "", $limit = "")
    {
        
        $filter = ''; 
        if(!isset($filters['status'])){
            $filter = " AND status != 'trash' "; 
        }
        
        //if(substr_count($order_by, 'order')){
        //    $order_by = "ac.category_id, ".$order_by;
        //}      
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND ( title like '%".$value."%' OR text like '%".$value."%' ) ";
            }
            elseif($key == 'category'){
                $filter .= " AND ac.category_id = '".$value."' ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        DISTINCT(a.id)
                    FROM
                        articles a
                        LEFT JOIN articles_data ad ON (a.id = ad.article_id AND ad.language_id = ".$this->language_id.")
			LEFT JOIN articles_categories ac ON (a.id = ac.article_id)
                    WHERE
                        id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $articles = $this->db->query($query)->result_array();
	
	foreach($articles as &$article){
	    $article = self::getDetails($article['id']);
	}
        
        return $articles;

    }
    
    public function getArticlesByCategory($filters = array(), $order_by = "")
    {
        
	$categories = $this->Category->getForDropdown('articles');
	
	$articles_arr = array();
	foreach($categories as $category_id => $category_title){
	    
	    $articles = self::getArticles(array('category' => $category_id), $order_by);
	    
	    foreach($articles as $article){
		$articles_arr[$category_title][$article['id']] = $article['title'];
	    }
	    
	}
        
        return $articles_arr;
        
    }
    
    public function getMaxOrder($category_id)
    {
        
        $this->db->select_max("`order`");
        $this->db->where("category_id", $category_id);
        $max_order = $this->db->get('articles_categories')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count($category_id)
    {

        $this->db->select('*');
        $this->db->where('category_id', $category_id);
        $this->db->from('articles_categories');
        $count = $this->db->count_all_results();  

        return $count;

    }
  
    public function prepareData($action)
    {
         
        $this->load->helper('alias');
                
        $data['articles_data']['title']            = $this->input->post('title');
        $data['articles_data']['text']             = $this->input->post('text');
	$data['articles_data']['meta_keywords']    = $this->input->post('meta_keywords');
	$data['articles_data']['meta_description'] = $this->input->post('meta_description');
        $data['articles_data']['language_id']      = $this->language_id;
        
        $data['articles']['alias']            = alias($this->input->post('alias'));
        //$data['articles']['category_id']      = $this->input->post('category');
        $data['articles']['status']           = $this->input->post('status');      
        $data['articles']['show_in_language'] = $this->input->post('show_in_language');
        $data['articles']['start_publishing'] = $this->input->post('start_publishing');
        $data['articles']['end_publishing']   = $this->input->post('end_publishing');
        $data['articles']['show_title']       = $this->input->post('show_title');
        $data['articles']['params']           = json_encode($this->input->post('params'));
	
        if($data['articles']['show_in_language'] == 'all'){
            $data['articles']['show_in_language'] = NULL;
        }
        if(empty($data['articles']['start_publishing'])){
            $data['articles']['start_publishing'] = NULL;
        }
        if(empty($data['articles']['end_publishing'])){
            $data['articles']['end_publishing'] = NULL;
        }

        if($action == 'insert'){
            $data['articles']['order']      =  self::getMaxOrder($data['articles']['category_id'])+1;
            $data['articles']['created_by'] =  $_SESSION['user_id'];
            $data['articles']['created_on'] =  now();        
        }
        elseif($action == 'update'){
            $data['articles']['updated_by'] =  $_SESSION['user_id'];
            $data['articles']['updated_on'] =  now(); 
        }

        //echo print_r($data);
        //exit;
        
        return $data;

    }

    public function add()
    {

        $data = self::prepareData('insert');

        $this->db->query('BEGIN');
        
        // save data in articles table
        $query = $this->db->insert_string('articles', $data['articles']);
        $result = $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
            $this->db->query('ROLLBACK');
            return;
        }
        
        $id = $this->db->insert_id();
        
        // save data in articles_data table
        $data['articles_data']['article_id'] = $id;
        $query = $this->db->insert_string('articles_data', $data['articles_data']);
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
	// save categories data
	$result = self::_save_categories($id);
	if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
	
        // save custom fields data
        $result = $this->Custom_field->saveFieldsValues($id);
        if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        $this->session->set_userdata('good_msg', lang('msg_save_article'));
        $this->db->query('COMMIT');
        return $id;

    }

    public function edit($id)
    {
        
        $data = self::prepareData('update');
        
        $this->db->query('BEGIN');
        
        // save previous data in articles_history table
        $result = self::_saveHistory($id);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        // save data in articles table
        $where = "id = ".$id; 
        $query = $this->db->update_string('articles', $data['articles'], $where);        
        $result = $this->db->query($query);        
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
        // save data in articles_data table
        if(parent::_dataExists('articles_data', 'article_id', $id) == 0){
            $data['articles_data']['article_id'] = $id;
            $query = $this->db->insert_string('articles_data', $data['articles_data']);
        }
        else{            
            $where = "article_id = ".$id." AND language_id = ".$this->language_id." ";
            $query = $this->db->update_string('articles_data', $data['articles_data'], $where);            
        }        
        $this->db->query($query);
        if($result != true){
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
        
	// save categories data
	$result = self::_save_categories($id, 'update');
	if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }
	
        // save custom fields data
        $result = $this->Custom_field->saveFieldsValues($id);
        if($result == false){
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
            $this->db->query('ROLLBACK');
            return $id;
        }        
        
        $this->session->set_userdata('good_msg', lang('msg_save_article'));
        $this->db->query('COMMIT');
        return $id;

    }
    
    private function _save_categories($id, $action = 'insert')
    {
	
	$categories = $this->input->post('categories');
	
	if($action == 'insert'){
	    
	    foreach($categories as $category_id){
		
		$category['article_id']  = $id;
		$category['category_id'] = $category_id;
		$category['order']       = self::getMaxOrder($category_id)+1;
						
		$query = $this->db->insert_string('articles_categories', $category);
		$result = $this->db->query($query);        
		if($result != TRUE){
		    return FALSE;
		}
		
	    }
	    
	}
	else{
	    	    
	    $new_categories = array();
	    
	    foreach($categories as $category_id){
		    
		$category = $this->db->get_where('articles_categories', array('article_id' => $id, 'category_id' => $category_id))->row_array();
		
		if(empty($category)){
		    $category['article_id']  = $id;
		    $category['category_id'] = $category_id;
		}
		
		$new_categories[] = $category;
		
	    }
	    
	    $result = $this->db->delete('articles_categories', array('article_id' => $id));    
	    if($result != TRUE){
		return FALSE;
	    }
	    
	    foreach($new_categories as $new_category){
		
		if(!isset($new_category['order'])){
		    $new_category['order'] = self::getMaxOrder($new_category['category_id'])+1;
		}
				
		$query = $this->db->insert_string('articles_categories', $new_category);
		$result = $this->db->query($query);        
		if($result != TRUE){
		    return FALSE;
		}
		
	    }
	    
	}
	
	return TRUE;
	
    }
    
    public function changeStatus($id, $status)
    {   

        $data['status'] = $status;
        $where = "id = ".$id;

        $query = $this->db->update_string('articles', $data, $where);
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
        
	$category_id = $this->input->post('category');
	if(empty($category_id)){
	    return FALSE;
	}
	
        $orders    = self::getDetails($id, 'orders');	
	$old_order = $orders[$category_id];
	        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND category_id = '".$category_id."'";
        $query1 = $this->db->update_string('articles_categories', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "article_id = ".$id;
        $query2 = $this->db->update_string('articles_categories', $data2, $where2);
        //echo $query2;
        $result2 = $this->db->query($query2);
        
        if($result1 == TRUE && $result2 == TRUE){
            return TRUE; 
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_order_error'));
        }

    }
    
    public function delete()
    {
        
        $this->db->query("BEGIN");
        
        $articles = $this->input->post('articles');     
        foreach($articles as $article){
            
            $status = self::getDetails($article, 'status');
            
            if($status == 'trash'){
                $result = true;//$this->db->simple_query("DELETE FROM articles WHERE id = '".$article."'");
            }
            else{
                $result = self::changeStatus($article, 'trash');
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
        
        $articles = $this->input->post('articles');     
        foreach($articles as $article_id){
            
	    $article = $this->db->get_where('articles', array('id' => $article_id))->row_array();
	    
	    $article['alias'] = $article['alias']."_copy";
	    $article['order'] = self::getMaxOrder($article['category_id'])+1;
	    $article['created_by'] = $_SESSION['user_id'];
            $article['created_on'] = now();
	    unset($article['id'], $article['updated_by'], $article['updated_on']);
	    
            $result = $this->db->insert('articles', $article);                        
            if($result != true){
		$this->session->set_userdata('error_msg', lang('msg_copy_article_error'));
                $this->db->query("ROLLBACK");
                return false;
            }
	    
	    $id = $this->db->insert_id();
            
	    $article_data = $this->db->get_where('articles_data', array('article_id' => $article_id))->result_array();
	    foreach($article_data as $data){
		$data['article_id'] = $id;
		$result = $this->db->insert('articles_data', $data);                        
		if($result != true){
		    $this->session->set_userdata('error_msg', lang('msg_copy_article_error'));
		    $this->db->query("ROLLBACK");
		    return false;
		}
	    }
	    
	    $custom_fields = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
	    foreach($custom_fields as $custom_field){
		
		$custom_field_data = $this->db->get_where('custom_fields_values', array('custom_field_id' => $custom_field['id'], 'element_id' => $article_id))->result_array();
		
		foreach($custom_field_data as $data){
		    
		    $data['element_id'] = $id;
		    
		    $result = $this->db->insert('custom_fields_values', $data);                        
		    if($result != true){
			$this->session->set_userdata('error_msg', lang('msg_copy_article_error'));
			$this->db->query("ROLLBACK");
			return false;
		    }
		
		}
		
	    }
	    
        }
        
	$this->session->set_userdata('good_msg', lang('msg_copy_article'));
        $this->db->query("COMMIT");
        return true;
        
    }
    
    private function _saveHistory($id)
    {
       
	$custom_fields = $this->Custom_field->getFieldsValues($id);
	$categories = $this->db->get_where('articles_categories', array('article_id' => $id))->result_array();
	
        $result = $this->db->query("INSERT 
                                      INTO 
                                        articles_history 
                                      SELECT 
                                         ad.article_id,
                                         ad.language_id,
                                         #a.category_id,
                                         a.alias,
                                         a.show_in_language,
                                         a.start_publishing,
                                         a.end_publishing,
					 a.show_title,
					 a.params,
                                         a.created_by,
                                         a.created_on,
                                         a.updated_by,
                                         a.updated_on,
                                         ad.title,
                                         ad.text,
                                         a.status,
					 '".json_encode($categories)."' AS categories,
					 '".mysql_real_escape_string(json_encode($custom_fields))."' AS custom_fields
                                       FROM
                                         articles a
                                         JOIN articles_data ad ON (a.id = ad.article_id AND ad.language_id = ".$this->language_id.")
                                       WHERE
                                         a.id = ".$id." ");
        
        return $result;
        
    }
    
}