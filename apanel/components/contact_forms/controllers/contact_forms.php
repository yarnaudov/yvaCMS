<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_forms extends MY_Controller {

    public  $trl;
    public  $page;
    private $contact_form_id;
    
    function __construct()
    {
        
        parent::__construct();
        
        $this->load->config('config');
        
        $this->load->model('Contact_form');
        
        $this->load->language('com_cf_labels');
        $this->load->language('com_cf_msg');
                
        $this->page            = isset($_GET['page']) ? $_GET['page'] : 1;        
        $this->contact_form_id = $this->uri->segment(4);
                    
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
            
            $this->jquery_ext->add_script($script);
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");
            $this->jquery_ext->add_library("../components/contact_forms/js/contact_forms.js");
            $this->jquery_ext->add_css("../components/contact_forms/css/contact_forms.css");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
            
                if ($this->form_validation->run() == TRUE){
                    
                    $contact_form_id = $this->Contact_form->$method($this->contact_form_id);
                                                            
                    if(isset($_POST['save'])){
                        redirect('components/contact_forms');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        /*
                         * save translation in cookie and use it to restore the correct translation
                         */
                        if(isset($_POST['translation'])){
                            $this->session->set_userdata('trl', $_POST['translation']);
                        }
                        redirect('components/contact_forms/edit/'.$contact_form_id);
                        exit();
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
        $data = parent::index($this->Contact_form, 'contact_forms', 'components/contact_forms');
   
        // get contact_forms
        $contact_forms = $this->Contact_form->getContactForms($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $contact_forms[0] = $contact_forms;
        }
        else{
          $contact_forms = array_chunk($contact_forms, $data['limit']);
          $data['max_pages'] = count($contact_forms);
        }

        $data['contact_forms'] = count($contact_forms) == 0 ? array() : $contact_forms[($this->page-1)]; 
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");
        
        //$data['contact_forms'] = $this->Contact_form->getContactsForms();
        
        $content["content"] = $this->load->view('list', $data, true);
        $this->load->view('layouts/default' , $content);
        
    }
    
    public function add()
    {
        
        $content["content"] = $this->load->view('add', '', true);
        $this->load->view('layouts/default' , $content);
        
    }
    
    public function edit()
    {
        $data = $this->Contact_form->getDetails($this->contact_form_id);
        
        $content["content"] = $this->load->view('add', $data, true);
        $this->load->view('layouts/default' , $content);
        
    }
    
}


