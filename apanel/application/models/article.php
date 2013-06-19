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
        $article = $article->result_array();

        if(empty($article)){
            return;
        }
        
        $article[0]['params'] = json_decode($article[0]['params'], true); 
        $article[0]           = array_merge($article[0], $this->Custom_field->getFieldsValues($id));
        
        if($field == null){
            return $article[0];
        }
        else{  	
            return $article[0][$field];
        }

    }
    
    public function getHistoryDetails($id, $updated_on, $field = null)
    {

        $this->db->select('*');
        $this->db->where('article_id', $id);
        $this->db->where('updated_on', $updated_on);

        $article = $this->db->get('articles_history');  	
        $article = $article->result_array();

        if($field == null){
            return $article[0];
        }
        else{  	
            return $article[0][$field];
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
        
        if(substr_count($order_by, 'order')){
            $order_by = "category_id, ".$order_by;
        }      
                    
        foreach($filters as $key => $value){
            
            if($key == 'search_v'){
                $filter .= " AND ( title like '%".$value."%' OR text like '%".$value."%' ) ";
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
                        articles a
                        LEFT JOIN articles_data ad ON (a.id = ad.article_id AND ad.language_id = ".$this->language_id.")
                    WHERE
                        id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $articles = $this->db->query($query)->result_array();
        
        return $articles;

    }
    
    public function getArticlesByCategory($filters = array(), $order_by = "")
    {
        
        $articles = self::getArticles($filters, $order_by);
        
        $articles_arr = array();
        foreach($articles as $article){
            
            $articles_arr[$this->Category->getDetails($article['category_id'], 'title')][$article['id']] = $article['title'];
            
        }
        
        return $articles_arr;
        
    }
    
    public function getMaxOrder($category_id)
    {
        
        $this->db->select_max("`order`");
        $this->db->where("category_id", $category_id);
        $max_order = $this->db->get('articles')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count($category_id)
    {

        $this->db->select('*');
        $this->db->where('category_id', $category_id);
        $this->db->from('articles');
        $count = $this->db->count_all_results();  

        return $count;

    }
  
    public function prepareData($action)
    {
         
        $this->load->helper('alias');
                
        $data['articles_data']['title']       = $this->input->post('title');
        $data['articles_data']['text']        = $this->input->post('text');
        $data['articles_data']['language_id'] = $this->language_id;
        
        $data['articles']['alias']            = alias($this->input->post('alias'));
        $data['articles']['category_id']      = $this->input->post('category');
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
        
        $old_order   = self::getDetails($id, 'order');
        $category_id = self::getDetails($id, 'category_id');
        
        if($order == 'up'){
            $new_order =  $old_order-1;        
        }
        else{
           $new_order =  $old_order+1;           
        }
        
        $data1['order'] = $old_order;
        $where1 = "`order` = ".$new_order." AND category_id = '".$category_id."'";
        $query1 = $this->db->update_string('articles', $data1, $where1);
        //echo $query1;
        $result1 = $this->db->query($query1);
        
        $data2['order'] = $new_order;
        $where2 = "id = ".$id;
        $query2 = $this->db->update_string('articles', $data2, $where2);
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
    
    private function _saveHistory($id)
    {
       
        $result = $this->db->query("INSERT 
                                      INTO 
                                        articles_history 
                                      SELECT 
                                         ad.article_id,
                                         ad.language_id,
                                         a.category_id,
                                         a.alias,
                                         a.show_in_language,
                                         a.start_publishing,
                                         a.end_publishing,
                                         a.created_by,
                                         a.created_on,
                                         a.updated_by,
                                         a.updated_on,
                                         ad.title,
                                         ad.text,
                                         a.status,
                                         a.`order`
                                       FROM
                                         articles a
                                         JOIN articles_data ad ON (a.id = ad.article_id AND ad.language_id = ".$this->language_id.")
                                       WHERE
                                         a.id = ".$id." ");
        
        return $result;
        
    }
    
}