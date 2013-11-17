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

        $this->load->config('gallery');
        
        parent::_loadComponetLanguages('gallery');
        
        $this->tool_title = lang('com_gallery_label_gallery').' '.lang('com_gallery_label_images');
        
        $this->page     = isset($_GET['page']) ? $_GET['page'] : 1;        
        $this->image_id = $this->uri->segment(5);
        
        $this->jquery_ext->add_library("../components/gallery/js/gallery.js");
        
    }
    
    public function _remap($method)
    {
        if ($method == 'add' || $method == 'edit')
        {
            
            $this->jquery_ext->add_plugin("validation");
            $this->jquery_ext->add_library("check_actions_add_edit.js");
            
            $this->jquery_ext->add_plugin('jcrop');
            $this->jquery_ext->add_library('../components/gallery/js/crop.js');
            
            if ($method == 'add'){
               
                $script = "$('select[name=translation]').attr('disabled', true);";
                
            }
            elseif($method == 'edit'){
                
                //$this->jquery_ext->add_plugin("lightbox");
                
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
            $this->jquery_ext->add_css("../components/gallery/css/gallery.css");

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
        
	$this->layout = 'default';
        $redirect     = 'components/gallery/images';
	
	if($this->uri->segment(5) != ''){
	    
	    $this->jquery_ext->add_plugin('iframe_auto_height');
            $script = "autoHeightIframe('jquery_ui_iframe');";
	    
	    $script .= "$('a.select').unbind('click');
                       $('a.select').click(function(event){
                
                           event.preventDefault();

                           if($('.checkbox:checked').length == 0){                               
                               $( '#dialog-edit1' ).dialog( 'open' );
                               $( '.ui-widget-overlay' ).css('opacity', '0');
                               return false;
                           }
                           
                           var html = new Array();
                           $('.checkbox:checked').each(function(){
                           
                               var value = $(this).val();
			       var img = $(this).parent().parent().find('img').attr('src');
			       var title = $(this).parent().parent().find('a:eq(1)').html();
       
                               if(parent.$('#article_images input[value=\"'+value+'\"]').length == 0){

                                   html.push('<li>');
                                   html.push('<input type=\"hidden\" name=\"params[images][]\" value=\"'+value+'\" >');
                                   
                                   html.push('<table><tr>');
                                   
                                   html.push('<td class=\"img\" >');
				   html.push('<img src=\"'+img+'\" >');
                                   html.push('<\/td>');
                                   
                                   html.push('<td>'+title+'<\/td>');
                                   
                                   html.push('<td class=\"actions\" >');
                                   html.push('<img class=\"handle\" src=\"'+DOCUMENT_BASE_URL+'".APANEL_DIR."/img/iconMove.png\" >');
                                   html.push('<a class=\"styled delete\" >&nbsp;<\/a>');
                                   html.push('<\/td>');
                                   
                                   html.push('<\/tr><\/table><\/li>');
                               
                               }
                               
                           });
                           
                           parent.$('#article_images').append(html.join(''));
                           parent.$( '#jquery_ui' ).dialog( 'close' ); 

                       });";	    
	    $this->jquery_ext->add_script($script);
	    
	    $this->layout = $this->uri->segment(5);
            $redirect     = 'components/gallery/images/index/'.$this->layout;
	    
	}
	
        /*
         *  parent index method handels: 
         *  delete, change status, change order, set order by, set filters, 
         *  clear filter, set limit, get sub menus, set class on sorted element
         */
        $data = parent::index($this->Image, 'gallery_images'.$this->layout, $redirect);
	
	if($this->input->get('album')){
	    $data['filters']['album'] = $this->input->get('album');
	}
     
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
        $this->load->view('layouts/'.$this->layout, $content);
        
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
        //$this->Image->removeResized('1.jpg');
        $data                  = $this->Image->getDetails($this->image_id);
        $data['album_params']  = $this->Album->getDetails($data['album_id'], 'params');
        $data['albums']        = $this->Album->getForDropdown();
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');
        $data['meta']          = '<meta http-equiv="cache-control" content="no-cache">';
        
        $content["content"] = $this->load->view('gallery/images/add', $data, true);		
        $this->load->view('layouts/default', $content);
        
    }

    public function quickadd()
    {
        //print_r($_FILES);
        
        $this->jquery_ext->add_plugin("validation");
        $this->jquery_ext->add_library("check_actions_add_edit.js");
	$this->jquery_ext->add_plugin("tinymce");
	$this->jquery_ext->add_library("tinymce.js");
        
        $this->load->helper('form');
        $this->load->library('form_validation');            

        if(isset($_POST['save']) || isset($_POST['apply'])){   

            $this->form_validation->set_rules('title', lang('label_title'), 'required');

            if ($this->form_validation->run() == TRUE){
                //print_r($_FILES['files']['name']);
                for($key = 0; $key < count($_FILES['files']['name']); $key++){
                //echo $key."<---<br/>";
                    /*
                     * check image file 
                     */
                    $ext = end(explode(".", $_FILES['files']['name'][$key]));

                    if($_FILES['files']["size"][$key] == 0){
                        @$msg .= $name." - ".lang('msg_image_empty_file')."<br/>";
                    }

                    if($_FILES["files"]["size"][$key] > 0){

                        if($_FILES["files"]["size"][$key] > $this->config->item('max_image_size')){                        
                            @$msg .= str_replace('{max_size}', (($this->config->item('max_image_size')/1024)/1024)."MB", $_FILES['files']['name'][$key]." - ".lang('msg_image_max_file'))."<br/>";                        
                        }
                        elseif(!in_array(strtolower($ext), $this->config->item('allowed_image_ext'))){
                            @$msg .= str_replace('{allowed_ext}', implode(", ", $this->config->item('allowed_image_ext')), $_FILES['files']['name'][$key]." - ".lang('msg_image_allowed_ext'))."<br/>";
                        }
                        
                    }

                    if(isset($msg)){
                        $this->session->set_userdata('error_msg', $msg);
                    }
                    else{
                        
                        $image_id = $this->Image->add($key);
                        
                    }
                    
                }
                
                if(isset($_POST['save'])){
                    redirect('components/gallery/images/');
                }
                elseif(isset($_POST['apply'])){
                    redirect('components/gallery/images/quickadd');
                }

                exit();
                
            }

        }
        
        
        $this->jquery_ext->add_plugin("validation");
        $this->jquery_ext->add_library("check_actions_add_edit.js");

        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['albums']        = $this->Album->getForDropdown();
        $data['custom_fields'] = $this->Custom_field->getCustomFields(array('status' => 'yes'), '`order`');

        $content["content"] = $this->load->view('gallery/images/quickadd', $data, true);     
        $this->load->view('layouts/default', $content);

    }

    public function change()
    {
        
        // TODO change the ext in the database upon save!
        
        $ext = end(explode(".", $_FILES["file"]["name"]));
        $tmp_file = $this->config->item('images_tmp_dir').'/'.$this->image_id.'.'.$ext;
        move_uploaded_file($_FILES['file']['tmp_name'], FCPATH.'../'.$tmp_file);

        $image_data = getimagesize(FCPATH.'../'.$tmp_file);

        $image['width']  = $image_data[0];
        $image['height'] = $image_data[1];
        $image['src']    = $tmp_file;
	$image['ext']    = $ext;

        echo "<span>".json_encode($image)."</span>";
	exit;
	
    }

    public function crop()
    {


        $images_tmp_dir = FCPATH.'../'.$this->config->item('images_tmp_dir');
        if(!file_exists($images_tmp_dir)){
            mkdir($images_tmp_dir, 0777);
	    copy(BASEPATH."/index.html", $images_tmp_dir."/index.html");
        }

        $targ_w = $this->input->post('w');
        $targ_h = $this->input->post('h');
        $jpeg_quality = 90;

        $src   = $this->input->post('image');
        $img_r = imagecreatefromjpeg($src);
        $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

        imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);

        $pathinfo = pathinfo($src);
        $extension = current(explode('?', $pathinfo['extension']));
        $dst_src = $this->config->item('images_tmp_dir').'/'.$pathinfo['filename'].'.'.$extension;

        //header('Content-type: image/jpeg');
        imagejpeg($dst_r, FCPATH.'../'.$dst_src, $jpeg_quality);

        $image_data = getimagesize(FCPATH.'../'.$dst_src);

        $image['width']  = $image_data[0];
        $image['height'] = $image_data[1];
        $image['src']    = $dst_src;

        echo json_encode($image);

        //echo $dst_src;
        //imagedestroy($dst_r);
        //redirect('upload/edit');
        exit;

    }

    public function rotate()
    {

        $images_tmp_dir = FCPATH.'../'.$this->config->item('images_tmp_dir');        
        if(!file_exists($images_tmp_dir)){
            mkdir($images_tmp_dir, 0777);
	    copy(BASEPATH."/index.html", $images_tmp_dir."/index.html");
        }

        // File and rotation
        $image_src    = $this->input->post('image_src');
        $degrees      = $this->input->post('degrees');
        $jpeg_quality = 90;

        // Load
        $source = imagecreatefromjpeg($image_src);

        // Rotate
        $rotate = imagerotate($source, $degrees, 0);

        // Output
        $pathinfo  = pathinfo($image_src);
        $extension = current(explode('?', $pathinfo['extension']));
        $tmp_image = $this->config->item('images_tmp_dir').'/'.$pathinfo['filename'].'.'.$extension;

        imagejpeg($rotate, FCPATH.'../'.$tmp_image, $jpeg_quality);

        // Free the memory
        imagedestroy($source);
        imagedestroy($rotate);

        $image_data = getimagesize(FCPATH.'../'.$tmp_image);

        $image['width']  = $image_data[0];
        $image['height'] = $image_data[1];
        $image['src']    = $tmp_image;

        echo json_encode($image);

        //echo $dst_image;

        exit;

    }

    public function origin()
    {
        
        $id = $this->input->post('id');
        $ext = $this->Image->getDetails($id, 'ext');

        $img_file = FCPATH.'../'.$this->config->item('images_origin_dir').'/'.$id.'.'.$ext;
        $tmp_file = FCPATH.'../'.$this->config->item('images_tmp_dir').'/'.$id.'.'.$ext;

        copy($img_file, $tmp_file);

        $image_data = getimagesize($tmp_file);

        $image['width']  = $image_data[0];
        $image['height'] = $image_data[1];
        $image['src']    = $this->config->item('images_tmp_dir').'/'.$id.'.'.$ext;

        echo json_encode($image);

        exit;

    }
    
    public function unlink($image)
    {
        
        if(file_exists($image)){
            unlink($image);
            return true;
        }
        else{
            return false;
        }

    }

}