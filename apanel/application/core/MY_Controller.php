<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
    
    // active translation language_id
    public $trl;
    
    // user access
    public $access;
    
    // all information about current user
    public $user;
    
    // all information about installed components
    public $components = array();
    
    // all information about installed modules
    public $modules = array();
    
    public $current_menu;
    
    public function __construct()
    {

        parent::__construct();
        
        $this->load->library('Lang_lib');        
        
        // load system languages
        $this->load->language('apanel_labels');
        $this->load->language('apanel_msg');
        
        //$this->load->model('Adm_menu');
        $this->load->model('Ap_menu');
        
        // set translation to default language
        $this->trl = $this->Language->getDefault();
        
        // load components data
        $this->components = $this->_loadComponetsData();
        
        // load modules
        $this->modules = $this->_loadModulesData();
        
        /*
         * check if user is logged in
         */
        if(!isset($_SESSION['user_id'])){            
            
            if(preg_match('/_ajax{1}$/', current_url())){                
                $this->jquery_ext->add_script("parent.location.reload();");
            }
            
        }
        else{
        	  
            $this->user = $this->User->getDetails($_SESSION['user_id']);
            $user_group = $this->User_group->getDetails($this->user['user_group_id']);
            
            $this->user['user_group_title'] = $user_group['title'];
            $access = $user_group['access'];
        	  
            if($access == '*'){        	  	
                $this->access = $this->Ap_menu->getAllMenus();
            }
            else{        	  
              $this->access = json_decode($access, true);
            }
            
        }
        
        //$this->Adm_menu->setConfig();
        $this->Ap_menu->setConfig();
        
        /*
         * Check access to pages. If user does not have access redirect to no_access page
         */
        $segment1 = $this->uri->segment(1);
        $segment2 = $this->uri->segment(2);
        $segment3 = $this->uri->segment(3);
        $segment4 = $this->uri->segment(4);
        
        $url = '';
        if(!empty($segment1)){
            $url = $segment1;
        }
                      
        if($segment2 == 'add' && !empty($segment3)){
           $url = $url.'/'.$segment3; 
        }
        elseif($segment2 == 'edit' && !empty($segment4) && $segment4 != 'history'){
           $url = $url.'/'.$segment4; 
        }
        elseif(!empty($segment2) && $segment2 != 'add' && $segment2 != 'edit' && $segment2 != 'index'){
            $url = $url.'/'.$segment2;
        }
        
        // get current menu id by alias
        $this->current_menu = $this->Ap_menu->getDetailsByAlias($url, 'id');
        
        if(@$this->access[$url] != 'on' && !in_array($url, $this->config->item('no_login')) && isset($_SESSION['user_id']) && !isset($_SESSION['no_access_page'])){
           $_SESSION['no_access_page'] = $_SERVER['REQUEST_URI'];
           $this->session->set_userdata('no_access_page', $_SERVER['REQUEST_URI']);
           redirect(current_url());
           exit;
        }
        else{
            unset($_SESSION['no_access_page']);
        }
        
        
        $this->jquery_ext->add_library("jquery/plugins/jquery.fixFloat.js");            
        $script = "$('select#language_switch').msDropDown();
                   $('#language_switch').change(function(){
                       window.location = $(this).val();
                   });
                   fixFloat($('#page_header'), 'page_header_clone');";
            
        $script .= "$('.multilang').attr('title', '".lang('msg_multilang_info')."');";
        
        /*
         * disable components menu
         */
        $script .= "$('#menu ul li a').each(function(){
                        var url = $(this).attr('href').split('/');
                        if(url[url.length-1] == 'components'){
                            $(this).click(function(){return false;});
                        }
                    });";
        
        /*
         * add class current to main menu when sum menu is focusted
         */
        $script .= "$('#menu ul li ul').hover(
                        function () {
                            $(this).parent().addClass('current2');
                        },
                        function () {
                            $(this).parent().removeClass('current2');
                        }
                    );";
        
        if(preg_match('/_ajax{1}$/', current_url())){ 
            $script .= "$('#main').css('padding', 0);";
        }
                
        $this->jquery_ext->add_script($script);
               
    }
    
    public function index($model, $session, $redirect)
    {
        
        // delete
        if(isset($_POST['delete'])){
            $result = $model->delete();
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect($redirect.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $model->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect($redirect.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $model->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect($redirect);
                exit();
            }
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata($session.'_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata($session.'_order', $_POST['order_by']);
            redirect($redirect);
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata($session.'_page_results', $_POST['page_results']);
            redirect($redirect);
            exit();            
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata($session.'_filters');
            redirect($redirect);
            exit();
        }
        
        // set css class on sorted element
        $order_by = $this->session->userdata($session.'_order') == '' ? '`order`' : $this->session->userdata($session.'_order');
        $elm_id   = str_replace(array('`',' DESC'), '', $order_by);
        $class    = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script   = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        
        // set data       
        $data['order_by'] = $this->session->userdata($session.'_order') == '' ? '`order`' : $this->session->userdata($session.'_order');
        $data['limit']    = $this->session->userdata($session.'_page_results') == '' ? $this->config->item('default_paging_limit') : $this->session->userdata($session.'_page_results');
        
        return $data;
        
    }
    
    private function _loadComponetsData()
    {
        
        $components = array();

        $components_dir = FCPATH.'components/';
        $handle = opendir($components_dir);
        while (false !== ($entry = readdir($handle))) { 
            if(substr($entry, 0, 1) == "." || !is_dir($components_dir.$entry)){
                continue;
            }  
            
            unset($component);
            include_once $components_dir.$entry.'/settings.php';
            
            $components[$entry] = $component;
                                                         
        }
        //print_r($components);
        return $components;
        
    }
    
    function _loadComponetLanguages($component)
    {
                
        foreach($this->components[$component]['languages'] as $language){
            $this->load->language('components/'.$language);
        }
        
    }
    
    private function _loadModulesData()
    {
        
        $modules = array();

        $modules_dir = FCPATH.'modules/';
        $handle = opendir($modules_dir);
        while (false !== ($entry = readdir($handle))) { 
            if(substr($entry, 0, 1) == "." || !is_dir($modules_dir.$entry)){
                continue;
            }  

            $modules[$entry] = $entry;
                                                         
        }

        return $modules;
        
    }

    function _loadModuleLanguage($module)
    {
        
        if(file_exists(FCPATH.APPPATH.'language/'.get_lang().'/modules/'.$module.'_lang.php')){
            $this->load->language('modules/'.$module);
        }
        
    }
    
    function _parseTemplateFile($type = null)
    {
    
        $this->load->helper('simple_html_dom');
        
        $template_file = FCPATH.'/../templates/'.$this->Setting->getTemplate().'/index.php';
        $html = file_get_html($template_file);
        
        $data = array();
        
        foreach($html->find('include') as $include){
            if($include->type == 'module'){
                $data['modules'][$include->name] = $include->name;
            }
            elseif($include->type == 'banner'){
                $data['banners'][$include->name] = $include->name;
            }
        }
        
        if($type != null){
            return $data[$type];
        }
        
        return $data;
        
    }
    
}
