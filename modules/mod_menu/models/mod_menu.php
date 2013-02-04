<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_menu extends CI_Model{
	
    function run($module)
    {
	  	
        $menus = $this->Menu->getByCategory($module['params']['category_id']);
        
        $menus_arr = array();
        foreach($menus as $key => $menu){
            
            /* --- check language for menu display --- */
            if($menu['show_in_language'] != NULL && $menu['show_in_language'] != $this->language_id){
                continue;
            }
                          
            $menu['link']  = module::menu_link($menu);
            $menu['class'] = module::menu_class($menu);
            
            if(isset($menu['params']['image']) && !empty($menu['params']['image'])){
                $menu['image'] = $menu['params']['image'];
            }
            
            $menus_arr[] = $menu;
            
        }
                
        $data['menus'] = $menus_arr;
                	  
        return module::loadContent($module, $data);
	  	
    }
    
}

