<?php
 
 $menus = $this->Adm_menu->getMainMenus();

 $menu = array();
 
 foreach($menus as $menu_d){
     
     $children_menus = $this->Adm_menu->getChildrenMenus($menu_d['id'], 0);
     
     $children_menu = array();
     foreach($children_menus as $children_menu_d){
         $children_menu[$children_menu_d['title_'.get_lang()]]   = $children_menu_d['alias']; 
     }
     
     if(count($children_menu) > 0){
        $menu[$menu_d['title_'.get_lang()]]   = array($menu_d['alias'] => $children_menu);
     }
     else{
        $menu[$menu_d['title_'.get_lang()]]   = $menu_d['alias']; 
     }
     
 }
  
 echo $this->menu_lib->create_menu($menu);