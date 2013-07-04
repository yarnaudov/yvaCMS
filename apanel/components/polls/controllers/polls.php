<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Polls extends MY_Controller {

    public  $page;
    private $poll_id;
    
    function __construct()
    {
        
        parent::__construct();
        
        $this->load->config('config');
        
        $this->load->model('Poll');
        
        parent::_loadComponetLanguages('polls');
                
        $this->page    = isset($_GET['page']) ? $_GET['page'] : 1;                
        $this->poll_id = $this->uri->segment(4);
                    
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
            $this->jquery_ext->add_library("../components/polls/js/polls.js");
            $this->jquery_ext->add_css("../components/polls/css/polls.css");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
            
                if ($this->form_validation->run() == TRUE){
                    
                    $poll_id = $this->Poll->$method($this->poll_id);
                                                            
                    if(isset($_POST['save'])){
                        redirect('components/polls');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        redirect('components/polls/edit/'.$poll_id);
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
        $data = parent::index($this->Poll, 'polls', 'components/polls');
        
        // get polls
        $polls = $this->Poll->getPolls($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $polls[0] = $polls;
        }
        else{
          $polls = array_chunk($polls, $data['limit']);
          $data['max_pages'] = count($polls);
        }
        $data['polls']   = count($polls) == 0 ? array() : $polls[($this->page-1)];
        
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
        $data = $this->Poll->getDetails($this->poll_id);
        
        $answers = $this->Poll->getAnswers($this->poll_id);
        
        $data['votes'] = 0;
        foreach($answers as $answer){
            $data['votes'] += $answer['votes'];
        }
        
        $content["content"] = $this->load->view('add', $data, true);
        $this->load->view('layouts/default' , $content);
        
    }
    
}


