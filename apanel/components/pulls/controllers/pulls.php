<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pulls extends MY_Controller {

    public  $page;
    private $pull_id;
    
    function __construct()
    {
        
        parent::__construct();
        
        $this->load->config('config');
        
        $this->load->model('Pull');
        
        parent::_loadComponetLanguages('pulls');
                
        $this->page    = isset($_GET['page']) ? $_GET['page'] : 1;                
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
        
        /*
         *  parent index method handels: 
         *  delete, change status, change order, set order by, set filters, 
         *  clear filter, set limit, get sub menus, set class on sorted element
         */
        $data = parent::index($this->Pull, 'pulls', 'components/pulls');
        
        // get pulls
        $pulls = $this->Pull->getPulls($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $pulls[0] = $pulls;
        }
        else{
          $pulls = array_chunk($pulls, $data['limit']);
          $data['max_pages'] = count($pulls);
        }
        $data['pulls']   = count($pulls) == 0 ? array() : $pulls[($this->page-1)];
        
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


