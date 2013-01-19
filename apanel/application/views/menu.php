<?php

/*
 $menu[] = anchor('articles',   lang('label_articles'));
 $menu[] = anchor('menus',      lang('label_menus'));
 $menu[] = anchor('images',     lang('label_images'));
 $menu[] = anchor('banners',    lang('label_banners'));
 $menu[] = anchor('languages',  lang('label_languages'));
 $menu[] = anchor('users',      lang('label_users'));
 $menu[] = anchor('modules',    lang('label_modules'));
 $menu[] = anchor('components', lang('label_components'));
 

 $menu = array();

 $menu[] = array('articles',   lang('label_articles'));
 $menu[] = array('menus',      lang('label_menus'));
 $menu[] = array('images',     lang('label_images'));
 $menu[] = array('banners',    lang('label_banners'));
 $menu[] = array('languages',  lang('label_languages'));
 $menu[] = array('users',      lang('label_users'));
 $menu[] = array('modules',    lang('label_modules'));
 $menu[] = array(array('contact_forms', 'Contact forms'), lang('label_components'));
 $menu[] = array('settings',   lang('label_settings'));
 
 echo $this->menu_lib->create($menu);
 
 */
 
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
  
 //$menu[lang('label_articles')]   = 'articles';
 //$menu[lang('label_menus')]      = 'menus';
 //$menu[lang('label_images')]     = 'images';
 //$menu[lang('label_banners')]    = 'banners';
 //$menu[lang('label_languages')]  = 'languages';
 //$menu[lang('label_users')]      = 'users';
 //$menu[lang('label_modules')]    = 'modules';
 
 //$menu[lang('label_components')] = array('Contact forms' => 'contact_forms');
 
 //$menu[lang('label_settings')]   =  'settings';
  
 echo $this->menu_lib->create_menu($menu);