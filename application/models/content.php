<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Model {
    
    public $templates = array('main'         => 'content/main',
                              'article'      => 'content/article',
                              'article_list' => 'content/articles_list');
        
    public function load($templates = array())
    {
        
        $templates = array_merge($this->templates, $templates);
                
        if($this->article_alias){
            
            $article = $this->Article->getByAlias($this->article_alias);            
            
            if(@$article['show_title'] == 'yes'){
              $data['title'] = $article['title'];
            }
            
            $data['content'] = $this->Article->parceText(@$article['text']);
            
        }
        elseif($this->menu_id == 'search'){ // stupid fix for search component to work with no menu assigned to it
            $data['content'] = $this->data['content'];
        }
        else{
            
            $menu = $this->Menu->getDetails($this->menu_id);
                        
            if(isset($templates[$menu['alias']])){
                $menu['template'] = $templates[$menu['alias']];
            }
            elseif(isset($templates[$menu['alias']])){
                $menu['template'] = $templates[$menu['type']];
            }
                        
            $data['title'] = $menu['title'];

            /*
             * If menu type is 'menu' rewrite variable $menu with new menu
             */
            if($menu['params']['type'] == 'menu' && !empty($menu['params']['menu_id'])){
                $menu = $this->Menu->getDetails($menu['params']['menu_id']);
            }
            
            if(preg_match('/^components{1}/', $menu['params']['type'])){
                $menu['params']['type'] = "component";
            }
            
            switch($menu['params']['type']){
                
                case "article":
                
                    $article = $this->Article->getDetails($menu['params']['article_id']);
                    
                    if($article['show_title'] == 'yes'){
                      $data['title'] = $article['title'];
                    }
                    
                    $data['content'] = $this->Article->parceText(@$article['text']);
                    
                break;
                
                case "component":
                                               
                    $data['content'] = $this->data['content'];
                                        
                break;
                
                case "articles_list":
                    $articles = $this->Article->getByCategory($menu['params']['category_id']);
                    $data['content'] = $this->load->view($this->templates['article_list'], compact('menu', 'articles'), true);                    
                break;
            
            }
            
        }
        
        $content = $this->load->view($templates['main'], $data, true); 
        
        return $content;
        
    }
    
    public function header()
    {
        /*
         * generate title
         */
        if($this->article_alias){
            $article = $this->Article->getByAlias($this->article_alias);
            $title = $article['title'];
        }
        elseif($this->menu_id == 'search'){ // stupid fix for search component to work with no menu assigned to it
            $title = lang('label_search');
        }
        else{
            $menu = $this->Menu->getDetails($this->menu_id);
            
            $description = trim(strip_tags($menu['description']));
            
            if(isset($menu['description_as_page_title']) && $menu['description_as_page_title'] == 'yes' && !empty($description) ){
                $title = $description;
            }
            else{            
                $title = $menu['title'];
            }
            
            $meta_description = $menu['meta_description'];
            $meta_keywords    = $menu['meta_keywords'];
        }
        
        if($this->Setting->getSiteNameInTitle() ==  'yes'){
            $title .= ' '.$this->Setting->getSiteNameInTitleSeparator().' '.$this->Setting->getSiteName();
        }
        
        $header = "<title>".$title."</title>\n";
        
        
        /*
         * generate meta data
         */        
        if(isset($meta_description) && !empty($meta_description)){
            $header .= "<meta name=\"description\" content=\"".$meta_description."\" >\n";
        }elseif($this->Setting->getMetaDescription()){
            $header .= "<meta name=\"description\" content=\"".$this->Setting->getMetaDescription()."\" >\n";
        }
        
        if(isset($meta_keywords) && !empty($meta_keywords)){
            $header .= "<meta name=\"keywords\" content=\"".$meta_keywords."\" >\n";
        }elseif($this->Setting->getMetaKeywords()){
            $header .= "<meta name=\"keywords\" content=\"".$this->Setting->getMetaKeywords()."\" >\n";
        }
        
        /*
         * robots
         */
        if($this->Setting->getRobots()){
            $header .= "<meta name=\"robots\" content=\"".$this->Setting->getRobots()."\" />\n";
        }
        
        return $header;
        
    }
    
}