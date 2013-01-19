<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles extends MY_Controller {
    
    public  $trl;
    public  $extension = 'articles';
    public  $page;
    public  $layout;
    private $article_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Article');
        
        /*
         * get current translation
         */
        $this->trl = $this->session->userdata('trl') == "" ? Language::getDefault() : $this->session->userdata('trl');
        $this->session->unset_userdata('trl');
        if(isset($_POST['translation'])){         
            $this->trl = $_POST['translation'];
            if(isset($_POST['uset_posts'])){                
                $this->input->post = array();
                $_POST = array();
            }
        }
        
        $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
                
        $this->article_id = $this->uri->segment(3);
        
    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit')
        {
            
            $this->jquery_ext->add_library("check_actions_add_edit.js");
            
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
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
                
                if ($method == 'add'){
                    $this->form_validation->set_rules('alias', lang('label_alias'), 'required|is_unique[articles.alias]');
                }
                elseif($method == 'edit'){
                    $this->form_validation->set_rules('alias', lang('label_alias'), 'required|is_unique_edit[articles.alias.article_id.'.$this->article_id.']');
                }
            
                if ($this->form_validation->run() == TRUE){
                    
                    $article_id = $this->Article->$method($this->article_id);
                                                            
                    if(isset($_POST['save'])){
                        redirect('articles/');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        /*
                         * save translation in cookie and use it to restore the correct translation
                         */
                        if(isset($_POST['translation'])){
                            $this->session->set_userdata('trl', $_POST['translation']);
                        }
                        redirect('articles/edit/'.$article_id);
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
        
        if($this->uri->segment(3) == ''){
            $this->layout = 'default';
            $redirect     = 'articles';
        }
        else{
            
            $this->jquery_ext->add_plugin('dialog-select-article');
            $this->jquery_ext->add_plugin('iframe_auto_height');
            $script = "autoHeightIframe('jquery_ui_iframe');";
            
            $script .= "$('table.list tr.row td a').bind('click', function(){
                
                           var article_id = $(this).attr('href').split('/');
                           article_id     = article_id[article_id.length-1];
                           
                           var article_alias = $(this).attr('lang');

                           var article_name = $.trim($(this).html());                          
                           
                           if(parent.$('#article_name').attr('type')){
                               parent.$('#article').val(article_id);
                               parent.$('#article_name').val(article_name);     
                           }
                           else{
                               parent.tinyMCE.execCommand('mceInsertContent', false, '<a href=\"article:'+article_alias+'\" >'+article_name+'</a>');
                           }

                           parent.$('#dialog-select-article').dialog('close');
                           
                           return false;
                           
                       });";
            $this->jquery_ext->add_script($script);
            
            $this->layout = $this->uri->segment(3);
            $redirect     = 'articles/index/'.$this->layout;
        }
        
        // delete articles
        if(isset($_POST['delete'])){
            $result = $this->Article->delete();
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect($redirect.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->Article->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect($redirect.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->Article->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect($redirect);
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
            $this->session->set_userdata('articles_filters', $filters);
            redirect($redirect);
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata('articles_filters');
            redirect($redirect);
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata('articles_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata('articles_order', $_POST['order_by']);
            redirect($redirect);
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata('articles_page_results', $_POST['page_results']);
            redirect($redirect);
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata('articles_filters');        
        $order_by = $this->session->userdata('articles_order');
        $limit    = $this->session->userdata('articles_page_results');
                
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
        
        // get articles
        $data              = $filters;
        $data['order']     = trim(str_replace('`', '', $order_by));
        $data['limit']     = $limit;
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($this->Article->getArticles($filters))/$limit);
        $data["articles"]  = $this->Article->getArticles($filters, $order_by, $limit_str);
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('articles/list', $data, true);
        $this->load->view('layouts/'.$this->layout , $content);
        
    }
	
    public function add()
    {   
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');

        $content["content"] = $this->load->view('articles/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        if($this->uri->segment(4) == 'history'){
            $data = $this->Article->getHistoryDetails($this->article_id, urldecode($this->uri->segment(5)) );             
        }
        else{
            $data = $this->Article->getDetails($this->article_id);
        }
        
        $data = @array_merge($data, $this->Custom_field->getFieldsValues($this->article_id));        
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        
        $data['article_history'] = $this->Article->getHistory($this->article_id);
        
        
        //print_r($data);

        $content["content"] = $this->load->view('articles/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
    
}