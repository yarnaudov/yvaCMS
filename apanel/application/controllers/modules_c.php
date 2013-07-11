<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules_c extends MY_Controller {
    
    public  $trl;
    public  $extension = 'modules';
    public  $page;
    public  $layout;
    public  $positions;
    
    public  $templates;
    
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
        
        $this->templates = parent::_getTemplates('modules');
        
    }
    
    public function _remap($method)
    {
        
        if ($method == 'add' || $method == 'edit')
        {

            $this->load->model('Article');
            $this->load->model('Menu');
            
            $this->jquery_ext->add_plugin("validation");
            $this->jquery_ext->add_library("check_actions_add_edit.js");

            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){   
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
            
                if ($this->form_validation->run() == TRUE){
                    
                    $module_id = $this->Module->$method($this->module_id);

                    // check if there is module model and call save method
                    $module_type = $this->input->post('type');                
                    if(file_exists(FCPATH.'modules/'.$module_type.'/models/'.$module_type.'.php')){
                        $this->load->model('../../modules/'.$module_type.'/models/'.$module_type);
                        $this->$module_type->save($module_id);
                    }
                                                            
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
        
        $data['positions']     = $this->positions;
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('extension_key' => set_value('position', key($data['positions'])),
                                                                            'status'        => 'yes'));

        $content["content"] = $this->load->view('modules/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
                
        $data                  = $this->Module->getDetails($this->module_id);
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('extension_key' => set_value('position', isset($data['position']) ? $data['position'] : ""), 
                                                                            'status'        => 'yes'));
        $data['positions']     = $this->positions;
        
        $content["content"] = $this->load->view('modules/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
    public function types()      
    {
    	
    	  $script = "$('a.type').live('click', function(event){
                       
                       event.preventDefault();

    	               parent.$('input.type').val($(this).attr('href'));
                       
                       $.get('".site_url('home/ajax/load_module_type')."?type='+$(this).attr('href'), function(data){
                           
                           if(data.search('script') != -1){
                                parent.$('.required').each(function(){
                                    $(this).removeClass('required');
                                });
                                parent.$('form').submit();
                                return;
                           }

                           parent.$('#type_label').html($(data, '#type_label').html());
                           parent.$('#module_options').css('display', 'none');                           
                           parent.$('#module_options').html(data);
                           parent.$('#module_options').find('#type_label').remove();
                           parent.$('#module_options').toggle('slow');
                           parent.$( '#jquery_ui' ).dialog( 'close' );
                       });
                       
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