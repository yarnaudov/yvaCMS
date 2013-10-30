<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    public $menu_id = '';
    public $menu_link;
    public $current_menus = array();
    public $language_id;
    
    public $article_alias;
    public $category_id;
    
    public $template;
    public $template_main;
    public $data;
    
    public $meta_description;
    public $meta_keywords;
    
    function __construct()
    {
        
        parent::__construct();
        
		$this->load->model('Setting');
	
        $this->load->library('Lang_lib');
        
        $this->load->model('Category');
        $this->load->model('Menu');
        $this->load->model('Article');
        $this->load->model('Banner');
        $this->load->model('Custom_field');
        $this->load->model('Module');
        $this->load->model('Content');        
        $this->load->model('Component');
        
        $this->load->helper('simple_html_dom');
        
        $this->language_id = $this->Language->getDetailsByAbbr(get_lang(), 'id');
	
		# reload settings for correct language
		$this->Setting->getSettings();
	
		# check environment
		error_reporting(0);
		switch ($this->Setting->getEnvironment())
		{
			case 'development':
			error_reporting(E_ALL);
			$this->output->enable_profiler(TRUE);
			break;

			case 'testing':
			case 'production':
			error_reporting(0);
			break;

			default:
			exit('The application environment is not set correctly.');
			break;
		}

		# Set settings
		$this->template = $this->Setting->getTemplate();
		$this->template_main = $this->Setting->getTemplate('main');

		# load system language
		$this->load->language('system');

		self::_getActiveContent();

		# set menu link
		$menu = $this->Menu->getDetails($this->menu_id);
        $this->menu_link = $this->Module->menu_link($menu);
	
		# load validation js if article is loaded
		if($this->article_alias || $this->Menu->getDetails($this->menu_id, 'type') == 'article'){

			$this->jquery_ext->add_plugin('validation');
			$this->jquery_ext->add_library('check_captcha.js');

			/*
			 * load validation library language file
			 */
			$file = 'validation/localization/messages_'.get_lang().'.js';
			if(file_exists('js/'.$file)){
			$this->jquery_ext->add_library($file);
			}

		}
        
        // If tamplate is assignt to menu load it insted of default one
        if(isset($menu['main_template']) && $menu['main_template'] != 'default'){
            $this->template = $menu['main_template'];
	    $template = explode("/", $menu['main_template']);
	    $this->template_main = current($template);
        }
        
		$this->load->add_package_path(TEMPLATES_DIR.'/'.$this->template_main.'/');
	
		# load template language if exists
		if(file_exists(TEMPLATES_DIR . '/' . $this->template_main . '/language/' . get_lang() . '/template_lang.php') ||
		   file_exists(TEMPLATES_DIR . '/' . $this->template_main . '/language/en/template_lang.php') ){
			$this->load->language('template');
		}
	
        # Set settings for template
        $this->data['SiteName']        = $this->Setting->getSiteName();
        $this->data['MetaDescription'] = $this->Setting->getMetaDescription();
        $this->data['MetaKeywords']    = $this->Setting->getMetaKeywords();
        $this->data['robots']          = $this->Setting->getRobots();
        
        $script = "$( '#dialog:ui-dialog' ).dialog( 'destroy' );   
                   $( '.jquery_ui' ).dialog({
                       autoOpen: false,
                       resizable: true,
                       modal: true,
                       position: ['top', 50],
                       width: 'auto',
                       close: function(event, ui){
                           $('#jquery_ui_iframe').remove();
                       },
                       buttons: {
                            'OK': function() {
                                $( this ).dialog( 'close' );
                            }
                       }
                   });
                   $('.load_jquery_ui').bind('click', function() {    
        
                       var id = $(this).attr('lang');
        
                       $( '#'+id ).dialog( 'open' );
                       return false;
        
                   });";
        $this->jquery_ext->add_script($script);
        
    }
    
    private function _getActiveContent()
    {
		
		# keep compatibility with old templates
		$uri_string = preg_replace('/:/', '/', $this->uri->uri_string());
		
		$uri = explode('/', $uri_string);		
        $uri = array_reverse($uri);
	
		# check if article is selected
        if(count($uri) == 2 && $uri[1] == 'article'){
            $this->article_alias = $uri[0];
        }
	
		# check if category is selected
		elseif(count($uri) == 2 && $uri[1] == 'category'){
			$this->category_id = str_replace("id", "", $uri[0]);
		}
	
		# stupid fix for search component to work with no menu assigned to it
        elseif($uri[0] == 'search'){
            $this->menu_id = 'search';
        }
	
		# check for menu to load
        else{
            
			# check if article is selected
            if(isset($uri[1]) && $uri[1] == 'article'){
                $this->article_alias = $uri[0];
				unset($uri[0], $uri[1]);
            }
			
            # seach for menu by alias              
            foreach($uri as $alias){
                $this->menu_id = $this->Menu->getByAlias($alias, 'id');
                if($this->menu_id != ''){
                    break;
                }
            }
            
            # if no menu found load default one
            if($this->menu_id == ''){
                $this->menu_id = $this->Menu->getDefault('id');
            }       
            
            # get all parent menus of current one
            $this->current_menus = $this->Menu->getParents($this->menu_id);
	    
			# get menu details
            $menu = $this->Menu->getDetails($this->menu_id);
            
            # If menu type is 'menu' rewrite variable $menu with new menu but save alias from original menu
            if($menu['type'] == 'menu' && !empty($menu['params']['menu_id'])){
                
                //$this->current_menus[] = $menu['menu_id'];
                
				$alias = $menu['alias'];
                $menu = $this->Menu->getDetails($menu['params']['menu_id']);
                $menu['alias'] = $alias;
                
            }
            
        }
	
    }
    
    function _parseTemplateFile()
    {
                
        $template_file = FCPATH . TEMPLATES_DIR.'/'.$this->template.'.php';
        if(!file_exists($template_file)){
            return $this->load->view('template_not_found', '', true);
        }
        
        $html = $this->load->view('../../'.TEMPLATES_DIR.'/'.$this->template, '', true);
        $html2 = str_get_html($html);
    
		# include language in html tag
		$html2->find('html', 0)->lang = get_lang();
	
		$html = $html2;
	
		#set headers here so module have access to meta data!
		$header = $this->Content->header();
	
        foreach($html2->find('include') as $include){
            if($include->type == 'module'){
                $html = str_replace($include, $this->Module->load($include->name), $html);
            }
            elseif($include->type == 'banner'){
				$random = $include->random == 'true' ? true : false;
                $html = str_replace($include, $this->Banner->load($include->name, $random), $html);
            }
            elseif($include->type == 'content'){
                $html = str_replace($include, $this->Content->load(), $html);
            }
            elseif($include->type == 'header'){
                $include_header = $include;
            }
			elseif($include->type == 'js'){
				$include_js = $include;
            }
        }

		#include headers after all modules are ready	
		if(is_object($this->jquery_ext)){

			if(isset($include_js)){

			ob_start();
			$this->jquery_ext->output('js');
			$html = str_replace($include_js, ob_get_clean(), $html);

			ob_start();
			$this->jquery_ext->output('css');
			$header .= ob_get_clean();

			}
			else{

			ob_start();
			$this->jquery_ext->output();
			$header .= ob_get_clean();

			}

		}	
	
		if(isset($include_header)){
			$html = str_replace($include_header, $header, $html);
		}
	
		# remove new lines, comments and spaces
		if($this->Setting->getEnvironment() != 'development'){
			$html = preg_replace('/(\r\n|\n|\r|\t)/m', '', $html);
			$html = preg_replace('/\s+/', ' ', $html);
			$html = preg_replace('/<!--(.*)-->/Uis', ' ', $html);
		}
	
        return $html;
        
    }
    
}