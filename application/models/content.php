<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Model {
    
    public $templates = array('main'         => 'content/main',
                              'article'      => 'content/article',
                              'article_list' => 'content/article_list');
        
    public function load($templates = array())
    {
        
        $templates = array_merge($this->templates, $templates);
                
        if($this->article_alias){
            
            $article = $this->Article->getByAlias($this->article_alias);            
            
            if(@$article['show_title'] == 'yes'){
              $data['title'] = $article['title_'.get_lang()];
            }
            $data['content'] = $this->Article->parceText(@$article['text_'.get_lang()]);
            //$data['content'] = @$article['text_'.get_lang()];
            
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
                        
            $data['title'] = $menu['title_'.get_lang()];

            /*
             * If menu type is 'menu' rewrite variable $menu with new menu
             */
            if($menu['type'] == 'menu' && !empty($menu['params']['menu_id'])){
                $menu = $this->Menu->getDetails($menu['params']['menu_id']);
            }
            
            switch($menu['type']){
                
                case "article":
                case "component":
                    
                    $article = $this->Article->getDetails($menu['params']['article_id']);
                    if(@$article['show_title'] == 'yes'){
                      $data['title'] = $article['title_'.get_lang()];
                    }
                    
                    $data['content'] = $this->Article->parceText(@$article['text_'.get_lang()]);
                                        
                    if($menu['type'] == 'component'){
                        
                        $data['content'] = '<div class="article" >'.$data['content'].'</div>';
                        
                        $data['content'] .= $this->data['content'];
                        
                    }
                    
                break;
                
                case "articles_list":
                    $data2['menu']     = $menu;
                    $data2['articles'] = $this->Article->getByCategory($menu['params']['category_id']);
                    $data['content']   = $this->load->view($menu['template'], $data2, true);                    
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
            $title = $article['title_'.get_lang()];
        }
        elseif($this->menu_id == 'search'){ // stupid fix for search component to work with no menu assigned to it
            $title = lang('label_search');
        }
        else{
            $menu = $this->Menu->getDetails($this->menu_id);
            
            $description = trim(strip_tags($menu['description_'.get_lang()]));
            
            if(isset($menu['params']['description_as_page_title']) && $menu['params']['description_as_page_title'] == 'yes' && !empty($description) ){
                $title = $description;
            }
            else{            
                $title = $menu['title_'.get_lang()];
            }
            
            $meta_description = $menu['meta_description_'.get_lang()];
            $meta_keywords    = $menu['meta_keywords_'.get_lang()];
        }
        
        if($this->Settings->getSiteNameInTitle() ==  'yes'){
            $title .= ' '.$this->Settings->getSiteNameInTitleSeparator().' '.$this->Settings->getSiteName();
        }
        
        echo "<title>".$title."</title>\n";
        
        
        /*
         * generate meta data
         */        
        if(isset($meta_description) && !empty($meta_description)){
            echo "<meta name=\"description\" content=\"".$meta_description."\" >\n";
        }elseif($this->Settings->getMetaDescription()){
            echo "<meta name=\"description\" content=\"".$this->Settings->getMetaDescription()."\" >\n";
        }
        
        if(isset($meta_keywords) && !empty($meta_keywords)){
            echo "<meta name=\"keywords\" content=\"".$meta_keywords."\" >\n";
        }elseif($this->Settings->getMetaKeywords()){
            echo "<meta name=\"keywords\" content=\"".$this->Settings->getMetaKeywords()."\" >\n";
        }
        
        /*
         * robots
         */
        if($this->Settings->getRobots()){
            echo "<meta name=\"robots\" content=\"".$this->Settings->getRobots()."\" />\n";
        }
        
    }
    
}