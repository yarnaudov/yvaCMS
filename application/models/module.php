<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends CI_Model {
    
    public $templates = array('main' => 'modules/main');
    
    public function getDetails($id, $field = null)
    {

        $query = "SELECT 
                      *
                    FROM
                      modules m
                      LEFT JOIN modules_data md ON (m.id = md.module_id AND md.language_id = '".$this->language_id."')
                    WHERE
                      m.id = '".$id."' ";
        
        $module = $this->db->query($query);  	
        $module = $module->result_array();

        $module[0]['params'] = json_decode($module[0]['params'], true); 
        $module[0]           = array_merge($module[0], $this->Custom_field->getValues($id, 'modules'));
        
        if(empty($module)){
            return;
        }

        if($field == null){
            return $module[0];
        }
        else{  	
            return $module[0][$field];
        }

    }    
    
    public function load($position, $templates = array())
    {        
        
        $templates = array_merge($this->templates, $templates);
                
        $modules = self::_getModules($position);

        $module_html = '';
        foreach($modules as $module){
            
            $module['template'] = isset($templates[$module['params']['type']]) ? $templates[$module['params']['type']] : "";
            
            $content = Module::_load_module($module['id']);
                
            $module_html .= $this->load->view($templates['main'], compact('module', 'content'), true); 
                      
        }

        return $module_html;
        
    }
    
    function _getModules($position)
    {
        
        if(empty($position)){
            return array();
        }
        
        $this->db->select('id');
        $this->db->where('position', $position);
        $this->db->where('status', 'yes');
        $this->db->order_by('order', 'asc');
        $modules = $this->db->get('modules');  	
        $modules = $modules->result_array();           

        $modules_arr = array();
        foreach($modules as $module){
                       
            $module = $this->Module->getDetails($module['id']);            
            
            /* --- check menus for module display --- */          
            if($module['params']['display_in'] == 'on_selected' && !in_array($this->menu_id, $module['params']['display_menus'])){
                continue;
            }
            elseif($module['params']['display_in'] == 'all_except_selected' && in_array($this->menu_id, $module['params']['display_menus'])){
                continue;
            }            
            
            /* --- check language for module display --- */
            if($module['show_in_language'] != NULL && $module['show_in_language'] != $this->Language->getDetailsByAbbr(get_lang(), 'id')){
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
        switch($menu['params']['type']){
            case "article":
            case "articles_list":
            case "component":
            case "menu":
                return site_url($menu['alias']);
            break;
            case "external_url":
                return $menu['params']['url'];
            break;
        }
        
    }
    
    function menu_class($menu)
    {
        
        $class = '';
        if(in_array($menu['id'], $this->current_menus)){
            $class = 'current';
        }
        
        return $class;
        
    }
    
    function loadContent($module, $data = array())
    {
    	
    	ob_start();
    	extract($data);
    	if(empty($module['template'])){
            include 'modules/' . $module['params']['type'] . '/views/' . $module['params']['type'] . '.php';
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

        if(file_exists('modules/' . $module['params']['type'] . '/models/' . $module['params']['type'] . '.php')){

            include_once 'modules/' . $module['params']['type'] . '/models/' . $module['params']['type'] . '.php';

            $moduleObj = new $module['params']['type'];
            $content   = $moduleObj->run($module);

        }
        
        return $content;
        
    }
    
}