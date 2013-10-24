<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

    public  $extension = '';
    
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
        
	$this->output->enable_profiler(FALSE);
	
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
				
				if($this->input->get('lang')){
					$this->load->language($this->input->get('lang'));
				}
				
				if($this->input->get('view')){
					$this->load->view($this->input->get('view'));
				}
                
            break;
        
            case "load_custom_fields":
                
                $this->load->helper('form');
                
                $this->extension = $this->input->post('extension');
		$extension_key   = $this->input->post('extension_key');
		$extension_keys  = $this->input->post('extension_keys');

                $filters['status']         = 'yes';
		if(!empty($extension_key)){
		    $filters['extension_key'] = $extension_key;
		}
		if(!empty($extension_keys)){
		    $filters['extension_keys'] = $extension_keys;
		}
                
		$model      = $this->input->post('model');
		$element_id = $this->input->post('element_id');
		
		$this->load->model($model);
		$data = $this->$model->getDetails($element_id);
		
                $data['custom_fields'] = $this->Custom_field->getCustomFields($filters);
		                    
                $this->load->view('custom_fields/load_fields', $data);
                
            break;
        
            case "load_menu_type":
                
                $this->load->helper('form');
                
                $type = $this->input->get('type');
                
                if(preg_match('/^components{1}/', $type)){
                                            
                    $type_arr = explode('/', $type);

                    $this->_loadComponetLanguages($type_arr[1]);
                    
                    $options_file = '../../'.$type_arr[0].'/'.$type_arr[1].'/views/apanel_options';

                    if(count($type_arr) > 2){                                                
                        $param      = $type_arr[2];
                        $type_label = lang('com_'.$type_arr[1]).' > '.lang($this->components[$type_arr[1]]['menus'][$type_arr[2]]);
                    }
                    else{                                                
                        $type_label = lang($this->components[$type_arr[1]]['menus'][$type_arr[1]]);
                    }

                }elseif(!empty($type)){ 
                    $options_file = 'menus/'.$type;
                    $type_label   = lang('label_'.$type);
                }
                    
                echo '<span id="type_label" ><strong>'.$type_label.'</strong> - </span>';
                $this->load->view($options_file, compact('param', isset($param) ? $param : ''));
                
            break;
            
            case "load_module_type":
                
                $this->load->helper('form');
                
                $type = $this->input->get('type');
                
                $this->_loadModuleLanguage($type);
                
                echo '<span id="type_label" ><strong>'.lang('label_'.$type).'</strong> - </span>';
                
                if(file_exists('modules/' . $type . '/views/apanel_options.php')){
                    include_once 'modules/' . $type . '/views/apanel_options.php';
                }
		
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