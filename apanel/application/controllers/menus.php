<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus extends MY_Controller {

    public  $trl;
    public  $extension = 'menus';
    public  $page;
    private $menu_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Menu');
        
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
        
        $this->menu_id = $this->uri->segment(3);
        
    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit')
        {   
            
            $this->load->model('Article');
            $this->load->helper('parceXMLfile');
            $this->jquery_ext->add_library("check_actions_add_edit.js");  
                                 
            $script = "$('select[name=category]').bind('change', function(){
                           $('form').submit();
                       });
                        
                        $('select[name=type]').bind('change', function(){
                            $('form').submit();
                        });
                        
                        $('select.component').bind('change', function(){
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
            
            $this->jquery_ext->add_script($script);
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

            $this->load->helper('form');
            $this->load->library('form_validation');          
            
            if(isset($_POST['save']) || isset($_POST['apply'])){   
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
                
                if($method == 'add'){
                    $this->form_validation->set_rules('alias', lang('label_alias'), 'required|is_unique[menus.alias]');
                }
                elseif($method == 'edit'){
                    $this->form_validation->set_rules('alias', lang('label_alias'), 'required|is_unique_edit[menus.alias.id.'.$this->menu_id.']');
                }
                
                if ($this->form_validation->run() == TRUE){
                    
                    $menu_id = $this->Menu->$method($this->menu_id);
                    
                    if(isset($_POST['save'])){
                        redirect('menus/');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        /*
                         * save translation in cookie and use it to restore the correct translation
                         */
                        if(isset($_POST['translation'])){
                            $this->session->set_userdata('trl', $_POST['translation']);
                        }
                        redirect('menus/edit/'.$menu_id);
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
        
        // delete
        if(isset($_POST['delete'])){
            $result = $this->Menu->delete();
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('menus'.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->Menu->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('menus'.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->Menu->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect('menus');
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
            $this->session->set_userdata('menus_filters', $filters);
            redirect('menus');
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata('menus_filters');
            redirect('menus');
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata('menus_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata('menus_order', $_POST['order_by']);
            redirect('menus');
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata('menus_page_results', $_POST['page_results']);
            redirect('menus');
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata('menus_filters');        
        $order_by = $this->session->userdata('menus_order');
        $limit    = $this->session->userdata('menus_page_results');
        
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
               
                
        // get menus
        $data = $filters;
        $data['order']      = trim(str_replace('`', '', $order_by));
        $data['limit']      = $limit;        
        $data["menus"]      = $this->Menu->getMenus($filters, $order_by);
        
        $data['categories'] = $this->Category->getForDropdown();
        
        /*
         * special way to set limit for menus 
         */
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($data["menus"])/$limit);        
        if($limit != 'all'){
            foreach($data["menus"] as $key => $menu){
                if($key < ($this->page-1)*$limit || $key >= ((($this->page-1)*$limit)+$limit)){
                    unset($data["menus"][$key]);
                }
            }
        }
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('menus/list', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {           
        
        $data['categories']    = $this->Category->getForDropdown();
        $data['menus']         = $this->Menu->getForDropdown(array('category_id' => set_value('category', key($data['categories']))));
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        
        $content = $this->load->view('menus/add', $data, true);		
        $this->load->view('layouts/default', compact('content'));
        
    }
	
    public function edit()      
    {
        
        $data                  = $this->Menu->getDetails($this->menu_id);   
        $data['categories']    = $this->Category->getForDropdown();
        $data['menus']         = $this->Menu->getForDropdown(array('category_id' => set_value('category', isset($data['category_id']) ? $data['category_id'] : "")));
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        
        //print_r($data);

        $content["content"] = $this->load->view('menus/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }

}