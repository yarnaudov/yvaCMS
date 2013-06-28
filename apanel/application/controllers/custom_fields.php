<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom_fields extends MY_Controller {
    
    /*
     * sets article translation 
     */
    public  $trl;
    public  $page;
    public  $extension;
    private $custom_field_id;
    
    private $sub_menu;
    
    function __construct()
    {
  	
        parent::__construct();
        
        $this->load->model('Custom_field');
        $this->load->model('Module');
        $this->load->model('Banner');
        $this->load->model('User_group');
        
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
        
        // create sub actions menu
        $parent_id = $this->Ap_menu->getDetails($this->current_menu, 'parent_id');
        $this->sub_menu = $this->Ap_menu->getSubActions($parent_id);
        
    }
    
    public function _remap($method)
    {
        
        if ($method == 'add' || $method == 'edit')
        {
            
            $this->jquery_ext->add_plugin("validation");
            $this->jquery_ext->add_library("check_actions_add_edit.js");
	    $this->jquery_ext->add_library("custom_fields.js");
            
            if ($method == 'add'){
                
                $this->extension = $this->uri->segment(3);
                
            }
            elseif($method == 'edit'){
                
                $this->extension       = $this->uri->segment(4);
                $this->custom_field_id = $this->uri->segment(3);
                                
            }
                
            $script = "$('select[name=type]').change(function(){
                           $.get('".site_url('home/ajax/load')."?view=custom_fields/'+$(this).val(), function(data){
			       
			       if(data.search('script') != -1){
                                   parent.$('.required').each(function(){
                                       $(this).removeClass('required');
                                   });
                                   parent.$('form').submit();
                                   return;
                               }
			    
                               $('#params').css('display', 'none');
                               $('#params').html(data);
                               $('#params').toggle('slow');
                           });
                       });";
            $this->jquery_ext->add_script($script);
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

            $this->load->helper('form');
            $this->load->library('form_validation');
            
            if(isset($_POST['save']) || isset($_POST['apply'])){   
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
            
                if ($this->form_validation->run() == TRUE){
                    
                    $custom_field_id = $this->Custom_field->$method($this->custom_field_id);
                    
                    if(isset($_POST['save'])){
                        redirect('custom_fields/'.$this->extension);
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        redirect('custom_fields/edit/'.$custom_field_id.'/'.$this->extension);
                        exit();
                    }
                    
                }
            }
            
        }
        elseif(!method_exists($this, $method)){
            $this->extension = $method;
            $method = 'index';
        }
        
        $this->jquery_ext->add_library("select_active_menu.js");
        $this->$method();

    }
    
    public function index()
    {
        
        /*
         *  parent index method handels: 
         *  delete, change status, change order, set order by, set filters, 
         *  clear filter, set limit, get sub menus, set class on sorted element
         */
        $data = parent::index($this->Custom_field, 'custom_fields', 'custom_fields/'.$this->extension);
        
        // get custom_fields
        $custom_fields = $this->Custom_field->getCustomFields($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $custom_fields[0] = $custom_fields;
        }
        else{
          $custom_fields = array_chunk($custom_fields, $data['limit']);
          $data['max_pages'] = count($custom_fields);
        }

        $data['custom_fields']   = count($custom_fields) == 0 ? array() : $custom_fields[($this->page-1)];   
        
        // create sub actions menu
        $data['sub_menu'] = $this->sub_menu;
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('custom_fields/list', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {   
        
        $data = array();
        
        if(in_array('categories/'.$this->extension, $this->sub_menu)){
            $data['extension_keys_label'] = 'category';
            $data['extension_keys_arr']   = $this->Category->getForDropdown();
        }
        elseif($this->extension == 'modules'){
            $data['extension_keys_label'] = 'position';
            $data['extension_keys_arr']   = $this->Module->getPositions(parent::_parseTemplateFile('modules'));
        }
        elseif($this->extension == 'banners'){
            $data['extension_keys_label'] = 'position';
            $data['extension_keys_arr']   = $this->Banner->getPositions(parent::_parseTemplateFile('banners'));
        }
        elseif($this->extension == 'users'){
            $data['extension_keys_label'] = 'group';
            $data['extension_keys_arr']   = $this->User_group->getForDropdown();
        }
         
        $content["content"] = $this->load->view('custom_fields/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data = $this->Custom_field->getDetails($this->custom_field_id);
        
        if(in_array('categories/'.$this->extension, $this->sub_menu)){
            $data['extension_keys_label'] = 'category';
            $data['extension_keys_arr']   = $this->Category->getForDropdown();
        }
        elseif($this->extension == 'modules'){
            $data['extension_keys_label'] = 'position';
            $data['extension_keys_arr']   = $this->Module->getPositions(parent::_parseTemplateFile('modules'));
        }
        elseif($this->extension == 'banners'){
            $data['extension_keys_label'] = 'position';
            $data['extension_keys_arr']   = $this->Banner->getPositions(parent::_parseTemplateFile('banners'));
        }
        elseif($this->extension == 'users'){
            $data['extension_keys_label'] = 'group';
            $data['extension_keys_arr']   = $this->User_group->getForDropdown();
        }

        $content["content"] = $this->load->view('custom_fields/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */