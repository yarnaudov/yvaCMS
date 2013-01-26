<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Languages extends MY_Controller {
    
    public  $extension = 'languages';
    public  $page;
    private $language_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Language');
                
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
                
        $this->language_id = $this->uri->segment(3);
        
    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit'){
            
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

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
            
        $page = "";
        
        // delete Languages
        if(isset($_POST['delete'])){
            $result = $this->Language->delete();
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('languages'.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->Language->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('languages'.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->Language->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect('languages');
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
            $this->session->set_userdata('languages_filters', $filters);
            redirect('languages');
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata('languages_filters');
            redirect('languages');
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata('languages_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata('languages_order', $_POST['order_by']);
            redirect('languages');
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata('languages_page_results', $_POST['page_results']);
            redirect('languages');
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata('languages_filters');        
        $order_by = $this->session->userdata('languages_order');
        $limit    = $this->session->userdata('languages_page_results');
                
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
        
        // get Languages
        $data              = $filters;
        $data['order']     = trim(str_replace('`', '', $order_by));
        $data['limit']     = $limit;
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($this->Language->getLanguages($filters))/$limit);
        $data["languages"]  = $this->Language->getLanguages($filters, $order_by, $limit_str);
        
        // create sub actions menu
        $data['sub_menu'] = $this->Ap_menu->getSubActions($this->current_menu);
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('languages/list', $data, true);
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {   
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');

        $content["content"] = $this->load->view('languages/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data = $this->Language->getDetails($this->language_id);
        $data = @array_merge($data, $this->Custom_field->getFieldsValues($this->language_id));        
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        
        //print_r($data);

        $content["content"] = $this->load->view('languages/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
}