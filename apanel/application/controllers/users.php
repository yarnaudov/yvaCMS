<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller {
    
    public $extension = 'users';
    public $page;
    public $user_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('User');
                
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
                
        $this->user_id = $this->uri->segment(3);
        
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
            $this->jquery_ext->add_script($script);
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){   
                
                $this->form_validation->set_rules('name', lang('label_name'), 'required');                
                
                if ($method == 'add'){
                    $this->form_validation->set_rules('user', lang('label_user'), 'required|is_unique[users.user]');
                    $this->form_validation->set_rules('password', lang('label_password'), 'required'); 
                    $this->form_validation->set_rules('password2', lang('label_confirm').' '.lang('label_password2'), 'required'); 
                }
                elseif($method == 'edit'){
                    $this->form_validation->set_rules('user', lang('label_user'), 'required|is_unique_edit[users.user.user_id.'.$this->user_id.']');                    
                }
            
                if ($this->form_validation->run() == TRUE){
                    
                    $passwords_match = true;
                    if(!empty($_POST['password']) || !empty($_POST['password2'])){                        
                        if($_POST['password'] != $_POST['password2']){
                            $this->session->set_userdata('error_msg', lang('msg_user_password_error'));
                            $passwords_match = false;
                        }                        
                    }                                      
                    
                    if($passwords_match == true){
                        
                        $_POST['pass'] = $_POST['password'];
                            
                        $user_id = $this->User->$method($this->user_id);                        
                        
                        if(isset($_POST['save'])){
                            redirect('users/');
                            exit();
                        }
                        elseif(isset($_POST['apply'])){
                            /*
                             * save translation in cookie and use it to restore the correct translation
                             */
                            if(isset($_POST['translation'])){
                                $this->session->set_userdata('trl', $_POST['translation']);
                            }
                            redirect('users/edit/'.$user_id);
                            exit();
                        }
                    }
                            
                }
            }
            
        }        
        
        $this->$method();

    }
    
    public function index()
    {
        
        $page = "";
        
        $this->load->model('User_group');
        
        // delete users
        if(isset($_POST['delete'])){
            $result = $this->User->delete();
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('users'.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->User->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('users'.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->User->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect('users');
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
            $this->session->set_userdata('users_filters', $filters);
            redirect('users');
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata('users_filters');
            redirect('users');
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata('users_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata('users_order', $_POST['order_by']);
            redirect('users');
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata('users_page_results', $_POST['page_results']);
            redirect('users');
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata('users_filters');        
        $order_by = $this->session->userdata('users_order');
        $limit    = $this->session->userdata('users_page_results');
                
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
        
        // get users
        $data              = $filters;
        $data['order']     = trim(str_replace('`', '', $order_by));
        $data['limit']     = $limit;
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($this->User->getUsers($filters))/$limit);
        $data["users"]  = $this->User->getUsers($filters, $order_by, $limit_str);
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('users/list', $data, true);
        $this->load->view('layouts/default' , $content);
        
    }
	
    public function add()
    {   
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');

        $content["content"] = $this->load->view('users/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data = $this->User->getDetails($this->user_id);
        $data = @array_merge($data, $this->Custom_field->getFieldsValues($this->user_id));        
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        
        //print_r($data);

        $content["content"] = $this->load->view('users/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
}