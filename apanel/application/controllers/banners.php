<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banners extends MY_Controller {
    
    public  $extension = 'banners';
    public  $model     = 'Banner';
    public  $element_id;
    public  $page;
    public  $positions;
    
    private $banner_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Banner');
                
        $this->page      = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->banner_id = $this->element_id = $this->uri->segment(3);
        
        /*
         * get positions
         */        
        $this->positions = $this->Banner->getPositions(parent::_parseTemplateFile('banners'));

    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit')
        {
            
            $this->load->model('Menu');
            
            $this->jquery_ext->add_plugin("validation");
            $this->jquery_ext->add_library("check_actions_add_edit.js");
            
            $this->jquery_ext->add_plugin("codemirror");
            
            $script = "try{
                         var editor = CodeMirror.fromTextArea(document.getElementById('code'), {mode: 'text/html', tabMode: 'indent', lineNumbers: true});
                       }
                       catch(err){}";
            $this->jquery_ext->add_script($script);
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){   
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
                            
                if ($this->form_validation->run() == TRUE){
                    
                    $banner_id = $this->Banner->$method($this->banner_id);
                                                            
                    if(isset($_POST['save'])){
                        redirect('banners');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){                        
                        redirect('banners/edit/'.$banner_id);
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
        $data = parent::index($this->Banner, 'banners', 'banners');
        
        // get banners
        $banners = $this->Banner->getbanners($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $banners[0] = $banners;
        }
        else{
          $banners = array_chunk($banners, $data['limit']);
          $data['max_pages'] = count($banners);
        }

        $data['banners']   = count($banners) == 0 ? array() : $banners[($this->page-1)]; 
        $data['positions'] = $this->positions;
        
        // create sub actions menu
        $data['sub_menu'] = $this->Ap_menu->getSubActions($this->current_menu);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('banners/list', $data, true);
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {                 
        
        $data['positions']     = $this->positions;
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('extension_key' => set_value('position', key($data['positions'])),
                                                                            'status'        => 'yes'));
        
        $content["content"] = $this->load->view('banners/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data                  = $this->Banner->getDetails($this->banner_id);
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('extension_key' => set_value('position', isset($data['position']) ? $data['position'] : ""), 
                                                                            'status'        => 'yes'));
        $data['positions']     = $this->positions;
        
        $content["content"] = $this->load->view('banners/add', $data, true);		
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
    	  
    	$banner_types = $this->config->item('banner_types');
    	  
        $content["content"] = $this->load->view('banners/types', compact('banner_types'), true);		
        $this->load->view('layouts/simple_ajax', $content);
        
    }
    
}