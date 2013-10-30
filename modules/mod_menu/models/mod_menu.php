<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_menu extends MY_Model{
	
    function run($module)
    {
	  	
        $menus = $this->Menu->getByCategory($module['params']['category_id']);
        
        $menus_arr = array();
        foreach($menus as $key => $menu){
                  
			if(parent::check_item_display($menu)){
				$menu['link']  = module::menu_link($menu);
				$menu['class'] = module::menu_class($menu);            
				$menus_arr[] = $menu;
			}
			
        }
                
        $data['menus'] = $menus_arr;
                	  
        return module::loadContent($module, $data);
	  	
    }
    
}

