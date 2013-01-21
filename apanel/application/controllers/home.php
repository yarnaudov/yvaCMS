<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

    public function index()
    {
        
        $this->load->model('Article');
        
        $articles = $this->Article->getArticles(array(), "updated_on DESC, created_on DESC", "0, 10");
        
        $content = $this->load->view('home', compact('articles'), true);		
        $this->load->view('layouts/default', compact('content'));
        
    }
    
    public function login()
    {
        
        if(isset($_POST['login'])){
            
            $this->load->model('User');
            $this->User->login();
            
            if(isset($_SESSION['user_id'])){        
                redirect(current_url()); 
                exit();
            }
            
        }
        
        $content["content"] = $this->load->view('login', '', true);		
        $this->load->view('layouts/simple', $content);
        
    }
    
    public function logout()
    {
        
        $this->load->model('User');
        $this->User->logout();
        redirect($_SERVER['HTTP_REFERER']);
        exit(); 
        
    }
    
    public function ajax($action)
    {
        
        switch($action){
            case "get_menus":
                
                $this->load->model('Menu');
                $menus = $this->Menu->getMenus(array('category_id' => $this->input->get('category')));
                
                $menus_arr = array();
                foreach($menus as $menu){
                    $lavel = "";
                    for($i = 1; $i < $menu['lavel']; $i++){
                        $lavel .= "- ";
                    }
                    $menus_arr[] = array('value' => $menu['id'], 'text' => $lavel.$menu['title']);
                }
                echo json_encode($menus_arr);
                
            break;
            
        }
        
    }
    
    public function no_access()
    {
        
        $content["content"] = $this->load->view('no_access', '', true);		
        $this->load->view('layouts/default', $content);
        
    }
    
    public function media()
    {
               
        $this->jquery_ext->add_library('check_actions_browse_media.js');
        
        $data['folder']   = isset($_POST['folder'])   ? $_POST['folder']   : $this->config->item('media_dir').'/';

        if(isset($_POST['up'])){
            $folders = explode('/', $data['folder']);
            if(count($folders) > 2){          
                unset($folders[count($folders)-2], $folders[count($folders)-1]);
                $data['folder'] = implode('/', $folders);
            }
        }
        
        if(isset($_POST['upload'])){
        
            $config['upload_path']   = realpath(dirname(__FILE__).'/../../../').'/'.$data['folder'];
            $config['allowed_types'] = 'gif|jpg|png|doc|docx|xls|xlsx|pdf';
            $config['max_size']	     = '1000';
            $config['max_width']     = '1024';
            $config['max_height']    = '768';              
            
            $this->load->library('upload', $config);

            if ( ! @$this->upload->do_upload('file'))
            {
                $error = array('error' => $this->upload->display_errors());
                $data['error'] = $this->upload->display_errors();
            }
            else
            {
                $data_u = array('upload_data' => $this->upload->data());
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
                $folder = realpath(dirname(__FILE__).'/../../../').'/'.$data['folder'].$_POST['folder_name'];
                mkdir($folder, 0777);
                unset($_POST['folder_name']);
            }
            
        }
                
        if(isset($_POST['rename'])){
            
            $item = $_POST['item'][0];
            $folder = realpath(dirname(__FILE__).'/../../../').'/'.$data['folder'];
            rename($folder.$item, $folder.$_POST['new_name']);
            
        }
        
        if(isset($_POST['delete'])){
                      
            $folder = realpath(dirname(__FILE__).'/../../../').'/'.$data['folder'];
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
        
        $content["content"] = $this->load->view('media/browse', $data, true);		
        $this->load->view('layouts/simple_ajax', $content);
        
    }
    
    public function modules()
    {
        $this->load->model('Module');
        $data["modules"]  = $this->Module->getModules();
        
        $this->jquery_ext->add_library('scroll_into_view.js');
        
        /*
         * load modules languages
         */
        $modules_dir = FCPATH.'modules/';
        $handle = opendir($modules_dir);
        while (false !== ($entry = readdir($handle))) { 
            if(substr($entry, 0, 1) == "." || !is_dir($modules_dir.$entry)){
                continue;
           }  
           
           $this->load->language($entry);          
                                                         
        }
        
        $content["content"] = $this->load->view('modules/simple_list', $data, true);
        $this->load->view('layouts/simple_ajax', $content);
        
    }
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */