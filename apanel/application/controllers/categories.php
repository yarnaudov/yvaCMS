<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends MY_Controller {

    public  $trl;
    public  $extension;
    public  $page;
    private $category_id;    
    
    function __construct()
    {
  	
        parent::__construct();
        
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
        
    }
    
    public function _remap($method)
    {
        
        if ($method == 'add' || $method == 'edit')
        {
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
        
        $page = "";
        
        // delete articles
        if(isset($_POST['delete'])){
            $result = $this->Category->delete();
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('categories/'.$this->extension.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->Category->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('categories/'.$this->extension.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->Category->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect('categories/'.$this->extension);
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
            $this->session->set_userdata($this->extension.'categories_filters', $filters);
            redirect('categories/'.$this->extension);
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata($this->extension.'categories_filters');
            redirect('categories/'.$this->extension);
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata($this->extension.'categories_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata($this->extension.'categories_order', $_POST['order_by']);
            redirect('categories/'.$this->extension);
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata($this->extension.'categories_page_results', $_POST['page_results']);
            redirect('categories/'.$this->extension);
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata($this->extension.'categories_filters');        
        $order_by = $this->session->userdata($this->extension.'categories_order');
        $limit    = $this->session->userdata($this->extension.'categories_page_results');
        
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
                
        // get categories
        $data = $filters;
        $data['order'] = trim(str_replace('`', '', $order_by));
        $data['limit']     = $limit;
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($this->Category->getCategories($filters))/$limit);
        $data["categories"] = $this->Category->getCategories($filters, $order_by, $limit_str);
        
        // create sub actions menu
        $parent_id = $this->Ap_menu->getDetails($this->current_menu, 'parent_id');
        $data['sub_menu'] = $this->Ap_menu->getSubActions($parent_id);
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
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