<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adm_menu extends CI_Model {
	
    function setConfig()
    {
    	
        $no_login = $this->config->item('no_login');
        
        $menus = $this->db->query("SELECT 
                                       * 
                                    FROM 
                                       adm_menus 
                                    WHERE
                                       check_access = 0
                                    ORDER BY `order`");
        $menus = $menus->result_array();
        
        foreach($menus as $menu){            
            if($menu['parent_id'] != null){
              $main_menu = self::getDetails($menu['parent_id']);
              if(isset($this->access[$main_menu['alias']]) && $this->access[$main_menu['alias']] == 'on'){
                  $no_login[] = $menu['alias'];
              }              
            }
            else{
                $no_login[] = $menu['alias'];
            }
        }
        
        $this->config->set_item('no_login', $no_login);
    	
    }
    
    public function getDetails($menu_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('id', $menu_id);

        $menu = $this->db->get('adm_menus');  	
        $menu = $menu->result_array();

        if($field == null){
                return $menu[0];
        }
        else{  	
            return $menu[0][$field];
        }

    }
    
    public function getDetailsByAlias($alias, $field = null)
    {

        $this->db->select('*');
        $this->db->where('alias', $alias);

        $menu = $this->db->get('adm_menus');  	
        $menu = $menu->result_array();

        if($field == null){
                return $menu[0];
        }
        else{  	
            return $menu[0][$field];
        }

    }
    
    public function getMainMenus($check_access = null, $check_user_access = 'yes')
    {
        
        $menus = $this->db->query("SELECT 
                                       * 
                                    FROM 
                                       adm_menus 
                                    WHERE 
                                       parent_id is NULL
                                       ".($check_access != null ? "AND check_access = '".$check_access."' " : "")."
                                    ORDER BY `order`");
        $menus = $menus->result_array();
        
        $menus_arr = array();
        foreach($menus as $menu){
            if(@$this->access[$menu['alias']] == 'on' || $check_user_access == 'no'){
                $menus_arr[] = $menu; 
            }
        }
                
        return $menus_arr;
        
    }
    
    public function getChildrenMenus($parent_id, $submenu, $check_access = null, $check_user_access = 'yes')
    {
        
        $menus = $this->db->query("SELECT 
                                      * 
                                    FROM 
                                      adm_menus 
                                    WHERE 
                                      parent_id = '".$parent_id."' 
                                    AND 
                                      submenu = '".$submenu."' 
                                      ".($check_access != null ? "AND check_access = '".$check_access."' " : "")."
                                    ORDER BY `order`");
        $menus = $menus->result_array();
        //echo print_r($this->config->item('no_login'))."<---<br/>";
        $menus_arr = array();
        foreach($menus as $menu){
            if(@$this->access[$menu['alias']] == 'on' || in_array($menu['alias'], $this->config->item('no_login')) || $check_user_access == 'no'){
                $menus_arr[] = $menu; 
            }
        }
               
        return $menus_arr;
        
    }
    
    public function getComponents()
    {
        
       $menus = $this->db->query("SELECT 
                                       * 
                                    FROM 
                                       adm_menus 
                                    WHERE 
                                       component = 1
                                    ORDER BY `order`");
        $menus = $menus->result_array();
        
        return $menus; 
        
    }
    
    public function getAllMenus()
    {
    	
    	  $this->db->select('*');

        $menus = $this->db->get('adm_menus');  	
        $menus = $menus->result_array();
    	  
    	  $menus_arr = array();
    	  foreach($menus as $menu){
    	  	$menus_arr[$menu['alias']] = 'on';
    	  }
    	  
    	  return $menus_arr;
    	  
    }
    
}