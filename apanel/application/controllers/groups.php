<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends MY_Controller {
    
    public  $extension;
    public  $page;
    private $group_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('User_group');
                
        $this->page     = isset($_GET['page']) ? $_GET['page'] : 1;                
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
        
        /*
         *  parent index method handels: 
         *  delete, change status, change order, set order by, set filters, 
         *  clear filter, set limit, get sub menus, set class on sorted element
         */
        $data = parent::index($this->User_group, 'groups', 'groups/users');
        
        // get groups
        $groups = $this->User_group->getGroups($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $groups[0] = $groups;
        }
        else{
          $groups = array_chunk($groups, $data['limit']);
          $data['max_pages'] = count($groups);
        }

        $data['groups'] = count($groups) == 0 ? array() : $groups[($this->page-1)];  
        
        // create sub actions menu
        $parent_id = $this->Ap_menu->getDetails($this->current_menu, 'parent_id');
        $data['sub_menu'] = $this->Ap_menu->getSubActions($parent_id);
        
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