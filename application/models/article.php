<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Model {

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
        
        $article['params'] = json_decode($article['params'], true); 
        $article           = array_merge($article, $this->Custom_field->getValues('articles', $id));
	
	$article['comments'] = $this->db->get_where('articles_comments', array('article_id' => $id))->result_array();
        
        if($field == null){
            return $article;
        }
        else{  	
            return $article[$field];
        }

    }
    
    public function getByCategory($categories, $order = 'order ASC', $limit = 'all', $filter = false)
    {
        
        if(empty($categories)){
            return array();
        }
        
        $this->db->select('id');
        $this->db->where_in('category_id', $categories);
        $this->db->where('status', 'yes');
	
        $this->db->order_by($order, '', false);
	
	if($limit != 'all'){
	    $this->db->limit($limit);
	}
	
        $articles = $this->db->get('articles');  	
        $articles = $articles->result_array();
        
        $articles_arr = array();
        foreach($articles as $article){
            
            $article = self::getDetails($article['id']);
	 
	    /* --- check filters --- */
	    if(self::_checkFilters($filter, $article) == false){
		continue;
	    }
	    
            /* --- check language for article display --- */
            if($article['show_in_language'] != NULL && $article['show_in_language'] != $this->Language->getDetailsByAbbr(get_lang(), 'id')){
                continue;
            }            

            /* --- check start end date for article display --- */
            if($article['start_publishing'] != NULL && $article['start_publishing'] > date('Y-m-d')){
                continue;
            }
            elseif($article['end_publishing'] != NULL && $article['end_publishing'] < date('Y-m-d')){
                continue;
            }
            
            $articles_arr[] = $article;
            
        }
        
        return $articles_arr;
        
    }
    
    public function getMostPopular($order = 'order ASC', $limit = 'all', $filter = false)
    {
	
	$this->db->select('articles.id, count(articles_statistics.id) AS views');
	$this->db->from('articles');
	$this->db->join('articles_statistics', 'articles.id = articles_statistics.article_id', 'left');
        $this->db->where('status', 'yes');
	$this->db->group_by('articles.id');
	
	$this->db->order_by('views', 'desc');
	$this->db->order_by($order, '', false);
	
	if($limit != 'all'){
	    $this->db->limit($limit);
	}
	
        $articles = $this->db->get()->result_array();
	
	$articles_arr = array();
        foreach($articles as $article){
            
            $article = self::getDetails($article['id']);
	 
	    /* --- check filters --- */
	    if(self::_checkFilters($filter, $article) == false){
		continue;
	    }
	    
            /* --- check language for article display --- */
            if($article['show_in_language'] != NULL && $article['show_in_language'] != $this->Language->getDetailsByAbbr(get_lang(), 'id')){
                continue;
            }            

            /* --- check start end date for article display --- */
            if($article['start_publishing'] != NULL && $article['start_publishing'] > date('Y-m-d')){
                continue;
            }
            elseif($article['end_publishing'] != NULL && $article['end_publishing'] < date('Y-m-d')){
                continue;
            }
            
            $articles_arr[] = $article;
            
        }
        
        return $articles_arr;
	
    }
    
    private function _checkFilters($filter, $article)
    {

	if($filter == $this->input->get_post('filter') || $this->input->get_post('filter') == 'all'){

	    $get_post = $_GET+$_POST;
	    
	    foreach($get_post as $key => $value){
		if($key == 'search_v'){
		    $pattern = "/".$value."/iu";
		    if(!preg_match($pattern, $article['title']) && !preg_match($pattern, $article['text'])){		
			return false;
		    }
		}
		elseif(isset($article[$key]) && $article[$key] != $value){
		    return false;
		}
	    }
	}
	
	return true;
	
    }
    
    public function getByAlias($alias, $field = null)
    {
        
        if(empty($alias)){
            return;
        }
        
        $query = "SELECT 
                      a.id
                    FROM
                      articles a
                      LEFT JOIN articles_data ad ON (a.id = ad.article_id AND ad.language_id = '".$this->language_id."')
                    WHERE
                      a.alias = '".$alias."'
                     AND
                      a.status = 'yes'";
        
        $article = $this->db->query($query);
        $article = $article->row_array();

        if(empty($article)){
            return;
        }
        
	$article = self::getDetails($article['id']);
	        
        
        if($field == null){
            return $article;
        }
        else{  	
            return $article[$field];
        }
        
    }    
    
    public function search($search_v)
    {
    
        $query = "SELECT *
                    FROM
                      articles a
                      LEFT JOIN articles_data ad ON (a.id = ad.article_id AND ad.language_id = '".$this->language_id."')                    
                    WHERE
                      a.status = 'yes'
                     AND
                      (ad.title like '%".$search_v."%' OR ad.text like '%".$search_v."%')
                    ORDER BY a.`order`";
        
        $articles = $this->db->query($query);  
        $articles = $articles->result_array();        
        
        return $articles;
        
    }
    
    public function parceText($text)
    {
        
        if(empty($text)){
            return '';            
        }
        
        $this->load->helper('simple_html_dom');
        $this->load->helper('fix_links');
        
        $elements = array(0 => array('video', array('src', 'poster')));
        
        $html = fix_links($text, $elements);
        
        $html = str_get_html($html);
        
        $pagebreaks = count($html->find('hr.pagebreak'));
        $readmore   = count($html->find('hr.readmore'));
        $modules    = $html->find('img.module');
        
        
        /*
         * fix object data and src to full path
         */
        foreach($html->find('object') as $key => $object){
            
            if(!preg_match('/^http:\/\//', $object->data)){
                
                $object->data = base_url($object->data);                
                $html->find('object', $key)->data = $object->data;
                $html->find('object', $key)->find('param[name=src]', 0)->value = $object->data;
            }
            
        }       
   
        if($pagebreaks > 0){
            
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            
            $html = explode('<hr class="pagebreak" />', $html);
            $html = $html[$page-1];
            
            $html .= '<ul class="article_pages" >';

            
            //start previous page
            if($page-1 > 0){
                $html .= '<li>';
                $html .= '  <a href="?page='.($page-1).'" ><</a>';
                $html .= '</li>';
            }
            //end previous page
            
            
            //start building pages
            for($i = 1; $i <= $pagebreaks+1; $i++){
                $html .= '<li '.($page == $i ? "class=current" : "").' >
                            <a href="?page='.$i.'" >'.$i.'</a>
                          </li>';
            }
            //end building pages
            
            
            //start next page
            if($page+1 <= $pagebreaks+1){
                $html .= '<li>';
                $html .= '  <a href="?page='.($page+1).'" >></a>';
                $html .= '</li>';
            }
            //end next page
            
            
            $html .= '</ul>';
                    
        }
        
        
        if($readmore == 1){
            
            $readmore = isset($_GET['readmore']) ? $_GET['readmore'] : 'no';
            
            if($readmore == 'no'){
                
                $html = explode('<hr class="readmore" />', $html);
                $html = $html[0];
                $html .= '<a href="?readmore=yes" >'.lang('label_read_more').'</a>';
                
            }
            else{
                $html = str_replace('<hr class="readmore" />', '', $html);
            }
            
        }
        elseif($readmore > 1){
        	  
            $html = explode('<hr class="readmore" />', $html);

            $new_html = '<script type="text/javascript" src="'.base_url('js/readmore.js').'"></script>';
            foreach($html as $key => $value){

                if($key == 0){
                      $new_html .= $value;
                }
                elseif($key&1){
                    $new_html .= '<a class="readmore" href="readmore'.$key.'" >'.lang('label_read_more').'</a>';
                    $new_html .= '<a class="readmore_hide" href="readmore'.$key.'" >'.lang('label_read_more_hide').'</a>';
                    $new_html .= '<div class="readmore" id="readmore'.$key.'" >'.$value;
                }
                else{
                    $new_html .= '</div>'.$value;
                }

            }
	        	
            if(!(count($html)&1)){
                      $new_html .= '</div>';
            }
        	  
            $html = $new_html;
        	  
        }
        
        
        /*
         * load modules
         */
        foreach($modules as $key => $img){
     
            $params = explode(";", $img->alt);
            foreach($params as $param){
                $param = explode("=", $param);
                ${$param[0]} = $param[1];
            }
            
            if($type == 'popup_iframe'){
                $content = '<iframe class="load_module_iframe" src="'.site_url('load/module/'.$id).'" ></iframe>';
            }
            else{                
                $content = $this->Module->load($id, 2);            
            }
            
            if(preg_match('/^popup/', $type)){
                
                $content = '<a href  = "'.site_url('articles').'" 
                               class = "load_jquery_ui" 
                               lang  = "dialog-'.$id.'" >'.$label.'</a>

                                <!-- start jquery UI -->
                                <div id    = "dialog-'.$id.'"
                                     class = "jquery_ui"
                                     title = "'.$label.'" >'.$content.'</div>';
                                
            }
            
            $html = str_replace($img, $content, $html);
            
        }
        
        return $html;
        
    }
    
    function _load_article($id)
    {
        
        $article = self::getDetails($id);
        
        return self::parceText($article['text_bg']);
        
    }
    
    function statistic($id)
    {
	
	$this->load->library('user_agent');
	
	# get user agent
	if ($this->agent->is_browser()){
	    $data['user_agent'] = $this->agent->browser().' '.$this->agent->version();
	}
	elseif ($this->agent->is_robot()){
	    $data['user_agent'] = $this->agent->robot();
	}
	elseif ($this->agent->is_mobile()){
	    $data['user_agent'] = $this->agent->mobile();
	}
	else{
	    $data['user_agent'] = 'Unidentified User Agent';
	}
	
	if($this->agent->is_referral()){
	    $data['user_referrer'] = $this->agent->referrer();
	}
	
	$data['page_url'] = $_SERVER['QUERY_STRING'] ? current_url().'?'.$_SERVER['QUERY_STRING'] : current_url();
	
	$data['article_id'] = $id;
	$data['ip'] = $this->input->ip_address();
	$data['created_on'] = date('Y-m-d H:i:s');
	
	$this->db->insert('articles_statistics', $data);
	
    }
    
    function addComment()
    {
	
	$data['article_id'] = $this->input->post('article_id', true);
	
	if(empty($data['article_id'])){
	    return FALSE;	    
	}
	
	//if(isset($_SESSION['user_id'])){
	//    $data['created_by'] = '';
	//}
	
	$data['created_on'] = date('Y-m-d H:i:s');
	$data['name']       = $this->input->post('name', true);
	$data['comment']    = $this->input->post('comment', true);
	
	$this->db->insert('articles_comments', $data);
	
    }
    
}