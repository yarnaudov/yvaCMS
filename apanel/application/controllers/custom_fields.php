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
        
        $this->trl = Language::getDefault();
        if(isset($_POST['translation'])){         
            $this->trl = $_POST['translation'];
            if(isset($_POST['uset_posts'])){                
                $this->input->post = array();
                $_POST = array();
            }
        }        
        
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
        
        // create sub actions menu
        $parent_id = $this->Ap_menu->getDetails($this->current_menu, 'parent_id');
        $this->sub_menu = $this->Ap_menu->getSubActions($parent_id);
        
    }
    
    public function _remap($method)
    {
        
        if ($method == 'add' || $method == 'edit')
        {
            if ($method == 'add'){
                
                $this->extension = $this->uri->segment(3);
                
            }
            elseif($method == 'edit'){
                
                $this->extension       = $this->uri->segment(4);
                $this->custom_field_id = $this->uri->segment(3);
                                
            }
                
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
        
        $page = "";
        
        // delete articles
        if(isset($_POST['delete'])){
            $result = $this->Custom_field->delete();
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('custom_fields/'.$this->extension.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->Custom_field->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('custom_fields/'.$this->extension.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->Custom_field->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect('custom_fields/'.$this->extension);
                exit();
            }
        }
        
        // set filters
        if(isset($_POST['search'])){
            $filters = array();
            if(isset($_POST['search_v']) && !empty($_POST['search_v'])){
                $filters['search_v'] = $_POST['search_v'];
            }
            if(isset($_POST['status']) && $_POST['status'] != "none"){
                $filters['status'] = $_POST['status'];
            }            
            $this->session->set_userdata($this->extension.'custom_fields_filters', $filters);
            redirect('custom_fields/'.$this->extension);
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata($this->extension.'custom_fields_filters');
            redirect('custom_fields/'.$this->extension);
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata($this->extension.'custom_fields_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata($this->extension.'custom_fields_order', $_POST['order_by']);
            redirect('custom_fields/'.$this->extension);
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata($this->extension.'custom_fields_page_results', $_POST['page_results']);
            redirect('custom_fields/'.$this->extension);
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata($this->extension.'custom_fields_filters');        
        $order_by = $this->session->userdata($this->extension.'custom_fields_order');
        $limit    = $this->session->userdata($this->extension.'custom_fields_page_results');
        
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
                
        // get custom_fields
        $data                  = $filters;
        $data['order']         = trim(str_replace('`', '', $order_by));
        $data['limit']         = $limit;
        $data['max_pages']     = $limit == 'all' ? 0 : ceil(count($this->Custom_field->getCustomFields($filters))/$limit);
        $data['custom_fields'] = $this->Custom_field->getCustomFields($filters, $order_by, $limit_str);
        
        // create sub actions menu
        $data['sub_menu'] = $this->sub_menu;

        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('custom_fields/list', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {   
        
        $data = array();
        
        if(in_array('categories/'.$this->extension, $this->sub_menu)){
            $data['categories'] = $this->Category->getForDropdown();
        }
         
        $content["content"] = $this->load->view('custom_fields/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data = $this->Custom_field->getDetails($this->custom_field_id);
        
        if(in_array('categories/'.$this->extension, $this->sub_menu)){
            $data['categories'] = $this->Category->getForDropdown();
        }

        $content["content"] = $this->load->view('custom_fields/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */