<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Languages extends MY_Controller {
    
    public  $extension = 'languages';
    public  $page;
    private $language_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Language');
                
        $this->page        = isset($_GET['page']) ? $_GET['page'] : 1;                
        $this->language_id = $this->uri->segment(3);
        
    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit'){
            
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");
            $this->jquery_ext->add_library("check_actions_add_edit.js");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){   
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
                
                if ($method == 'add'){
                    $this->form_validation->set_rules('abbreviation', lang('label_abbreviation'), 'required|is_unique[languages.abbreviation]');
                }
                elseif($method == 'edit'){
                    $this->form_validation->set_rules('abbreviation', lang('label_abbreviation'), 'required|is_unique_edit[languages.abbreviation.id.'.$this->language_id.']');
                }
            
                if ($this->form_validation->run() == TRUE){
                    
                    $language_id = $this->Language->$method($this->language_id);
                                                            
                    if(isset($_POST['save'])){
                        redirect('languages');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){                   
                        redirect('languages/edit/'.$language_id);
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
        $data = parent::index($this->Language, 'languages', 'languages');
        
        // get Languages
        $languages = $this->Language->getLanguages($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $languages[0] = $languages;
        }
        else{
          $languages = array_chunk($languages, $data['limit']);
          $data['max_pages'] = count($languages);
        }

        $data['languages'] = count($languages) == 0 ? array() : $languages[($this->page-1)];
        
        // create sub actions menu
        $data['sub_menu'] = $this->Ap_menu->getSubActions($this->current_menu);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('languages/list', $data, true);
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {   
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'));

        $content["content"] = $this->load->view('languages/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data = $this->Language->getDetails($this->language_id);
        $data = @array_merge($data, $this->Custom_field->getFieldsValues($this->language_id));        
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'));
        
        //print_r($data);

        $content["content"] = $this->load->view('languages/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
}