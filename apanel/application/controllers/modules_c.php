<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules_c extends MY_Controller {
    
    public  $trl;
    public  $extension = 'modules';
    public  $page;
    public  $layout;
    private $module_id;
    private $modules = array();
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Module');        
        
        /*
         * get current translation
         */
        $this->trl = $this->session->userdata('trl') == "" ? Language::getDefault() : $this->session->userdata('trl');
        $this->session->unset_userdata('trl');
        if(isset($_POST['translation'])){         
            $this->trl = $_POST['translation'];
            if(isset($_POST['uset_posts'])){                
                $this->input->post = array();
                $_POST = array();
            }
        }
        
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
                
        $this->module_id = $this->uri->segment(3);
        
        /*
         * get modules
         */
        $modules_dir = FCPATH.'modules/';
        $handle = opendir($modules_dir);
        while (false !== ($entry = readdir($handle))) { 
            if(substr($entry, 0, 1) == "." || !is_dir($modules_dir.$entry)){
                continue;
           }  
           
           $this->load->language($entry);
            
           $this->modules[] = $entry;
                                                         
        }
        
    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit')
        {
            
            $this->load->model('Article');
            $this->load->model('Menu');
            $this->jquery_ext->add_library("check_actions_add_edit.js");
            
            $script = "$('select[name=type]').bind('change', function(){
                           $('form').submit();
                       });";
            
            if ($method == 'add'){
               
                $script .= "$('select[name=translation]').attr('disabled', true);";
                
            }
            elseif($method == 'edit'){
                
                $script .= "$('select[name=translation]').bind('change', function(){
                                $('form').append('<input type=\"hidden\" name=\"uset_posts\" value=\"true\" >');
                                $('form').submit();
                            });";
                
            }
            
            $script .= "$('.datepicker').datepicker({
                            showOn: 'button',
                            dateFormat: 'yy-mm-dd',
                            buttonImage: '".base_url('img/iconCalendar.png')."',
                            buttonImageOnly: true
                        });";
            $this->jquery_ext->add_script($script);
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){   
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
            
                if ($this->form_validation->run() == TRUE){
                    
                    $module_id = $this->Module->$method($this->module_id);
                                                            
                    if(isset($_POST['save'])){
                        redirect('modules/');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        /*
                         * save translation in cookie and use it to restore the correct translation
                         */
                        if(isset($_POST['translation'])){
                            $this->session->set_userdata('trl', $_POST['translation']);
                        }
                        redirect('modules/edit/'.$module_id);
                        exit();
                    }
                    
                }
            }
            
        }        
        
        $this->$method();

    }
    
    public function index()
    {
        
        $page = "";
        
        if($this->uri->segment(3) == ''){
            $this->layout = 'default';
            $redirect     = 'modules';
        }
        else{
            
            $this->jquery_ext->add_plugin('iframe_auto_height');        
            $script = "autoHeightIframe('jquery_ui_iframe');";
            
            $script .= "$('table.list tr.row td a').bind('click', function(){
                
                           var module_id = $(this).attr('href').split('/');
                           module_id     = module_id[module_id.length-1];
                           parent.$('#module').val(module_id);
                           
                           var module_name = $.trim($(this).html());
                           parent.$('#module_name').val(module_name);
                           
                           parent.$('#dialog-select-module').dialog('close');
                           
                           return false;
                           
                       });";
            $this->jquery_ext->add_script($script);
            
            $this->layout = $this->uri->segment(3);
            $redirect     = 'modules/index/'.$this->layout;
        }
        
        // delete modules
        if(isset($_POST['delete'])){
            $result = $this->Module->delete();
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
            $result = $this->Module->changeStatus($_POST['element_id'], $_POST['change_status']);
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
            $result = $this->Module->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect($redirect);
                exit();
            }
        }
        
        // set filters
        if(isset($_POST['search'])){
            $filters = array();
            if(isset($_POST['search_v']) && !empty($_POST['search_v'])){
                $filters['search_v'] = $_POST['search_v'];
            }
            if(isset($_POST['category']) && $_POST['category'] != "none"){
                $filters['category'] = $_POST['category'];
            }
            if(isset($_POST['status']) && $_POST['status'] != "none"){
                $filters['status'] = $_POST['status'];
            }            
            $this->session->set_userdata('modules_filters', $filters);
            redirect($redirect);
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata('modules_filters');
            redirect($redirect);
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata('modules_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata('modules_order', $_POST['order_by']);
            redirect($redirect);
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata('modules_page_results', $_POST['page_results']);
            redirect($redirect);
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata('modules_filters');        
        $order_by = $this->session->userdata('modules_order');
        $limit    = $this->session->userdata('modules_page_results');
                
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
        
        // get modules
        $data              = $filters;
        $data['order']     = trim(str_replace('`', '', $order_by));
        $data['limit']     = $limit;
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($this->Module->getModules($filters))/$limit);
        $data["modules"]  = $this->Module->getModules($filters, $order_by, $limit_str);
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('modules/list', $data, true);
        $this->load->view('layouts/'.$this->layout , $content);
        
    }
	
    public function add()
    {   
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');

        $content["content"] = $this->load->view('modules/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data = $this->Module->getDetails($this->module_id);
        $data = @array_merge($data, $this->Custom_field->getFieldsValues($this->module_id));
        $data['params'] = json_decode($data['params'], true);
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        
        //print_r($data);

        $content["content"] = $this->load->view('modules/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
    public function types()      
    {
    	
    	  $script = "$('a.type').live('click', function(event){
                       
                       event.preventDefault();

    	               parent.$('input[name=type]').val($(this).attr('href'));
                       parent.$('form').submit();
                       
                   });";
                   
        $this->jquery_ext->add_script($script, 'general');
    	  
    	$modules = $this->modules;
    	  
        $content["content"] = $this->load->view('modules/types', compact('modules'), true);		
        $this->load->view('layouts/simple_ajax', $content);
        
    }
    
}