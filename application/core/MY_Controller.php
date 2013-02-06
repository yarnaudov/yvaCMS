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
        if($this->Setting->getUrlSuffix()){
            $this->config->set_item('url_suffix', '.'.$this->Setting->getUrlSuffix());
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
            $this->menu_id = $this->Menu->getByAlias($alias, 'menu_id');
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
            if($menu['params']['type'] == 'menu' && !empty($menu['params']['menu_id'])){
                
                $this->current_menus[] = $menu['menu_id'];
                
                $alias = $menu['alias'];
                $menu = $this->Menu->getDetails($menu['params']['menu_id']);
                $menu['alias'] = $alias;
                
            }
            
            
            /*
             * If menu type is 'component' set route to component and redirect the page 
             */
            if(preg_match('/^components{1}/', $menu['params']['type'])){                     
                $this->setRoute($menu);                
            }
            
        }
        
        /*
         * If tamplate is assignt to menu load it insted of default one
         */
        if(isset($menu['template']) && $menu['template'] != 'default'){
            $this->template = $menu['template'];
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
    
    function _parseTemplateFile()
    {
                
        $template_file = FCPATH . 'templates/'.$this->template.'.php';
        if(!file_exists($template_file)){
            return $this->load->view('template_not_found', '', true);
        }
        
        $html = $this->load->view('../../templates/'.$this->template, '', true);
        $html2 = str_get_html($html);
    
        foreach($html2->find('include') as $include){
            if($include->type == 'module'){
                $html = str_replace($include, $this->Module->load($include->name), $html);
            }
            elseif($include->type == 'banner'){
                $html = str_replace($include, '[-----banner-----]', $html);
            }
            elseif($include->type == 'content'){
                $html = str_replace($include, $this->Content->load(), $html);
            }
        }

        return $html;
        
    }
    
}