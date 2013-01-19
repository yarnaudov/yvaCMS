<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    public $menu_id = '';
    public $current_menus = array();
    public $language_id;
    public $article_alias;
    
    public $template;
    public $data;
    
    function __construct() {
        
        parent::__construct();
        
        $this->load->library('Lang_lib');
        
        $this->load->model('Settings');
        $this->load->model('Category');
        $this->load->model('Menu');
        $this->load->model('Article');
        $this->load->model('Image');
        $this->load->model('Banner');
        $this->load->model('Custom_field');
        $this->load->model('Module');
        $this->load->model('Content');
        
        $this->load->model('Component');
        
        $this->load->language('system');
        
        /*
         * Set settings
         */
        $this->template = $this->Settings->getTemplate();
        if($this->Settings->getUrlSuffix()){
            $this->config->set_item('url_suffix', '.'.$this->Settings->getUrlSuffix());
        }

        $alias = $this->uri->segment(1) != '' ? $this->uri->segment(1) : $this->Menu->getDefault('alias');        
        $alias = preg_replace('/'.$this->config->item('url_suffix').'$/', '', $alias);
        
        if(current(explode(':', $alias)) == 'article'){
            $this->article_alias = end(explode(':', $alias));
        }
        elseif($alias == 'search'){ // stupid fix for search component to work with no menu assigned to it
            $this->menu_id = 'search';
            $this->current_menus = array();
        }
        else{
            $this->menu_id       = $this->Menu->getByAlias($alias, 'menu_id');
            if($this->menu_id == ''){
                $this->menu_id   = $this->Menu->getDefault('menu_id');
            }                        
            $this->current_menus = $this->Menu->getParents($this->menu_id);
            if(current(explode(':', $this->uri->segment(2))) == 'article'){
                $this->article_alias = end(explode(':', $this->uri->segment(2)));
            }
            
            $menu = $this->Menu->getDetails($this->menu_id);
            
            
            /*
             * If menu type is 'menu' rewrite variable $menu with new menu but save alias from original menu
             */
            if($menu['type'] == 'menu' && !empty($menu['params']['menu_id'])){
                
                $alias = $menu['alias'];
                $menu = $this->Menu->getDetails($menu['params']['menu_id']);
                $menu['alias'] = $alias;
                
                $this->current_menus[] = $menu['menu_id'];
                
            }
            
            
            /*
             * If menu type is 'component' set route to component and redirect the page 
             */
            if($menu['type'] == 'component'){                     
                $this->setRoute($menu);                
            }
            
        }
        
        /*
         * If tamplate is assignt to menu load it insted of default one
         */
        if($menu['params']['template'] != 'default'){
            $this->template = $menu['params']['template'];
        }

        $this->language_id = $this->Language->getDetailsByAbbr('bg', 'language_id');
        
        /*
         * Set settings for template
         */
        $this->data['SiteName']        = $this->Settings->getSiteName();
        $this->data['MetaDescription'] = $this->Settings->getMetaDescription();
        $this->data['MetaKeywords']    = $this->Settings->getMetaKeywords();
        $this->data['robots']          = $this->Settings->getRobots();
        
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
        
        include APPPATH . "cache/routes.php";
                        
        include_once "components/" . $menu['params']['component'] . "/controllers/" . $menu['params']['component'] . ".php";
        
        $com_route_key   = '(\w{2})/' . $menu['alias'] . '(.*)';
        $com_route_value = $menu['params']['component']::getRoute($menu);
        
        if( (isset($route[$com_route_key]) && $route[$com_route_key] != $com_route_value && $menu['alias'] != $com_route_value) || !isset($route[$com_route_key]) ){

            $route[$com_route_key] = $com_route_value;

            foreach($route as $key => $value){                    
                $routes[] = '$route["' . $key . '"] = "' . $value . '";';                        
            }

            $data  = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";
            $data .= implode("\n", $routes);

            $this->load->helper('file');
            write_file(APPPATH . "cache/routes.php", $data);
            redirect($menu['alias']);
            
        }
        
        if($this->uri->segment(1) == ''){
            redirect($menu['alias']);  
        }
        
    }
    
}