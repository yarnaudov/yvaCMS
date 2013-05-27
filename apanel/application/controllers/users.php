<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller {
    
    public $extension = 'users';
    public $page;
    public $user_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('User');
                
        $this->page    = isset($_GET['page']) ? $_GET['page'] : 1;                
        $this->user_id = $this->uri->segment(3);
        
    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit')
        {
            
            $this->jquery_ext->add_plugin("validation");
            $this->jquery_ext->add_library("check_actions_add_edit.js"); 
            
            $script = "$('select[name=user_group]').change(function(){

                           $.get('".site_url('home/ajax/load_custom_fields')."?extension=".$this->extension."&extension_key='+$(this).val(), function(data){
                               $('#custom_fields').css('display', 'none');
                               $('#custom_fields').html(data);
                               $('#custom_fields').toggle('slow');
                           });

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
                    $this->form_validation->set_rules('user', lang('label_user'), 'required|is_unique_edit[users.user.id.'.$this->user_id.']');                    
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
        
        /*
         *  parent index method handels: 
         *  delete, change status, change order, set order by, set filters, 
         *  clear filter, set limit, get sub menus, set class on sorted element
         */
        $data = parent::index($this->User, 'users', 'users');
       
        // get users
        $users = $this->User->getUsers($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $users[0] = $users;
        }
        else{
          $users = array_chunk($users, $data['limit']);
          $data['max_pages'] = count($users);
        }

        $data['users']   = count($users) == 0 ? array() : $users[($this->page-1)];
        
        // create sub actions menu
        $data['sub_menu'] = $this->Ap_menu->getSubActions($this->current_menu);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('users/list', $data, true);
        $this->load->view('layouts/default' , $content);
        
    }
	
    public function add()
    {   
        
        $data['user_groups']   = $this->User_group->getForDropdown();
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('extension_key' => set_value('user_group', key($data['user_groups'])), 
                                                                            'status'        => 'yes'));

        $content["content"] = $this->load->view('users/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data = $this->User->getDetails($this->user_id);
        $data['user_groups']   = $this->User_group->getForDropdown();
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('extension_key' => set_value('user_group', isset($data['user_group_id']) ? $data['user_group_id'] : ""), 
                                                                            'status'        => 'yes'));

        $content["content"] = $this->load->view('users/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
}