<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Albums extends MY_Controller {
    
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
        
        parent::_loadComponetLanguages('gallery');
        
        $this->tool_title = lang('com_gallery_label_gallery').' '.lang('com_gallery_label_albums');
        
        $this->page     = isset($_GET['page']) ? $_GET['page'] : 1;        
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
        
        $data = parent::index($this->Album, 'gallery_albums', 'components/gallery/albums');
          
        // get albums
        $albums = $this->Album->getAlbums($data['filters'], $data['order_by']);
        if($data['limit'] == 'all'){
            $albums[0] = $albums;
        }
        else{
          $albums = array_chunk($albums, $data['limit']);
          $data['max_pages'] = count($albums);
        }

        $data['albums'] = count($albums) == 0 ? array() : $albums[($this->page-1)]; 
        
        // create sub actions menu
        $data['sub_menu'] = $this->Ap_menu->getSubActions($this->current_menu);
        $current_key = key($data['sub_menu']);
        unset($data['sub_menu'][$current_key]);
        
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