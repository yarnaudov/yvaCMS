<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Model {
    
    public $templates = array('main'                   => 'content/main',
                              'article'                => 'content/article',
                              'articles_list'		   => 'content/articles_list',
							  'custom_articles_list'   => 'content/articles_list',
							  'category_articles_list' => 'content/category_articles_list');
        
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
		elseif($this->category_id){
            
			$category = $this->Category->getDetails($this->category_id);

			if(empty($category)){
				$data['content'] = lang('msg_category_not_found');
			}
			else{

			$articles = $this->Article->getByCategory(array($category['id']));    

			$data['content'] = $this->load->view($this->templates['category_articles_list'], compact('category', 'articles'), true);

			}
            
        }
        elseif($this->menu_id == 'search'){ // stupid fix for search component to work with no menu assigned to it
			//$controllerInstance = & get_instance();
            //$data['content'] = $controllerInstance->getContent();
			$this->load->model('Component');
			$data['content'] = $this->Component->run('search');
        }
        else{
            
            $menu = $this->Menu->getDetails($this->menu_id);
	    
			# If menu type is 'menu' rewrite variable $menu with new menu
			if($menu['type'] == 'menu' && !empty($menu['params']['menu_id'])){
				$org_menu = $menu;
				$menu = $this->Menu->getDetails($menu['params']['menu_id']);
				$menu['id']    = $org_menu['id'];
				$menu['alias'] = $org_menu['alias'];
            }
            
            if(preg_match('/^components{1}/', $menu['type'])){
				$type = explode('/', $menu['type']);
                $menu['type'] = "component";
				$component = $type[1];
            }
	    
			$menu['link']  = module::menu_link($menu);
	    
			$data['menu'] = $menu;
                        
            if(isset($templates[$menu['alias']])){
                $menu['template'] = $templates[$menu['alias']];
            }
            elseif(isset($templates[$menu['type']])){
                $menu['template'] = $templates[$menu['type']];
            }
                        
            
            
            
            /*
             * set content template
             */
            $template_file = TEMPLATES_DIR.'/'.$this->Setting->getTemplate('main').'/'.$menu['content_template'].'.php';        
            if(file_exists(FCPATH.$template_file)){
                $content_template = '../../'.$template_file;
            }
            else{
                $content_template = @$this->templates[$menu['type']];
            }
                        
            switch($menu['type']){
                
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
		
				case "custom_articles_list":
                    		
					$articles = $this->Article->getByIds(isset($menu['params']['custom_articles']) ? $menu['params']['custom_articles'] : array());
						
					$data['content'] = $this->load->view($content_template, compact('menu', 'articles'), true);
		    
                break;
            
				case "sitemap":
					$data['content'] = self::_sitemap();
				break;
		
                case "component":
					$this->load->model('Component');
                    $data['content'] = $this->Component->run($component, $menu['params']);                                      
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
	
		$header = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8;\" />\n";	
        
        if($this->Setting->getEnvironment() != 'production'){
            $header .= "<meta http-equiv=\"cache-control\" content=\"no-cache\" />\n";
        }
	
        # generate title and meta data
        if($this->article_alias){
	    
            $article = $this->Article->getByAlias($this->article_alias);
            $title   = $article['title'];
	    
			$meta_description = $article['meta_description'];
            $meta_keywords    = $article['meta_keywords'];
	    
			if($this->menu_id != '' && (empty($meta_description) || empty($meta_keywords)) ){
				$menu = $this->Menu->getDetails($this->menu_id);

				if(empty($meta_description)){
					$meta_description = $menu['meta_description'];
				}

				if(empty($meta_keywords)){
					$meta_keywords = $menu['meta_keywords'];
				}

			}
	    
        }
		elseif($this->category_id){
			
			$category = $this->Category->getDetails($this->category_id);			
			$title = $category['title'];
			
		}
        elseif($this->menu_id == 'search'){ // stupid fix for search component to work with no menu assigned to it
	    $this->load->language('components/search');
            $title = lang('label_search');
        }
        else{
	    
            $menu = $this->Menu->getDetails($this->menu_id);
            
	    $title = $menu['title'];
	    
            $description = trim(strip_tags($menu['description']));            
            if(isset($menu['description_as_page_title']) && $menu['description_as_page_title'] == 'yes' && !empty($description) ){
                $title = $description;
            }

            $meta_description = $menu['meta_description'];
            $meta_keywords    = $menu['meta_keywords'];
	    
        }
        
        if($this->Setting->getSiteNameInTitle() ==  'yes'){
            $title .= ' '.$this->Setting->getSiteNameInTitleSeparator().' '.$this->Setting->getSiteName();
        }
        
        $header .= "<title>".$title."</title>\n";
        
        
        # load meta data tag       
        if(!empty($meta_description)){
            $header .= "<meta name=\"description\" content=\"".$meta_description."\" >\n";
        }elseif($this->Setting->getMetaDescription()){
	    $meta_description = $this->Setting->getMetaDescription();
            $header .= "<meta name=\"description\" content=\"".$meta_description."\" >\n";
        }
        
        if(!empty($meta_keywords)){
            $header .= "<meta name=\"keywords\" content=\"".$meta_keywords."\" >\n";
        }elseif($this->Setting->getMetaKeywords()){
	    $meta_keywords = $this->Setting->getMetaKeywords();
            $header .= "<meta name=\"keywords\" content=\"".$meta_keywords."\" >\n";
        }
        
		# change controller properties
		get_instance()->meta_description = isset($meta_description) ? $meta_description : '';
		get_instance()->meta_keywords    = isset($meta_keywords) ? $meta_keywords : '';
	
        /*
         * robots
         */
        if($this->Setting->getRobots()){
            $header .= "<meta name=\"robots\" content=\"".$this->Setting->getRobots()."\" />\n";
        }
	
		$header .= "<script type=\"text/javascript\" >var base_url = '".base_url()."';var site_url = '".rtrim(site_url(), '/')."';</script>\n";
                               
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
				if($menu['type'] == 'articles_list'){

					$articles = self::_get_articles($menu);
					if(count($articles) == 0){
						continue;
					}

					foreach($articles as $article){

						if($menu['parent_id'] != NULL){
							$sitemap_items[$category['id']]['children'][$menu['parent_id']]['children'][$menu['id']]['children'][$article['id']] = array('title' => $article['title'], 'link' => $menu_link.'/article/'.$article['alias'], 'updated_on' => $article['updated_on']);
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
			$sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" 
								 xmlns:image=\"http://www.google.com/schemas/sitemap-image/1.1\" 
								 xmlns:video=\"http://www.google.com/schemas/sitemap-video/1.1\">\n";
		}

		foreach($sitemap_items as $sitemap_item){

			if(isset($sitemap_item['link'])){

			$sitemap .= "<url>\n";	    
			$sitemap .= "<loc>".$sitemap_item['link']."</loc>\n";	    
			if(isset($sitemap_item['updated_on'])){
				$objDateTime = new DateTime($sitemap_item['updated_on']);
				$sitemap .= "<lastmod>".$objDateTime->format('c')."</lastmod>\n";
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