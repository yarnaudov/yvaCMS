<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media extends MY_Controller {
    
    private $allowed_types = array(
	
	# images
	'gif','jpg','jpeg','jpe','png','tiff','tif', 
	
	# videos
	'swf','wav','mp3','mp2','mpeg','mpg','mpe','qt','mov','avi', 'movie', 
	
	# documents
	'doc','docx','xls','xlsx','word','xl','pdf','csv','txt',
	
	# other formats
	'eml', 'json', 'xml', 'rtx', 'zip', 'rar', 'psd');
    
    private $max_size = '10240';
    
    public function __construct() {
        
        parent::__construct();

        $this->load->model('Setting');

        $settings = $this->Setting->getSettings();

        if(isset($settings['media_file_size'])){
                $this->max_size = $settings['media_file_size'];
        }	
        if(isset($settings['media_file_ext'])){
                $this->allowed_types = $settings['media_file_ext'];
        }
	
    }
	
    public function _remap($method, $params)
    {
        
        if ($method == 'index')
        {        
            $script = "$('#sub_actions li.first').attr('class', 'current')";
            $this->jquery_ext->add_script($script);  
        }
        
        // create sub actions menu
        $parent_id = $this->Ap_menu->getDetails($this->current_menu, 'parent_id');
        if(empty($parent_id)){
            $parent_id = $this->current_menu;
        }
        $this->sub_menu = $this->Ap_menu->getSubActions($parent_id);        
        $current_key = key($this->sub_menu);
        unset($this->sub_menu[$current_key]);
        
        $this->$method($params);
        
    }
    
    public function index()
    {
	
        $data = self::_actions(true);	

        $this->jquery_ext->add_library('check_actions_browse_media.js');

        $this->load->helper('directory');
        $data['entries'] = directory_map(realpath(FCPATH.'../').'/'.$data['folder'], true);

        // create sub actions menu
        $data['sub_menu'] = $this->sub_menu;

        $content["content"] = $this->load->view('media/list', $data, true);		
        $this->load->view('layouts/default', $content);

    }
    
    public function settings()
    {
	
        if(isset($_POST['save']) || isset($_POST['apply'])){
            $this->Setting->save();
            if(isset($_POST['save'])){
                redirect('media');
            }
            else{
                redirect('media/settings');
            }
        }

        $data['settings']['media_file_size'] = $this->max_size;
        $data['settings']['media_file_ext']  = $this->allowed_types;

        $this->load->helper('form');

        $this->load->library('upload');
        $this->upload->mimes_types('jpg');

        $data['mimes'] = $this->upload->mimes;

        // create sub actions menu
        $data['sub_menu'] = $this->sub_menu;

        $content["content"] = $this->load->view('media/settings', $data, true);		
        $this->load->view('layouts/default', $content);
	
    }
    
    public function browse($params = '')
    {

        $this->output->enable_profiler(FALSE);
        
        $data = self::_actions();
	
        $this->jquery_ext->add_library('check_actions_browse_media.js');
	
        $data['param']  = isset($params[0]) ? $params[0] : '';
	
        $this->jquery_ext->add_plugin('iframe_auto_height');        
        $script = "autoHeightIframe('jquery_ui_iframe');";        
        $this->jquery_ext->add_script($script);
        
        if($data['param'] == 'article'){
            
            $script = "$('a.select').unbind('click');
                       $('a.select').click(function(event){
                
                           event.preventDefault();

                           if($('.checkbox:checked').length == 0){                               
                               $( '#dialog-edit1' ).dialog( 'open' );
                               $( '.ui-widget-overlay' ).css('opacity', '0');
                               return false;
                           }
                           
                           var html = new Array();
                           $('.checkbox:checked').each(function(){
                           
                               var value = $('input[name=folder]').val()+$(this).val();
       
                               if(parent.$('#article_images input[value=\"'+value+'\"]').length == 0){

                                   html.push('<li>');
                                   html.push('<input type=\"hidden\" name=\"params[images][]\" value=\"'+value+'\" >');
                                   
                                   html.push('<table><tr>');
                                   
                                   html.push('<td class=\"img\" >');
                                   if($(this).hasClass('directory')){
                                       html.push('<img class=\"directory\" src=\"'+DOCUMENT_BASE_URL+'".APANEL_DIR."/img/media/iconFolder.png\" >');
                                   }
                                   else{
                                       html.push('<img src=\"'+DOCUMENT_BASE_URL+value+'\" >');
                                   }
                                   html.push('<\/td>');
                                   
                                   html.push('<td>'+value+'<\/td>');
                                   
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
            
        }
        
        $this->load->helper('directory');
        $data['entries'] = directory_map(realpath(FCPATH.'../').'/'.$data['folder'], true);
	
        $content["content"] = $this->load->view('media/browse', $data, true);		
        $this->load->view('layouts/simple', $content);
        
    }
    
    private function _actions($redirect = false)
    {

        $data['folder'] = $this->input->get_post('folder') ? $this->input->get_post('folder') : $this->config->item('media_dir').'/';

        $folder = realpath(FCPATH.'../').'/'.$data['folder'];
        if(!file_exists($folder)){
            mkdir($folder, 0777);
            copy(BASEPATH."/index.html", $folder."/index.html");
        }

        if($this->input->get_post('up')){
            
            $folders = explode('/', $data['folder']);
            if(count($folders) > 2){          
                unset($folders[count($folders)-2], $folders[count($folders)-1]);
                $data['folder'] = implode('/', $folders);
            }

            if($redirect == true){
                redirect('media?folder='.urlencode($data['folder']));
            }

        }
        elseif($this->input->get_post('upload')){

            $config['upload_path']   = realpath(FCPATH.'../').'/'.$data['folder'];
            $config['allowed_types'] = implode('|', $this->allowed_types);
            $config['max_size']	     = $this->max_size;

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_multi_upload('file')){
                $data['error'] = sprintf($this->upload->display_errors(), implode(', ', $this->allowed_types).' - '.$this->upload->file_type);
            }

            if(!isset($data['error']) && $redirect == true){
                redirect('media?folder='.urlencode($data['folder']));
            }

        }        
        elseif($this->input->get_post('create_folder')){

            $folder = trim($this->input->get_post('folder_name'));

            if(empty($folder)){
                $data['error'] = lang('msg_empty_folder');
            }
            elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $folder)){
                $data['error'] = lang('msg_folder_allowed_chars');
            }
            else{ 
                $folder = realpath(FCPATH.'../').'/'.$data['folder'].$this->input->get_post('folder_name');
                if(file_exists($folder)){
                    $data['error'] = lang('msg_folder_exists');
                }
                else{
                    mkdir($folder, 0777);
                    copy(BASEPATH."/index.html", $folder."/index.html");
                    unset($_POST['folder_name']);
                }
            }

            if($redirect == true){
                redirect('media?folder='.urlencode($data['folder']));
            }

        }
        elseif($this->input->get_post('rename')){

            $item = $this->input->get_post('item');
            $item = $item[0];
            $new_name = trim($this->input->get_post('new_name'));

            if(preg_match('/\./', $new_name)){
                $file = explode('.', $new_name);
                $ext = end($file);
            }

            if(!preg_match('/^[a-zA-Z0-9_'.(isset($ext) ? '\.' : '').']+$/', $new_name)){
                $data['error'] = lang('msg_folder_allowed_chars');
            }
            elseif(isset($ext) && !in_array($ext, $this->allowed_types)){
                $data['error'] = sprintf(lang('msg_rename_file_allowed_ext'), implode(', ', $this->allowed_types));
            }
            else{
                rename($folder.$item, $folder.$new_name);
            }

            if($redirect == true){
                redirect('media?folder='.urlencode($data['folder']));
            }

        }
        elseif($this->input->get_post('delete')){

            foreach($this->input->get_post('item') as $item){

                if(is_dir($folder.$item)){
                    $this->load->helper('delete_directory');
                    delete_directory($folder.$item);                
                }
                else{
                    unlink($folder.$item);                
                }

            }

            if($redirect == true){
                redirect('media?folder='.urlencode($data['folder']));
            }

        }
        elseif($this->input->get_post('download')){

            $this->load->helper('download');

            if(count($this->input->get_post('item')) == 1){
                $item = $this->input->get_post('item');
                $item = $item[0];
                $file_data = file_get_contents($folder.$item);
                force_download($item, $file_data); 
            }
            else{

                $this->load->library('zip');

                foreach($this->input->get_post('item') as $item){                    
                    $file_data = file_get_contents($folder.$item);
                    $this->zip->add_data($item, $file_data);                    
                }

                $this->zip->download('media-files-'.date('YmdHis').'.zip'); 

            }

        }

        return $data;
	
    }
    
    public function image_settings($image)
    {
        
        $this->output->enable_profiler(FALSE);
        
        $this->jquery_ext->add_plugin('jcrop');
        $this->jquery_ext->add_library('../components/gallery/js/crop.js');
        
        $this->jquery_ext->add_plugin('iframe_auto_height');
        $script = "autoHeightIframe('jquery_ui_iframe');";        
        $this->jquery_ext->add_script($script);
        
        $image = $this->input->get('image');
        $image_data = getimagesize(FCPATH.'../'.$image);
        
        $content["content"] = $this->load->view('media/image_settings', compact('image', 'image_data'), true);
        $this->load->view('layouts/simple_ajax', $content);
        
    }
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */