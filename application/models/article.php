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
        $article = $article->result_array();

        if(empty($article)){
            return;
        }
        
        $article[0]['params'] = json_decode($article[0]['params'], true); 
        $article[0]           = array_merge($article[0], $this->Custom_field->getValues($id, 'articles'));
        
        if($field == null){
            return $article[0];
        }
        else{  	
            return $article[0][$field];
        }

    }
    
    public function getByCategory($category_id)
    {
        
        if(empty($category_id)){
            return array();
        }
        
        $this->db->select('id');
        $this->db->where('category_id', $category_id);
        $this->db->where('status', 'yes');
        $this->db->order_by('order', 'asc');
        $articles = $this->db->get('articles');  	
        $articles = $articles->result_array();
        
        $articles_arr = array();
        foreach($articles as $article){
            $articles_arr[] = self::getDetails($article['id']); 
        }
        
        return $articles_arr;
        
    }
    
    public function getByAlias($alias, $field = null)
    {
        
        if(empty($alias)){
            return array();
        }
        
        $this->db->select('*');
        $this->db->where('alias',  $alias);
        $this->db->where('status', 'yes');
        $article = $this->db->get('articles');  	
        $article = $article->result_array();

        if(empty($article)){
            return '';
        }
        
        if($field == null){
            $article[0]['custom_fields'] = $this->Custom_field->getValues('articles', $article[0]['id']);
            return $article[0];
        }
        else{  	
            return $article[0][$field];
        }
        
    }    
    
    public function search($search_v)
    {
    
        $query = "SELECT *
                    FROM
                      articles
                    WHERE
                      status = 'yes'
                     AND
                      (title_".get_lang()." like '%".$search_v."%'
                        OR
                       text_".get_lang()." like '%".$search_v."%')
                    ORDER BY `order`";
        
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
        
        $html = str_get_html($text);
        
        $pagebreaks = count($html->find('hr.pagebreak'));
        $readmore   = count($html->find('hr.readmore'));
        $modules    = $html->find('img.module');
        
            
        /*
         * fix images src to full path
         */
        foreach($html->find('img') as $key => $img){
            
            if(!preg_match('/^http:\/\//', $img->src)){
                
                $img->src = base_url($img->src);                
                $html->find('img', $key)->src = $img->src;    
                
            }
            
        }
        
        
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
        
        /*
         * fix object data and src to full path
         */
        foreach($html->find('video') as $key => $video){
        
            if(!preg_match('/^http:\/\//', $video->src)){
                
                $video->src = base_url($video->src);                
                $html->find('video', $key)->src = $video->src;
                
                $video->poster = base_url($video->poster);
                $html->find('video', $key)->poster = $video->poster;
            }
            
        }
        
        
        /*
         * fix links to full path
         */
        foreach($html->find('a') as $key => $a){
                        
            if(!preg_match('/^http:\/\//', $a->href) && !preg_match('/^mailto:/', $a->href)){
                
                $a->href = site_url($a->href);                
                $html->find('a', $key)->href = $a->href; 
                
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
                $content = '<iframe class="load_module_iframe" src="'.site_url('load/module/'.$module_id).'" ></iframe>';
            }
            else{                
                $content = Module::_load_module($module_id);            
            }
            
            if(preg_match('/^popup/', $type)){
                
                $content = '<a href  = "'.site_url('articles').'" 
                               class = "load_jquery_ui" 
                               lang  = "dialog-'.$module_id.'" >'.$label.'</a>

                                <!-- start jquery UI -->
                                <div id    = "dialog-'.$module_id.'"
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
    
}