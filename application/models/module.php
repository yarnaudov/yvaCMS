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
    
    public function load($position, $type = 1)
    {
	
	$templates = $this->templates;
	if(isset($this->templates[$position])){
	    $templates = array_merge($this->templates, $this->templates[$position]);
	}
                
        $modules = self::_getModules($position, $type);

        $module_html = '';
        foreach($modules as $module){
            
	    if($module['template'] == 'default'){
		$module['template'] = isset($templates[$module['type']]) ? $templates[$module['type']] : "";
	    }
       
            $content = $this->Module->_load_module($module);
                
            $module_html .= $this->load->view($templates['main'], compact('module', 'content'), true); 
                      
        }

        return $module_html;
        
    }
    
    function _getModules($position, $type = 1)
    {
        
        if(empty($position)){
            return array();
        }
        
        
        $this->db->select('id');        
        if($type == 1){
            $this->db->where('position', $position);
        }
        else{
            $this->db->where('id', $position);
        }
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
            
            /* --- check display rules for module --- */
            $display_rules = isset($module['params']['display_rules']) ? $module['params']['display_rules'] : array();
            $continue = true;
            foreach($display_rules as $display_rule){
                if(@preg_match('/'.$display_rule.'/', current_url())){
                    $continue = false; 
                }
            }
            if($continue == true && count($display_rules) > 0){
                continue;
            }
            
            $modules_arr[] = $module; 
            
        }

        return $modules_arr;
        
    }
    
    function menu_link($menu, $full = true)
    {
        
        if(preg_match('/^components{1}/', $menu['type'])){
            $menu['type'] = "component";
        }
        
        /* --- get menu link --- */            
        switch($menu['type']){
            case "article":
            case "articles_list":           
	    case "custom_articles_list":
            case "menu":
            case "component":
	    case "sitemap":
                
                $link = '';
                
                $menus = $this->Menu->getParents($menu['id']);
                $menus = array_reverse($menus);
                foreach($menus as $menu_id){
                    $menu = $this->Menu->getDetails($menu_id);
                    $link .= '/'.$menu['alias'];
                }
        
                if($full == true){
                    return site_url($link);
                }
                else{
                    return $link;
                }
                
            break;
            case "external_url":
		
		if(!preg_match('/^http:\/\//', $menu['params']['url']) &&
		   !preg_match('/^https:\/\//', $menu['params']['url']) &&
		   !preg_match('/^mailto:/', $menu['params']['url'])){
		    $menu['params']['url'] = site_url($menu['params']['url']);
		}
                
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
        
        $template_file = TEMPLATES_DIR.'/'.$this->Setting->getTemplate('main').'/'.$module['template'].'.php';

        if(file_exists(FCPATH.$template_file)){
            include $template_file;
        }
    	else{
            include 'modules/' . $module['type'] . '/views/' . $module['type'] . '.php';
        }
        
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    	
    }
    
    function _load_module($module)
    {
        
        $content = '';

        if(file_exists('modules/' . $module['type'] . '/models/' . $module['type'] . '.php')){

            include_once 'modules/' . $module['type'] . '/models/' . $module['type'] . '.php';
            
            $moduleObj = new $module['type'];
            $content   = $moduleObj->run($module);

        }
        
        return $content;
        
    }
    
    function count($position)
    {
        
        $modules = self::_getModules($position);
        
        return count($modules);
        
    }
    
}