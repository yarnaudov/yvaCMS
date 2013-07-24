<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
    
    // active translation language_id
    public $language_id;
    
    // user access
    public $access;
    
    // all information about current user
    public $user;
    
    // all information about installed components
    public $components = array();
    
    // all information about installed modules
    public $modules = array();
    
    // all active menus on page
    public $current_menu;
    
    public function __construct()
    {

        parent::__construct();
        
	// get all settings
	$settings = $this->Setting->getSettings();
	
	// check environment
	error_reporting(0);
	$this->session->unset_userdata('error_msg');
	
	switch ($settings['environment'])
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
		$this->session->set_userdata('error_msg', 'The application environment is not set correctly. Please go to <a href="'.site_url('settings').'" >Settings</a> to fix this');
	    break;
	}
	
        $this->load->library('Lang_lib');        
        
        // load system languages
        $this->load->language('apanel_labels');
        $this->load->language('apanel_msg');
        
        //$this->load->model('Adm_menu');
        $this->load->model('Ap_menu');
        
        /*
         * get current translation
         */
        $this->language_id = $this->session->userdata('trl') == "" ? $this->Language->getDefault() : $this->session->userdata('trl');
        $this->session->unset_userdata('trl');
        if(isset($_POST['translation'])){
            $this->language_id = $_POST['translation'];
            if(isset($_POST['uset_posts'])){
                $this->input->post = array();
                $_POST = array();
            }
        }
        
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
        
	$this->jquery_ext->add_library("scripts.js");
	
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
	    
	    // stupid fix for the stupid IE !
	    $this->load->library('user_agent');
	    if($this->agent->browser() == 'Internet Explorer' && preg_match('/media/', $_SERVER['REQUEST_URI'])){
		exit;
	    }
	    
	    $_SESSION['no_access_page'] = $_SERVER['REQUEST_URI'];
	    $this->session->set_userdata('no_access_page', $_SERVER['REQUEST_URI']);
	    redirect(current_url());
	    exit;
        }
        else{
            unset($_SESSION['no_access_page']);
        }
        
        
        $this->jquery_ext->add_library("jquery.fixFloat.js");            
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
        
        $page = "";
	if($this->page > 1){
	    $page = "?page=".$this->page;
	}
        
        // delete
        if(isset($_POST['delete'])){
            $result = $model->delete();
            if($result == true){                
                redirect($redirect.$page);
                exit();
            }
        }
	
	// copy
        if(isset($_POST['copy'])){
            $result = $model->copy();
            if($result == true){
                redirect($redirect.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $model->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                redirect($redirect.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $model->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect($redirect.$page);
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
        
        // set filters
        if(isset($_POST['search'])){
            $filters = array();
            foreach($_POST['filters'] as $name => $value){
                if(!empty($value) && $value != 'none'){
                    $filters[$name] = trim($value);
                }
            }            
            $this->session->set_userdata($session.'_filters', $filters);
            redirect($redirect);
            exit();
        }
        
        // set css class on sorted element
        $order_by = $this->session->userdata($session.'_order') == '' ? '`order`' : $this->session->userdata($session.'_order');
        $elm_id   = str_replace(array('`',' DESC'), '', $order_by);
        $class    = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script   = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
	// fix for sort when page loaded via ajax
	if($this->input->is_ajax_request()) {
	    echo $elm_id.'&'.$class.'&';
	}
	
        // set data       
        $data['order_by']  = $this->session->userdata($session.'_order')        == '' ? '`order`' : $this->session->userdata($session.'_order');
        $data['order']     = trim(str_replace('`', '', $data['order_by']));
        $data['limit']     = $this->session->userdata($session.'_page_results') == '' ? $this->config->item('default_paging_limit') : $this->session->userdata($session.'_page_results');
        $data['filters']   = $this->session->userdata($session.'_filters')      == '' ? array() : $this->session->userdata($session.'_filters');
        $data['max_pages'] = 0;
        
        return $data;
        
    }
    
    function _loadComponetsData()
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
    
    function _loadModulesData()
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
        
        $template_file = FCPATH.'/../'.TEMPLATES_DIR.'/'.$this->Setting->getTemplate().'.php';
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
            return isset($data[$type]) ? $data[$type] : array();
        }
        
        return $data;
        
    }
    
    function _getTemplates($action = 'defaul')
    {
       
        $templates = array();
        
        if($action == 'modules'){
                        
            $templates_dir = FCPATH.'/../'.TEMPLATES_DIR.'/'.$this->Setting->getTemplate('main').'/views/modules';
            
            if(is_dir($templates_dir)){
            
                $handle = opendir($templates_dir);

                while (false !== ($file = readdir($handle))) { 
                    if(substr($file, 0, 1) == "." || is_dir($templates_dir.$file) || !preg_match('/.php$/', $file) ){
                        continue;                                                
                    }

                    $templates[] = 'views/modules/'.str_replace('.php', '', $file);

                }
                
            }
                        
        }
        elseif($action == 'content'){
                        
            $templates_dir = FCPATH.'/../'.TEMPLATES_DIR.'/'.$this->Setting->getTemplate('main').'/views/content';
            
            if(is_dir($templates_dir)){
            
                $handle = opendir($templates_dir);

                while (false !== ($file = readdir($handle))) { 
                    if(substr($file, 0, 1) == "." || is_dir($templates_dir.$file) || !preg_match('/.php$/', $file) ){
                        continue;                                                
                    }

                    $templates[] = 'views/content/'.str_replace('.php', '', $file);

                }
                
            }
                        
        }
        else{
        
            $templates_dir = FCPATH.'/../'.TEMPLATES_DIR.'/';
            $handle = opendir($templates_dir);

            while (false !== ($template = readdir($handle))) {                                                
                if(substr($template, 0, 1) == "." || !is_dir($templates_dir.$template)){
                  continue;                                                
                }

                $template_dir = $templates_dir.$template.'/';
                $handle2 = opendir($template_dir);

                while (false !== ($file = readdir($handle2))) { 
                    if(substr($file, 0, 1) == "." || is_dir($template_dir.$file) || !preg_match('/.php$/', $file) ){
                        continue;                                                
                    }

                    $templates[$template][] = $template.'/'.str_replace('.php', '', $file);

                }

            }
            
        }
        
        return $templates;
        
    }
    
}
