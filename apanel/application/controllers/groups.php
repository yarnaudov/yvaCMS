<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends MY_Controller {
    
    public  $extension;
    public  $page;
    private $group_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('User_group');
                
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
                
        $this->group_id = $this->uri->segment(3);
        
    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit')
        {
            if ($method == 'add'){
               
                $script = "$('select[name=translation]').attr('disabled', true);";
                
            }
            elseif($method == 'edit'){
                
                $script = "$('select[name=translation]').bind('change', function(){
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
            
            $script .= "$('input[type=checkbox]').click(function(){                            
                            var class_name = $(this).attr('class');
                            var checked = $(this).attr('checked');
                            if(class_name != 'main' && checked == 'checked'){
                                $('#'+class_name).attr('checked', true);
                            }
                        });
                        $('input.main').click(function(){                            
                            if($(this).attr('checked') != 'checked'){
                                $('.'+$(this).attr('id')).removeAttr('checked');
                            }                            
                        });";
            
            $this->jquery_ext->add_script($script);
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){   
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
            
                if ($this->form_validation->run() == TRUE){
                                                       
                    $group_id = $this->User_group->$method($this->group_id);                         

                    if(isset($_POST['save'])){
                        redirect('groups/users');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        redirect('groups/edit/'.$group_id.'/users');
                        exit();
                    }
                            
                }
            }
            
        }        
        
        $this->jquery_ext->add_library("select_active_menu.js");
        $this->$method();

    }
    
    public function index()
    {
        
        $page = "";
                    
        // delete groups
        if(isset($_POST['delete'])){
            $result = $this->User_group->delete();
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('groups/users'.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->User_group->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('groups/users'.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->User_group->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect('groups/users');
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
            $this->session->set_userdata('groups_filters', $filters);
            redirect('groups/users');
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata('groups_filters');
            redirect('groups/users');
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata('groups_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata('groups_order', $_POST['order_by']);
            redirect('groups/users');
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata('groups_page_results', $_POST['page_results']);
            redirect('groups/users');
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata('groups_filters');        
        $order_by = $this->session->userdata('groups_order');
        $limit    = $this->session->userdata('groups_page_results');
                
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
        
        // get groups
        $data              = $filters;
        $data['order']     = trim(str_replace('`', '', $order_by));
        $data['limit']     = $limit;
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($this->User_group->getGroups($filters))/$limit);
        $data["groups"]  = $this->User_group->getGroups($filters, $order_by, $limit_str);
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('groups/list', $data, true);
        $this->load->view('layouts/default' , $content);
        
    }
	
    public function add()
    {   
        $content["content"] = $this->load->view('groups/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data = $this->User_group->getDetails($this->group_id);
        
        //print_r($data);

        $content["content"] = $this->load->view('groups/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
}