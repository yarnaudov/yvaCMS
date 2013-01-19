<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends CI_Model {
    
    public $templates = array('main' => 'modules/main');
    
    public function getDetails($module_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('module_id', $module_id);

        $module = $this->db->get('modules');  	
        $module = $module->result_array();
        
        $module[0]['params'] = @json_decode($module[0]['params'], true);
        
        if($field == null){
            $module[0]['custom_fields'] = $this->Custom_field->getValues('modules', $module[0]['module_id']);
            return $module[0];
        }
        else{  	
            return $module[0][$field];
        }

    }    
    
    public function load($category_alias, $templates = array())
    {        
        
        $templates = array_merge($this->templates, $templates);
                
        $modules = self::_getModules($category_alias);
        
        $module_html = '';
        foreach($modules as $module){
            
            $module['template'] = isset($templates[$module['type']]) ? $templates[$module['type']] : "";
            
            $content = Module::_load_module($module['module_id']);
                
            $module_html .= $this->load->view($templates['main'], compact('module', 'content'), true); 
                      
        }
        
        return $module_html;
        
    }
    
    function _getModules($category_alias)
    {
        
        $category_id = @$this->Category->getByAlias($category_alias, 'modules', 'category_id');
        
        if(empty($category_id)){
            return array();
        }
        
        $this->db->select('*');
        $this->db->where('category_id', $category_id);
        $this->db->where('status', 'yes');
        $this->db->order_by('order', 'asc');
        $modules = $this->db->get('modules');  	
        $modules = $modules->result_array();           

        $modules_arr = array();
        foreach($modules as $module){
            
            $module['custom_fields'] = $this->Custom_field->getValues('modules', $module['module_id']);
            
            /* --- check menus for module display --- */
            $module['params'] = json_decode($module['params'], true);            
            if($module['display_in'] == 'on_selected' && !in_array($this->menu_id, $module['params']['display_menus'])){
                continue;
            }
            elseif($module['display_in'] == 'all_except_selected' && in_array($this->menu_id, $module['params']['display_menus'])){
                continue;
            }
            
            
            /* --- check language for module display --- */
            if($module['language_id'] != NULL && $module['language_id'] != $this->Language->getDetailsByAbbr(get_lang(), 'language_id')){
                continue;
            }
            
            
            /* --- check start end date for module display --- */
            if($module['start_publishing'] != NULL && $module['start_publishing'] > date('Y-m-d')){
                continue;
            }
            elseif($module['end_publishing'] != NULL && $module['end_publishing'] < date('Y-m-d')){
                continue;
            }
            
            $modules_arr[] = $module; 
            
        }

        return $modules_arr;
        
    }
    
    function menu_link($menu)
    {
        
        /* --- get menu link --- */            
        switch($menu['type']){
            case "article":
            case "articles_list":
            case "component":
            case "menu":
                $link = site_url($menu['alias']);
            break;
            case "external_url":
                $link = $menu['params']['url'];
            break;
        }
        
        return $link;
        
    }
    
    function menu_class($menu)
    {
        
        $class = '';
        if(in_array($menu['menu_id'], $this->current_menus)){
            $class = 'current';
        }
        
        return $class;
        
    }
    
    function loadContent($module, $data = array())
    {
    	
    	ob_start();
    	extract($data);
    	if(empty($module['template'])){
            include 'modules/' . $module['type'] . '/views/' . $module['type'] . '.php';
        }
        else{
            include $module['template'];
        }
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    	
    }
    
    function _load_module($id)
    {
        
        $content = '';
        
        $module = Module::getDetails($id);

        if(file_exists('modules/' . $module['type'] . '/models/' . $module['type'] . '.php')){

            include_once 'modules/' . $module['type'] . '/models/' . $module['type'] . '.php';

            $moduleObj = new $module['type'];
            $content   = $moduleObj->run($module);

        }
        
        return $content;
        
    }
    
}