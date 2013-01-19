<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pulls extends MY_Controller {

    public  $page;
    private $pull_id;
    
    function __construct()
    {
        
        parent::__construct();
        
        $this->load->config('config');
        
        $this->load->model('Pull');
        
        $this->load->language('com_pulls_labels');
        $this->load->language('com_pulls_msg');
                
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
                
        $this->pull_id = $this->uri->segment(4);
                    
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
            $this->jquery_ext->add_library("../components/pulls/js/pulls.js");
            $this->jquery_ext->add_css("../components/pulls/css/pulls.css");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
            
                if ($this->form_validation->run() == TRUE){
                    
                    $pull_id = $this->Pull->$method($this->pull_id);
                                                            
                    if(isset($_POST['save'])){
                        redirect('components/pulls');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        redirect('components/pulls/edit/'.$pull_id);
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
        
        // delete pulls
        if(isset($_POST['delete'])){
            $result = $this->Pull->delete();
            if($result == true){
                redirect('components/pulls'.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->Pull->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                redirect('components/pulls'.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->Pull->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect('components/pulls'.$page);
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
            $this->session->set_userdata('pulls_filters', $filters);
            redirect('components/pulls');
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata('pulls_filters');
            redirect('components/pulls');
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata('pulls_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata('pulls_order', $_POST['order_by']);
            redirect('components/pulls');
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata('pulls_page_results', $_POST['page_results']);
            redirect('components/pulls');
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata('pulls_filters');        
        $order_by = $this->session->userdata('pulls_order');
        $limit    = $this->session->userdata('pulls_page_results');
                
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
        
        // get pulls
        $data              = $filters;
        $data['order']     = trim(str_replace('`', '', $order_by));
        $data['limit']     = $limit;
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($this->Pull->getPulls($filters))/$limit);
        $data["pulls"]  = $this->Pull->getPulls($filters, $order_by, $limit_str);
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");
                
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
        $data = $this->Pull->getDetails($this->pull_id);
        
        $answers = $this->Pull->getAnswers($this->pull_id);
        
        $data['votes'] = 0;
        foreach($answers as $answer){
            $data['votes'] += $answer['votes'];
        }
        
        $content["content"] = $this->load->view('add', $data, true);
        $this->load->view('layouts/default' , $content);
        
    }
    
}


