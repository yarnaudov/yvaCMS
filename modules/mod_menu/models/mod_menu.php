<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_menu extends CI_Model{
	
	  function run($module)
	  {
	  	
	  	  $menus = $this->Menu->getByCategory($module['params']['category_id']);
        
        $menus_arr = array();
        foreach($menus as $key => $menu){
            
            $menu['params'] = json_decode($menu['params'], true);
            
            /* --- check language for menu display --- */
            if($menu['language_id'] != NULL && $menu['language_id'] != $this->language_id){
                continue;
            }
                       
            
            
            $menu['link']   = module::menu_link($menu);
            $menu['title']  = $menu['title_'.$this->lang_lib->get()];
            $menu['class']  = module::menu_class($menu);
            if(isset($menu['params']['image']) && !empty($menu['params']['image'])){
                $menu['image'] = $menu['params']['image'];
            }
            
            $menus_arr[] = $menu;
            
        }
                
        $data['menus'] = $menus_arr;
                	  
    	  return module::loadContent($module, $data);
	  	
	  }
    
}

