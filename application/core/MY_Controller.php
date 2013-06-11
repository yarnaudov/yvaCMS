<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    public $menu_id = '';
    public $current_menus = array();
    public $language_id;
    public $article_alias;
    
    public $template;
    public $data;
    
    function __construct()
    {
        
        parent::__construct();
        
        $this->load->library('Lang_lib');
        
        $this->load->model('Category');
        $this->load->model('Menu');
        $this->load->model('Article');
        $this->load->model('Image');
        $this->load->model('Banner');
        $this->load->model('Custom_field');
        $this->load->model('Module');
        $this->load->model('Content');        
        $this->load->model('Component');
        
        $this->load->helper('simple_html_dom');
        
        $this->load->language('system');
        
        $this->language_id = $this->Language->getDetailsByAbbr(get_lang(), 'id');
        
        $this->load->model('Setting');
        
        
        /*
         * Set settings
         */
        $this->template = $this->Setting->getTemplate();

        $uri = explode('/', $this->uri->uri_string());
        $uri = array_reverse($uri);
        
        if(count($uri) == 1 && current(explode(':', $uri[0])) == 'article'){
            $this->article_alias = end(explode(':', $uri[0]));
        }
        elseif($uri[0] == 'search'){ // stupid fix for search component to work with no menu assigned to it
            $this->menu_id = 'search';
            $this->current_menus = array();
        }
        else{
            
            //seach for menu by alias              
            foreach($uri as $alias){
                $this->menu_id = $this->Menu->getByAlias($alias, 'id');
                if($this->menu_id != ''){
                    break;
                }
            }
            
             //if no menu found load default one
            if($this->menu_id == ''){
                $this->menu_id   = $this->Menu->getDefault('id');
            }       
            
            // get all parent menus of current one
            $this->current_menus = $this->Menu->getParents($this->menu_id);
            if(current(explode(':', $this->uri->segment(2))) == 'article'){
                $this->article_alias = end(explode(':', $this->uri->segment(2)));
            }
            
            $menu = $this->Menu->getDetails($this->menu_id);
            
            // If menu type is 'menu' rewrite variable $menu with new menu but save alias from original menu
            if($menu['params']['type'] == 'menu' && !empty($menu['params']['menu_id'])){
                
                $this->current_menus[] = $menu['menu_id'];
                
                $alias = $menu['alias'];
                $menu = $this->Menu->getDetails($menu['params']['menu_id']);
                $menu['alias'] = $alias;
                
            }            
            
            // If menu type is 'component' set route to component and redirect the page 
            if(preg_match('/^components{1}/', $menu['params']['type'])){                     
                $this->setRoute($menu);                
            }
            
        }
        
        // If tamplate is assignt to menu load it insted of default one
        if(isset($menu['main_template']) && $menu['main_template'] != 'default'){
            $this->template = $menu['main_template'];
        }
        
        /*
         * Set settings for template
         */
        $this->data['SiteName']        = $this->Setting->getSiteName();
        $this->data['MetaDescription'] = $this->Setting->getMetaDescription();
        $this->data['MetaKeywords']    = $this->Setting->getMetaKeywords();
        $this->data['robots']          = $this->Setting->getRobots();
        
        $script = "var base_url = '".base_url()."';
                   var site_url = '".site_url()."';";
                       
        $this->jquery_ext->add_script($script, 'general');
        
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
    
    function setRoute($menu)
    {
        
        $component = explode('/', $menu['params']['type']);
        $menu['params']['component'] = $component[1];
        
        include APPPATH . "cache/routes.php";
                        
        include_once "components/" . $menu['params']['component'] . "/controllers/" . $menu['params']['component'] . ".php";
        
        $link = $this->Module->menu_link($menu, false);
        $com_route_key   = '(\w{2})' . $link . '(.*)';
        $com_route_value = $menu['params']['component']::getRoute($menu);
        
        if(!isset($route[$com_route_key]) || $route[$com_route_key] != $com_route_value){

            $data  = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";
            $data .= '$route["' . $com_route_key . '"] = "' . $com_route_value . '";';

            $this->load->helper('file');
            write_file(APPPATH . "cache/routes.php", $data);
            redirect($link);
            
        }
        
        if($this->uri->segment(1) == ''){
            redirect($link);  
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
    
        foreach($html2->find('include') as $include){
            if($include->type == 'module'){
                $html = str_replace($include, $this->Module->load($include->name), $html);
            }
            elseif($include->type == 'banner'){
                $html = str_replace($include, $this->Banner->load($include->name), $html);
            }
            elseif($include->type == 'content'){
                $html = str_replace($include, $this->Content->load(), $html);
            }
            elseif($include->type == 'header'){
                $header = $this->Content->header();
                if(is_object($this->jquery_ext)){
                    ob_start();
                    $this->jquery_ext->output();
                    $header .= ob_get_clean();                     
                } 
                $html = str_replace($include, $header, $html);
            }
        }

        return $html;
        
    }
    
}