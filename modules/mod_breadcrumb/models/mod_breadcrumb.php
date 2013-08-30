<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_breadcrumb extends CI_Model{
	
    function run($module)
    {
	
	if(isset($module['params']['text'][$this->language_id])){
	    $data['text']      = $module['params']['text'][$this->language_id];
	}
	
        $data['separator'] = $module['params']['separator'];
        
        $menus_arr = array();
        foreach($this->current_menus as $menu){
         
            $menu = $menu = $this->Menu->getDetails($menu);
            
            /* --- check language for menu display --- */
            if($menu['show_in_language'] != NULL && $menu['show_in_language'] != $this->language_id){
                continue;
            }
            
            /* --- check default menu if yes skip --- */
            if($menu['default'] == 'yes'){
                continue;
            }
            
            $menu['link']   = module::menu_link($menu);
            $menu['class']  = module::menu_class($menu);
            
            $menus_arr[] = $menu;
            
        }
        
        if($this->menu_id == 'search'){ // stupid fix for search component to work with no menu assigned to it
            $menus_arr[]['title']  = lang('label_search');
        }
        
        krsort($menus_arr);
        $data['menus'] = $menus_arr;
        
        $data['article_alias'] = $this->article_alias;
               
        return module::loadContent($module, $data);
	  	
    }
    
}

