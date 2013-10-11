<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media extends MY_Controller {
    
    public function browse($param = '')
    {
               
        $this->jquery_ext->add_library('check_actions_browse_media.js');
        
        $data['folder'] = isset($_POST['folder']) ? $_POST['folder'] : $this->config->item('media_dir').'/';
	
	$folder = realpath(FCPATH.'../').'/'.$data['folder'];
	if(!file_exists($folder)){
	    mkdir($folder, 0777);
	}
	
        $data['param']  = $param;

        if(isset($_POST['up'])){
            $folders = explode('/', $data['folder']);
            if(count($folders) > 2){          
                unset($folders[count($folders)-2], $folders[count($folders)-1]);
                $data['folder'] = implode('/', $folders);
            }
        }
        
        if(isset($_POST['upload'])){
        
            $config['upload_path']   = realpath(FCPATH.'../').'/'.$data['folder'];
            $config['allowed_types'] = 'gif|jpg|jpeg|jpe|png|gif|doc|docx|xls|xlsx|pdf|txt|csv|ttf';
            $config['max_size']	     = '10000';             
            
            $this->load->library('upload', $config);

            //print_r($_FILES);
            //exit;
            
            if ( ! @$this->upload->do_multi_upload('file'))
            {
                $data['error'] = $this->upload->display_errors();
            }
            else
            {
                $data_u = array('upload_data' => $this->upload->get_multi_upload_data());
            }
            
        }
        
        if(isset($_POST['create_folder'])){
            
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
                
        if(isset($_POST['rename'])){
            
            $item = $_POST['item'][0];
            $folder = realpath(FCPATH.'../').'/'.$data['folder'];
            rename($folder.$item, $folder.$_POST['new_name']);
            
        }
        
        if(isset($_POST['delete'])){
            
            $folder = realpath(FCPATH.'../').'/'.$data['folder'];
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
        
        $content["content"] = $this->load->view('media/browse', $data, true);		
        $this->load->view('layouts/simple', $content);
        
    }
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */