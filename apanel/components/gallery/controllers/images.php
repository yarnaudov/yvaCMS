<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Images extends MY_Controller {

    public  $extension = 'gallery';
    public  $page;
    private $image_id;
    
    function __construct()
    {
  	
        parent::__construct();    
        
        $this->load->model('Image');
        $this->load->model('Album');
        
        parent::_loadComponetLanguages('gallery');
        
        $this->tool_title = lang('com_gallery_label_gallery').' '.lang('com_gallery_label_images');
        
        $this->page     = isset($_GET['page']) ? $_GET['page'] : 1;        
        $this->image_id = $this->uri->segment(5);
        
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
                            redirect('components/gallery/images/');
                            exit();
                        }
                        elseif(isset($_POST['apply'])){
                            /*
                             * save translation in cookie and use it to restore the correct translation
                             */
                            if(isset($_POST['translation'])){
                                $this->session->set_userdata('trl', $_POST['translation']);
                            }
                            redirect('components/gallery/images/edit/'.$image_id);
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
        
        /*
         *  parent index method handels: 
         *  delete, change status, change order, set order by, set filters, 
         *  clear filter, set limit, get sub menus, set class on sorted element
         */
        $data = parent::index($this->Image, 'gallery_images', 'components/gallery/images');        
         
        // get images
        $images = $this->Image->getImages($data['filters'], $data['order_by']); 
        if($data['limit'] == 'all'){
            $images[0] = $images;
        }
        else{
          $images = array_chunk($images, $data['limit']);
          $data['max_pages'] = count($images);
        }

        $data['images'] = count($images) == 0 ? array() : $images[($this->page-1)]; 
        $data['albums'] = $this->Album->getForDropdown();
        
        // create sub actions menu
        $data['sub_menu'] = $this->Ap_menu->getSubActions($this->current_menu);
        $current_key = key($data['sub_menu']);
        unset($data['sub_menu'][$current_key]);
        
        // load custom jquery script
        $this->jquery_ext->add_library("check_actions.js");      

        $content["content"] = $this->load->view('gallery/images/list', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }
	
    public function add()
    {   
        
        $data['albums']        = $this->Album->getForDropdown();
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');

        $content["content"] = $this->load->view('gallery/images/add', $data, true);		
        $this->load->view('layouts/default', $content);
    }
	
    public function edit()      
    {
        
        $data                  = $this->Image->getDetails($this->image_id); 
        $data['albums']        = $this->Album->getForDropdown();
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        $data['meta']          = '<meta http-equiv="cache-control" content="no-cache">';
        
        $content["content"] = $this->load->view('gallery/images/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }

}