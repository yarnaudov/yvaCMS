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
            
            case "load":
                
                $this->load->helper('form');
                $this->load->view($this->input->get('view'));
                
            break;
        
            case "load_custom_fields":
                
                $this->load->helper('form');
                
                $this->extension = $this->input->get('extension');                
                $extension_key   = $this->input->get('extension_key');
                
                $filters['status']        = 'yes';
                $filters['extension_key'] = $extension_key;
                
                $custom_fields = $this->Custom_field->getCustomFields($filters);
                    
                $this->load->view('custom_fields/load_fields', compact('custom_fields'));
                
            break;
            
        }
        
    }
    
    public function no_access()
    {
        
        $content["content"] = $this->load->view('no_access', '', true);		
        $this->load->view('layouts/default', $content);
        
    }
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */