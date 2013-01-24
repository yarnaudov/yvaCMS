<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banners extends MY_Controller {
    
    public  $extension = 'banners';
    public  $page;
    public  $positions;
    
    private $banner_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Banner');
                
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
                
        $this->banner_id = $this->uri->segment(3);
        
        /*
         * get positions
         */        
        $this->positions = $this->Banner->getPositions(parent::_parseTemplateFile('banners'));

    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit')
        {
            
            $this->load->model('Menu');
            $this->jquery_ext->add_library("check_actions_add_edit.js");
            
            $this->jquery_ext->add_plugin("codemirror");
            
            $script = "try{
                         var editor = CodeMirror.fromTextArea(document.getElementById('code'), {mode: 'text/html', tabMode: 'indent', lineNumbers: true});
                       }
                       catch(err){}
                       
                       $('select[name=position]').bind('change', function(){
                           if($(this).val() == 'value'){
                             $(this).css('display', 'none');
                             $(this).attr('disabled', true);
                             $('input[name=position]').css('display', 'inline');
                             $('input[name=position]').attr('disabled', false);
                             $('input[name=position]').focus();
                           }
                       });
                       $('input[name=position]').blur(function(){
                           if($(this).val() == ''){
                             $(this).css('display', 'none');
                             $(this).attr('disabled', true);
                             $('select[name=position]').css('display', 'inline');
                             $('select[name=position]').attr('disabled', false);
                             $('select[name=position]').val('');
                           }
                       });";
            
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
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
                            
                if ($this->form_validation->run() == TRUE){
                    
                    $banner_id = $this->Banner->$method($this->banner_id);
                                                            
                    if(isset($_POST['save'])){
                        redirect('banners');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){                        
                        redirect('banners/edit/'.$banner_id);
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
        
        // delete banners
        if(isset($_POST['delete'])){
            $result = $this->Banner->delete();
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('banners'.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->Banner->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('banners'.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->Banner->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect('banners');
                exit();
            }
        }
        
        // set filters
        if(isset($_POST['search'])){
            $filters = array();
            if(isset($_POST['search_v']) && !empty($_POST['search_v'])){
                $filters['search_v'] = $_POST['search_v'];
            }
            if(isset($_POST['position']) && $_POST['position'] != "none"){
                $filters['position'] = $_POST['position'];
            }
            if(isset($_POST['status']) && $_POST['status'] != "none"){
                $filters['status'] = $_POST['status'];
            }            
            $this->session->set_userdata('banners_filters', $filters);
            redirect('banners');
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata('banners_filters');
            redirect('banners');
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata('banners_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata('banners_order', $_POST['order_by']);
            redirect('banners');
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata('banners_page_results', $_POST['page_results']);
            redirect('banners');
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata('banners_filters');        
        $order_by = $this->session->userdata('banners_order');
        $limit    = $this->session->userdata('banners_page_results');
                
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
        
        // get banners
        $data              = $filters;
        $data['order']     = trim(str_replace('`', '', $order_by));
        $data['limit']     = $limit;
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($this->Banner->getbanners($filters))/$limit);
        $data["banners"]   = $this->Banner->getbanners($filters, $order_by, $limit_str);
        $data['positions'] = $this->positions;
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('banners/list', $data, true);
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {                 
        
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        $data['positions']     = $this->positions;

        $content["content"] = $this->load->view('banners/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data                  = $this->Banner->getDetails($this->banner_id);
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        $data['positions']     = $this->positions;
        
        $content["content"] = $this->load->view('banners/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
    public function types()      
    {
    	
    	$script = "$('a.type').live('click', function(event){
                       
                       event.preventDefault();

    	               parent.$('input.type').val($(this).attr('href'));
                       parent.$('form').submit();
                       
                   });";
                   
        $this->jquery_ext->add_script($script, 'general');
    	  
    	$banner_types = $this->config->item('banner_types');
    	  
        $content["content"] = $this->load->view('banners/types', compact('banner_types'), true);		
        $this->load->view('layouts/simple_ajax', $content);
        
    }
    
}