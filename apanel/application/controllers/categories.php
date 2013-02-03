<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends MY_Controller {

    public  $trl;
    public  $extension;
    public  $page;
    private $category_id;    
    
    function __construct()
    {
  	
        parent::__construct();
        
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
        
    }
    
    public function _remap($method)
    {
        
        if ($method == 'add' || $method == 'edit')
        {
            
            $this->jquery_ext->add_plugin("validation");
            $this->jquery_ext->add_library("check_actions_add_edit.js");
            
            if ($method == 'add'){
                
                $this->extension = $this->uri->segment(3);
                
                $script = "$('select[name=translation]').attr('disabled', true);";
                
            }
            elseif($method == 'edit'){
                
                $this->extension   = $this->uri->segment(4);
                $this->category_id = $this->uri->segment(3);
                
                $script = "$('select[name=translation]').bind('change', function(){
                               $('form').append('<input type=\"hidden\" name=\"uset_posts\" value=\"true\" >');
                               $('form').submit();
                           });";
                
            }
            
            $this->jquery_ext->add_script($script);     
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){  
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
                
                if ($this->form_validation->run() == TRUE){
                    
                    $category_id = $this->Category->$method($this->category_id);
                    
                    if(isset($_POST['save'])){
                        redirect('categories/'.$this->extension);
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        /*
                         * save translation in cookie and use it to restore the correct translation
                         */
                        if(isset($_POST['translation'])){
                            $this->session->set_userdata('trl', $_POST['translation']);
                        }
                        redirect('categories/edit/'.$category_id.'/'.$this->extension);
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
        $data = parent::index($this->Category, 'categories', 'categories/'.$this->extension);
        
        // get categories
        $categories = $this->Category->getCategories($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $categories[0] = $categories;
        }
        else{
          $categories = array_chunk($categories, $data['limit']);
          $data['max_pages'] = count($categories);
        }
        
        $data['categories'] = count($categories) == 0 ? array() : $categories[($this->page-1)]; 
                
        // create sub actions menu
        $parent_id = $this->Ap_menu->getDetails($this->current_menu, 'parent_id');
        $data['sub_menu'] = $this->Ap_menu->getSubActions($parent_id);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('categories/list', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {   
        $content["content"] = $this->load->view('categories/add', '', true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data = $this->Category->getDetails($this->category_id);
        
        //print_r($data);

        $content["content"] = $this->load->view('categories/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */