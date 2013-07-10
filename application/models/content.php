<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Model {
    
    public $templates = array('main'          => 'content/main',
                              'article'       => 'content/article',
                              'articles_list' => 'content/articles_list');
        
    public function load($templates = array())
    {
        
        $data['content'] = '';
        
        $templates = array_merge($this->templates, $templates);
                
        if($this->article_alias){
            
            $article = $this->Article->getByAlias($this->article_alias);    

            if(empty($article)){
                $data['content'] = lang('msg_article_not_found');
            }
            else{

                $article['text'] = $this->Article->parceText($article['text']);
		$data['content'] = $this->load->view($this->templates['article'], compact('article'), true);

                $this->Article->statistic($article['id']);
                
            }
            
        }
        elseif($this->menu_id == 'search'){ // stupid fix for search component to work with no menu assigned to it
            $data['content'] = $this->data['content'];
        }
        else{
            
            $menu = $this->Menu->getDetails($this->menu_id);
                        
            if(isset($templates[$menu['alias']])){
                $menu['template'] = $templates[$menu['alias']];
            }
            elseif(isset($templates[$menu['params']['type']])){
                $menu['template'] = $templates[$menu['params']['type']];
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
            
            
            /*
             * set content template
             */
            $template_file = TEMPLATES_DIR.'/'.$this->Setting->getTemplate('main').'/'.$menu['content_template'].'.php';        
            if(file_exists(FCPATH.$template_file)){
                $content_template = '../../'.$template_file;
            }
            else{
                $content_template = @$this->templates[$menu['params']['type']];
            }
                        
            switch($menu['params']['type']){
                
                case "article":
                
                    if(!empty($menu['params']['article_id'])){
		
			$article = $this->Article->getDetails($menu['params']['article_id']); 
			 
			if(empty($article)){
			    $article['show_title'] = 'no';
			    $article['text'] = lang('msg_article_not_found');
			}
			else{			    
			                      
			    $article['text'] = $this->Article->parceText($article['text']);

			    $this->Article->statistic($article['id']);
			    
			}
                        
                    }
                    else{
                        $article['show_title'] = 'no';
                        $article['text'] = lang('msg_article_not_selected');
                    }
                    
                    $data['content'] = $this->load->view($content_template, compact('article'), true);
                    
                break;
                
                case "articles_list":
                    
                    if(isset($menu['params']['categories'])){ 
			
                        $articles = self::_get_articles($menu);						
			$data['content'] = $this->load->view($content_template, compact('menu', 'articles'), true);
                    
                    }
                    else{
		       $data['content'] = lang('msg_category_not_selected');
                    }
		    
                break;
            
		case "sitemap":
		    $data['content'] = self::_sitemap();
		break;
		
                case "component":		 
                    $data['content'] = $this->data['content'];                                        
                break;
                
            }
            
        }
        
        $content = $this->load->view($templates['main'], $data, true); 
        
        return $content;
        
    }
    
    private function _get_articles($menu)
    {
	if(isset($menu['params']['categories'])){
	
	    if(in_array('most_popular', $menu['params']['categories'])){
		$articles = $this->Article->getMostPopular($menu['params']['order'], $menu['params']['number'], 'menu'.$menu['id']);
	    }
	    elseif(in_array('most_commented', $menu['params']['categories'])){
		$articles = $this->Article->getMostCommented($menu['params']['order'], $menu['params']['number'], 'menu'.$menu['id']);
	    }
	    else{
		$articles = $this->Article->getByCategory($menu['params']['categories'], $menu['params']['order'], $menu['params']['number'], 'menu'.$menu['id']);
	    }
	    
	    return $articles;
	
	}
		
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
	
	$header .= "<script type=\"text/javascript\" >
		        var base_url = '".base_url()."';
                        var site_url = '".site_url()."';
		    </script>\n";
                               
        return $header;
        
    }
    
    private function _sitemap()
    {
	$sitemap_items = array();
	
	$categories = $this->Category->getCategories('menus');
	
	foreach($categories as $category){
	    
	    # get menus list
	    $menus = $this->Menu->getByCategory($category['id']);
	    if(count($menus) == 0){
		continue;
	    }
	    	    
	    $sitemap_items[$category['id']] = array('title' => $category['title']);
	    
	    foreach($menus as $menu){
		
		$menu_link = $this->Module->menu_link($menu);
		
		if($menu['parent_id'] != NULL){
		    $sitemap_items[$category['id']]['children'][$menu['parent_id']]['children'][$menu['id']] = array('title' => $menu['title'], 'link' => $menu_link, 'updated_on' => $menu['updated_on']);
		}
		else{
		    $sitemap_items[$category['id']]['children'][$menu['id']] = array('title' => $menu['title'], 'link' => $menu_link, 'updated_on' => $menu['updated_on']);
		}
		
		# get articles list
		if($menu['params']['type'] == 'articles_list'){
		    
		    $articles = self::_get_articles($menu);
		    if(count($articles) == 0){
			continue;
		    }
		    
		    foreach($articles as $article){
			
			if($menu['parent_id'] != NULL){
			    $sitemap_items[$category['id']]['children'][$menu['parent_id']]['children'][$menu['id']]['children'][$article['id']] = array('title' => $article['title'], 'link' => $menu_link.'/article:'.$article['alias'], 'updated_on' => $article['updated_on']);
			}
			else{
			    $sitemap_items[$category['id']]['children'][$menu['id']]['children'][$article['id']] = array('title' => $article['title'], 'link' => $menu_link.'/article:'.$article['alias'], 'updated_on' => $article['updated_on']);
			}
		    }

		}

	    }

	    
	}	
	
	if($this->input->get('type') == 'xml'){
	    header('Content-type: text/xml');	
	    echo self::_sitemap_xml($sitemap_items);
	    exit;
	}
	else{
	    return self::_sitemap_html($sitemap_items);
	}
	
    }
    
    private function _sitemap_html($sitemap_items)
    {
	
	$sitemap = "<ul>";
	
	foreach($sitemap_items as $sitemap_item){
	    
	    $sitemap .= "  <li>\n";
	    
	    if(isset($sitemap_item['link'])){
		$sitemap .= "<a href=\"".$sitemap_item['link']."\" >\n";
	    }
	    
	    $sitemap .= "<span>".$sitemap_item['title']."</span>\n";
		
	    if(isset($sitemap_item['link'])){
		$sitemap .= "</a>\n";   
	    }
	    
	    if(isset($sitemap_item['children'])){
		$sitemap .= self::_sitemap_html($sitemap_item['children']);
	    }
	    
	    $sitemap .= "   </li>\n";
	    
	}
	
	$sitemap .= "</ul>";
	
	return $sitemap;
	
    }
    
    private function _sitemap_xml($sitemap_items, $children = false)
    {
	
	$sitemap = '';
	
	if($children == false){
	    $sitemap .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
	    $sitemap .= "<urlset xmlns=\"".current_url()."?type=xml\">\n";
	}
	
	foreach($sitemap_items as $sitemap_item){
	    	    
	    if(isset($sitemap_item['link'])){
	    
		$sitemap .= "<url>\n";	    
		$sitemap .= "<loc>".$sitemap_item['link']."</loc>\n";	    
		if(isset($sitemap_item['updated_on'])){
		    $sitemap .= "<lastmod>".$sitemap_item['updated_on']."</lastmod>\n";
		}	    
		$sitemap .= "<changefreq>weekly</changefreq>\n";	    
		$sitemap .= "</url>\n";
	    
	    }
	    
	    if(isset($sitemap_item['children'])){
		$sitemap .= self::_sitemap_xml($sitemap_item['children'], true);
	    }
	    
	}
	if($children == false){
	    $sitemap .= "</urlset>\n";	    
	}
	
	return $sitemap;
	
    }
    
}