<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules_c extends MY_Controller {
    
    public  $trl;
    public  $extension = 'modules';
    public  $page;
    public  $layout;
    public  $positions;
    
    private $module_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Module');        
        
        $this->page      = isset($_GET['page']) ? $_GET['page'] : 1;                
        $this->module_id = $this->uri->segment(3);
        
        /*
         * load modules languages
         */
        foreach($this->modules as $module){
           $this->_loadModuleLanguage($module);                                                                   
        }
        
        /*
         * get positions
         */        
        $this->positions = $this->Module->getPositions(parent::_parseTemplateFile('modules'));
        
    }
    
    public function _remap($method)
    {
        
        if ($method == 'add' || $method == 'edit')
        {
            
            $this->load->model('Article');
            $this->load->model('Menu');
            
            $this->jquery_ext->add_library("check_actions_add_edit.js");
            $this->jquery_ext->add_plugin("codemirror");
            
            $script = "try{
                         var editor = CodeMirror.fromTextArea(document.getElementById('code'), {mode: 'text/html', tabMode: 'indent', lineNumbers: true});
                       }
                       catch(err){}
                       
                       $('select[name=position]').bind('change', function(){
                           if($(this).val() == 'value'){
                             $(this).css('display', 'none');
                             $(this).attr('disabled', true);
                             $('input[name=position]').css('display', 'inline');
                             $('input[name=position]').attr('disabled', false);
                             $('input[name=position]').focus();
                           }
                       });
                       $('input[name=position]').blur(function(){
                           if($(this).val() == ''){
                             $(this).css('display', 'none');
                             $(this).attr('disabled', true);
                             $('select[name=position]').css('display', 'inline');
                             $('select[name=position]').attr('disabled', false);
                             $('select[name=position]').val('');
                           }
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
        
        /*
         *  parent index method handels: 
         *  delete, change status, change order, set order by, set filters, 
         *  clear filter, set limit, get sub menus, set class on sorted element
         */
        $data = parent::index($this->Module, 'modules', 'modules');

        // get modules
        $modules = $this->Module->getModules($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $modules[0] = $modules;
        }
        else{
          $modules = array_chunk($modules, $data['limit']);
          $data['max_pages'] = count($modules);
        }

        $data['modules']   = count($modules) == 0 ? array() : $modules[($this->page-1)];  
        $data['positions'] = $this->positions;
        
        // create sub actions menu
        $data['sub_menu'] = $this->Ap_menu->getSubActions($this->current_menu);
                
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('modules/list', $data, true);
        $this->load->view('layouts/default' , $content);
        
    }
	
    public function add()
    {   
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        $data['positions']     = $this->positions;

        $content["content"] = $this->load->view('modules/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
                
        $data                  = $this->Module->getDetails($this->module_id);
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        $data['positions']     = $this->positions;
        
        $content["content"] = $this->load->view('modules/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
    public function types()      
    {
    	
    	  $script = "$('a.type').live('click', function(event){
                       
                       event.preventDefault();

    	               parent.$('input.type').val($(this).attr('href'));
                       parent.$('form').submit();
                       
                   });";
                   
        $this->jquery_ext->add_script($script, 'general');
    	  
    	$modules = $this->modules;
    	  
        $content["content"] = $this->load->view('modules/types', compact('modules'), true);		
        $this->load->view('layouts/simple_ajax', $content);
        
    }
    
    public function article_list()
    {
        $this->load->model('Module');
        $data["modules"]  = $this->Module->getModules();
        
        $this->jquery_ext->add_library('scroll_into_view.js');
        
        /*
         * load modules languages
         */
        foreach($this->modules as $module){
           $this->_loadModuleLanguage($module);                                                                   
        }
        
        $content["content"] = $this->load->view('modules/simple_list', $data, true);
        $this->load->view('layouts/simple', $content);
        
    }
    
}