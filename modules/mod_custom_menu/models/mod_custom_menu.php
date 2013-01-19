<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_custom_menu extends CI_Model{
	
	  function run($module)
	  {
	  	
	  	  $data['menu_id'] = $this->menu_id;
                
        $menus = $module['params']['custom_menus'];
        
        $menus_arr = array();
        foreach($menus as $key => $menu){
            
            $menu = $this->Menu->getDetails($menu);
            
            /* --- check language for menu display --- */
            if($menu['language_id'] != NULL && $menu['language_id'] != $this->language_id){
                continue;
            }
            
            $menu['link']   = module::menu_link($menu);
            $menu['title']  = $menu['title_'.$this->lang_lib->get()];
            $menu['class']  = module::menu_class($menu);
            
            $menus_arr[] = $menu;
            
        }
                
        $data['menus'] = $menus_arr;
               
        return module::loadContent($module, $data);
	  	
	  }
    
}

