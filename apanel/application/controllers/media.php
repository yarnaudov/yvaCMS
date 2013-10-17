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
	'eml', 'json', 'xml', 'rtx', 'zip', 'psd');
    
    public function browse($param = '')
    {
               
        $this->jquery_ext->add_library('check_actions_browse_media.js');
        
        $data['folder'] = isset($_POST['folder']) ? $_POST['folder'] : $this->config->item('media_dir').'/';
	
	$folder = realpath(FCPATH.'../').'/'.$data['folder'];
	if(!file_exists($folder)){
	    mkdir($folder, 0777);
	    copy(BASEPATH."/index.html", $folder."/index.html");
	}
	
        $data['param']  = $param;

        if(isset($_POST['up'])){
            $folders = explode('/', $data['folder']);
            if(count($folders) > 2){          
                unset($folders[count($folders)-2], $folders[count($folders)-1]);
                $data['folder'] = implode('/', $folders);
            }
        }
        elseif(isset($_POST['upload'])){
        
            $config['upload_path']   = realpath(FCPATH.'../').'/'.$data['folder'];
            $config['allowed_types'] = implode('|', $this->allowed_types);
            $config['max_size']	     = '10000';             
            
            $this->load->library('upload', $config);
            
            if ( ! @$this->upload->do_multi_upload('file')){
                $data['error'] = sprintf($this->upload->display_errors(), implode(', ', $this->allowed_types).' - '.$this->upload->file_type);
            }
            else{
                $data_u = array('upload_data' => $this->upload->get_multi_upload_data());
            }
            
        }        
        elseif(isset($_POST['create_folder'])){
            
            $folder = trim($_POST['folder_name']);
            
            if(empty($folder)){
                $data['error'] = lang('msg_empty_folder');
            }
            elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $folder)){
               $data['error'] = lang('msg_folder_allowed_chars');
            }
            else{ 
                $folder = realpath(FCPATH.'../').'/'.$data['folder'].$_POST['folder_name'];
                if(file_exists($folder)){
                    $data['error'] = lang('msg_folder_exists');
                }
                else{
                    mkdir($folder, 0777);
		    copy(BASEPATH."/index.html", $folder."/index.html");
                    unset($_POST['folder_name']);
                }
            }
            
        }
        elseif(isset($_POST['rename'])){

            $item = $_POST['item'][0];
	    $new_name = trim($_POST['new_name']);
	    
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
            
        }
        elseif(isset($_POST['delete'])){
            
            foreach($_POST['item'] as $item){
                
                if(is_dir($folder.$item)){
                    $this->load->helper('delete_directory');
                    delete_directory($folder.$item);                
                }
                else{
                    unlink($folder.$item);                
                }
                
            }
            
        }
        elseif(isset($_POST['download'])){
            
	    $this->load->helper('download');
	    
	    if(count($_POST['item']) == 1){
		$item = $_POST['item'][0];
		$file_data = file_get_contents($folder.$item);
		force_download($item, $file_data); 
	    }
	    else{
		
                $this->load->library('zip');
                
		foreach($_POST['item'] as $item){                    
                    $file_data = file_get_contents($folder.$item);
                    $this->zip->add_data($item, $file_data);                    
		}
		
                $this->zip->download('media-files-'.date('YmdHis').'.zip'); 
                
	    }
	    
	}
	
        $this->jquery_ext->add_plugin('iframe_auto_height');        
        $script = "autoHeightIframe('jquery_ui_iframe');";        
        $this->jquery_ext->add_script($script);
        
        if($param == 'article'){
            
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
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */