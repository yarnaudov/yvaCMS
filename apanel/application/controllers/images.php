<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Images extends MY_Controller {
    
    public  $trl;
    public  $extension = 'images';
    public  $page;
    private $image_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Image');
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
        
        $this->image_id = $this->uri->segment(3);
        
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
                
                $this->jquery_ext->add_plugin("lightbox");
                
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
            
            $script .= "$('input.file').bind('change', function(){
                            $('input.text').val($(this).val());
                        });";
            
            $this->jquery_ext->add_script($script);
            $this->jquery_ext->add_plugin("tinymce");
            $this->jquery_ext->add_library("tinymce.js");

            $this->load->helper('form');
            $this->load->library('form_validation');            
            
            if(isset($_POST['save']) || isset($_POST['apply'])){   
                
                $this->form_validation->set_rules('title', lang('label_title'), 'required');
                            
                if ($this->form_validation->run() == TRUE){
                    
                    /*
                     * check image file 
                     */
                    $ext = end(explode(".", $_FILES["file"]["name"]));
                    
                    if($method == 'add' && $_FILES["file"]["size"] == 0){
                        $msg = lang('msg_image_empty_file');
                    }
                    
                    if($_FILES["file"]["size"] > 0){
                        
                        if($_FILES["file"]["size"] > $this->config->item('max_image_size')){                        
                            $msg = str_replace('{max_size}', (($this->config->item('max_image_size')/1024)/1024)."MB", lang('msg_image_max_file'));                        
                        }
                        elseif(!in_array(strtolower($ext), $this->config->item('allowed_image_ext'))){
                            $msg = str_replace('{allowed_ext}', implode(", ", $this->config->item('allowed_image_ext')), lang('msg_image_allowed_ext'));
                        }
                    }
                    
                    if(isset($msg)){
                        $this->session->set_userdata('error_msg', $msg);
                    }
                    else{
                    
                    
                        $image_id = $this->Image->$method($this->image_id);

                        if(isset($_POST['save'])){
                            redirect('images/');
                            exit();
                        }
                        elseif(isset($_POST['apply'])){
                            /*
                             * save translation in cookie and use it to restore the correct translation
                             */
                            if(isset($_POST['translation'])){
                                $this->session->set_userdata('trl', $_POST['translation']);
                            }
                            redirect('images/edit/'.$image_id);
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
        
        // delete images
        if(isset($_POST['delete'])){
            $result = $this->Image->delete();
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('images'.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->Image->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('images'.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->Image->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect('images');
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
            if(isset($_POST['article']) && $_POST['article'] != "none"){
                $filters['article'] = $_POST['article'];
            }
            if(isset($_POST['status']) && $_POST['status'] != "none"){
                $filters['status'] = $_POST['status'];
            }            
            $this->session->set_userdata('images_filters', $filters);
            redirect('images');
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata('images_filters');
            redirect('images');
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata('images_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata('images_order', $_POST['order_by']);
            redirect('images');
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata('images_page_results', $_POST['page_results']);
            redirect('images');
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata('images_filters');        
        $order_by = $this->session->userdata('images_order');
        $limit    = $this->session->userdata('images_page_results');
        
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
                
        // get images
        $data             = $filters;
        $data['order']    = trim(str_replace('`', '', $order_by));
        $data['limit']     = $limit;
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($this->Image->getImages($filters))/$limit);
        $data["images"]   = $this->Image->getImages($filters, $order_by, $limit_str);
        $data["articles"] = $this->Article->getArticlesByCategory(array(), "`order`");
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('images/list', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {   
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');

        $content["content"] = $this->load->view('images/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data = $this->Image->getDetails($this->image_id);
        $data = @array_merge($data, $this->Custom_field->getFieldsValues($this->image_id));        
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        $data['meta'] = '<meta http-equiv="cache-control" content="no-cache">';
        
        //print_r($data);

        $content["content"] = $this->load->view('images/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }

}