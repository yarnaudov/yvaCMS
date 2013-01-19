<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Model {

    public function getDetails($article_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('article_id', $article_id);

        $article = $this->db->get('articles');  	
        $article = $article->result_array();

        $article[0]['title'] = $article[0]['title_'.$this->trl];
        $article[0]['text']  = $article[0]['text_'.$this->trl];
        
        if($field == null){
            return $article[0];
        }
        else{  	
            return $article[0][$field];
        }

    }
    
    public function getHistoryDetails($article_id, $updated_on, $field = null)
    {

        $this->db->select('*');
        $this->db->where('article_id', $article_id);
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
    
    public function getHistory($article_id)
    {

        $this->db->select('*');
        $this->db->where('article_id', $article_id);
        $this->db->order_by('updated_on DESC');

        $article = $this->db->get('articles_history');  	
        $article = $article->result_array();
        
        return $article;

    }
    
    public function getArticles($filters = array(), $order_by = "", $limit = "")
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
                                text_".$language['abbreviation']."  like '%".$value."%'";
                }
                
                $filter .= " ) ";

            }
            elseif($key == 'category'){
                $filter .= " AND category_id = '".$value."' ";
            }
            else{
                $filter .= " AND `".$key."` = '".$value."' ";
            }
            
        }

        $query = "SELECT 
                        article_id
                    FROM
                        articles
                    WHERE
                        article_id IS NOT NULL
                        ".$filter."
                    ".($order_by != "" ? "ORDER BY ".$order_by : "")."
                    ".($limit    != "" ? "LIMIT ".$limit : "")."";
         
        //echo $query."<br/>";

        $articles = $this->db->query($query)->result_array();
        
        foreach($articles as $key => $article){
            $articles[$key] = self::getDetails($article['article_id']);
        }
        
        return $articles;

    }
    
    public function getArticlesByCategory($filters = array(), $order_by = "")
    {
        
        $articles = self::getArticles($filters, $order_by);
        
        $articles_arr = array();
        foreach($articles as $article){
            
            $articles_arr[$this->Category->getDetails($article['category_id'], 'title_'.$this->Language->getDefault())][$article['article_id']] = $article['title_'.$this->Language->getDefault()];
            
        }
        
        return $articles_arr;
        
    }
    
    public function getMaxOrder($category = "")
    {
        
        $category == "" ? $category = $this->input->post('category') : "";
        
        $this->db->select_max("`order`");
        $this->db->where("category_id", $category);
        $max_order = $this->db->get('articles')->result_array();      
        $order = $max_order[0]['order'];

        return $order;

    }
    
    public function count($category = "")
    {
        
        $query = "SELECT 
                        COUNT(*) as `count`
                    FROM
                        articles
                    WHERE
                        category_id = '".$category."'";
         
        //echo $query."<br/>";

        $articles = $this->db->query($query)->result_array();    

        return $articles[0]['count'];

    }
  
    public function prepareData($action)
    {
         
        $this->load->helper('alias');
        
        $data['title_'.$this->trl] = $this->input->post('title');
        $data['alias']             = alias($this->input->post('alias'));
        $data['text_'.$this->trl]  = $this->input->post('text');

        $data['category_id']       = $this->input->post('category');
        $data['status']            = $this->input->post('status');      
        $data['language_id']       = $this->input->post('language');
        $data['start_publishing']  = $this->input->post('start_publishing');
        $data['end_publishing']    = $this->input->post('end_publishing');
        $data['show_title']        = $this->input->post('show_title');

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

        $query = $this->db->insert_string('articles', $data);
        //echo $query;
        $result = $this->db->query($query);

        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_article'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
        }
        
        $article_id =$this->db->insert_id();
        
        $this->Custom_field->saveFieldsValues($article_id);
        
        return $article_id;

    }

    public function edit($article_id)
    {

        $data = self::prepareData('update');
        $where = "article_id = ".$article_id; 

        $query = $this->db->update_string('articles', $data, $where);
        //echo $query;
        
        /*
         * move old data to history table before update
         */
        $this->db->query("INSERT INTO articles_history SELECT * FROM articles WHERE article_id = ".$article_id);
        $result = $this->db->query($query);
        
        if($result == true){
            $this->session->set_userdata('good_msg', lang('msg_save_article'));
        }
        else{
            $this->session->set_userdata('error_msg', lang('msg_save_article_error'));
        }
        
        $this->Custom_field->saveFieldsValues($article_id);
        
        return $article_id;

    }

    public function changeStatus($article_id, $status)
    {   

        $data['status'] = $status;
        $where = "article_id = ".$article_id;

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
    
    public function changeOrder($article_id, $order)
    {   
        
        $old_order   = self::getDetails($article_id, 'order');
        $category_id = self::getDetails($article_id, 'category_id');
        
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
        $where2 = "article_id = ".$article_id;
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
                $result = $this->db->simple_query("DELETE FROM articles WHERE article_id = '".$article."'");
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
    
}