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
        
        /*
         * get current translation
         */
        $this->trl = $this->session->userdata('trl') == "" ? $this->Language->getDefault() : $this->session->userdata('trl');
        $this->session->unset_userdata('trl');
        if(isset($_POST['translation'])){         
            $this->trl = $_POST['translation'];
            if(isset($_POST['uset_posts'])){                
                $this->input->post = array();
                $_POST = array();
            }
        }
        
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
                
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
        
        $page = "";
        if($this->page > 1){
            $page = "?page=".$this->page;
        }
        
        // delete contact_forms
        if(isset($_POST['delete'])){
            $result = $this->Contact_form->delete();
            if($result == true){
                redirect('components/contact_forms'.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->Contact_form->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                redirect('components/contact_forms'.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->Contact_form->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect('components/contact_forms'.$page);
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
            $this->session->set_userdata('contact_forms_filters', $filters);
            redirect('components/contact_forms');
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata('contact_forms_filters');
            redirect('components/contact_forms');
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata('contact_forms_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata('contact_forms_order', $_POST['order_by']);
            redirect('components/contact_forms');
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata('contact_forms_page_results', $_POST['page_results']);
            redirect('components/contact_forms');
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata('contact_forms_filters');        
        $order_by = $this->session->userdata('contact_forms_order');
        $limit    = $this->session->userdata('contact_forms_page_results');
                
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
        
        // get contact_forms
        $data              = $filters;
        $data['order']     = trim(str_replace('`', '', $order_by));
        $data['limit']     = $limit;
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($this->Contact_form->getContactForms($filters))/$limit);
        $data["contact_forms"]  = $this->Contact_form->getContactForms($filters, $order_by, $limit_str);
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
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


