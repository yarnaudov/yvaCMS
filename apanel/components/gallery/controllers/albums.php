<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Albums extends MY_Controller {
    
    public  $trl;
    public  $extension = 'gallery';
    public  $page;
    private $album_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        if($this->uri->segment(3) != 'albums'){
            redirect('components/gallery/albums');
        }
        
        $this->load->model('Album');
        $this->load->model('Image');
        $this->load->model('Article');
        
        parent::_loadComponetLanguages('gallery');
        
        $this->tool_title = lang('com_gallery_label_gallery').' '.lang('com_gallery_label_albums');
        
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
        
        $this->album_id = $this->uri->segment(5);
        
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
                            buttonAlbum: '".base_url('img/iconCalendar.png')."',
                            buttonAlbumOnly: true
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
                                                            
                    $album_id = $this->Album->$method($this->album_id);

                    if(isset($_POST['save'])){
                        redirect('components/gallery/albums');
                        exit();
                    }
                    elseif(isset($_POST['apply'])){
                        /*
                            * save translation in cookie and use it to restore the correct translation
                            */
                        if(isset($_POST['translation'])){
                            $this->session->set_userdata('trl', $_POST['translation']);
                        }
                        redirect('components/gallery/albums/edit/'.$album_id);
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
        
        // delete albums
        if(isset($_POST['delete'])){
            $result = $this->Album->delete();
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('components/gallery/albums'.$page);
                exit();
            }
        }
        
        // change status
        if(isset($_POST['change_status'])){
            $result = $this->Album->changeStatus($_POST['element_id'], $_POST['change_status']);
            if($result == true){
                if($this->page > 1){
                    $page = "?page=".$this->page;
                }
                redirect('components/gallery/albums'.$page);
                exit();
            }
        }
        
        // change order
        if(isset($_POST['change_order'])){
            $result = $this->Album->changeOrder($_POST['element_id'], $_POST['change_order']);
            if($result == true){
                redirect('components/gallery/albums');
                exit();
            }
        }
        
        // set filters
        if(isset($_POST['search'])){
            $filters = array();
            if(isset($_POST['search_v']) && !empty($_POST['search_v'])){
                $filters['search_v'] = $_POST['search_v'];
            }
            if(isset($_POST['article']) && $_POST['article'] != "none"){
                $filters['article'] = $_POST['article'];
            }
            if(isset($_POST['status']) && $_POST['status'] != "none"){
                $filters['status'] = $_POST['status'];
            }            
            $this->session->set_userdata('albums_filters', $filters);
            redirect('components/gallery/albums');
            exit();
        }
        
        // clear filters
        if(isset($_POST['clear'])){
            $this->session->unset_userdata('albums_filters');
            redirect('components/gallery/albums');
            exit();
        }
        
        // set order by
        if(isset($_POST['order_by'])){
            $_POST['order_by'] = "`".$_POST['order_by']."`";
            $order_by = $this->session->userdata('albums_order');
            if($order_by == $_POST['order_by']){
                $_POST['order_by'] = $_POST['order_by']." DESC";
            }
            $this->session->set_userdata('albums_order', $_POST['order_by']);
            redirect('components/gallery/albums');
            exit();            
        }
        
        // set limit
        if(isset($_POST['limit'])){
            $this->session->set_userdata('albums_page_results', $_POST['page_results']);
            redirect('components/gallery/albums');
            exit();            
        }
        
        //get filters, order by and limit
        $filters  = $this->session->userdata('albums_filters');        
        $order_by = $this->session->userdata('albums_order');
        $limit    = $this->session->userdata('albums_page_results');
        
        // set default filter and otder by
        $filters  == "" ? $filters  = array() : "";
        $order_by == "" ? $order_by = "`order`" : "";
        $limit    == "" ? $limit = $this->config->item('default_paging_limit') : "";
        
        $limit_str = $limit == 'all' ? '' : ($this->page-1)*$limit.', '.$limit;
                
        // get albums
        $data              = $filters;
        $data['order']     = trim(str_replace('`', '', $order_by));
        $data['limit']     = $limit;
        $data['max_pages'] = $limit == 'all' ? 0 : ceil(count($this->Album->getAlbums($filters))/$limit);
        $data["albums"]    = $this->Album->getAlbums($filters, $order_by, $limit_str);
        
        // create sub actions menu
        $data['sub_menu'] = $this->Ap_menu->getSubActions($this->current_menu);
        $current_key = key($data['sub_menu']);
        unset($data['sub_menu'][$current_key]);
        
        // set css class on sorted element
        $elm_id = trim(str_replace(array('`','DESC'), '', $order_by));
        $class  = substr_count($order_by, 'DESC') == 0 ? "sorted" : "sorted_desc";        
        $script = "$('#".$elm_id."').addClass('".$class."');";
        $this->jquery_ext->add_script($script);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('gallery/albums/list', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {   
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');

        $content["content"] = $this->load->view('gallery/albums/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data = $this->Album->getDetails($this->album_id);
        $data = @array_merge($data, $this->Custom_field->getFieldsValues($this->album_id));        
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        $data['meta'] = '<meta http-equiv="cache-control" content="no-cache">';
        
        //print_r($data);

        $content["content"] = $this->load->view('gallery/albums/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }

}