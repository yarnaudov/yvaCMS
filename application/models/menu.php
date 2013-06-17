<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Model {
    
    private $menus = array();
    
    public function getDetails($id, $field = null)
    {

        $query = "SELECT 
                      *
                    FROM
                      menus m
                      LEFT JOIN menus_data md ON (m.id = md.menu_id AND md.language_id = '".$this->language_id."')
                    WHERE
                      m.id = '".$id."' ";
        
        $menu = $this->db->query($query);  	
        $menu = $menu->result_array();

        if(empty($menu)){
            return;
        }
        
        $menu[0]['params'] = json_decode($menu[0]['params'], true);   
        $menu[0]           = array_merge($menu[0], $this->Custom_field->getValues('menus', $id));

        if($field == null){
            return $menu[0];
        }
        else{  	
            return $menu[0][$field];
        }

    }    
    
    public function getByAlias($alias, $field = null)
    {

        $query = "SELECT 
                      *
                    FROM
                      menus m
                      LEFT JOIN menus_data md ON (m.id = md.menu_id AND md.language_id = '".$this->language_id."')
                    WHERE
                      m.alias = '".$alias."' ";
        
        $menu = $this->db->query($query);  	
        $menu = $menu->result_array();

        if(empty($menu)){
            return;
        }
        
        $menu[0]['params'] = json_decode($menu[0]['params'], true);   
        $menu[0]           = array_merge($menu[0], $this->Custom_field->getValues('menus', $menu[0]['id']));

        if($field == null){
            return $menu[0];
        }
        else{  	
            return $menu[0][$field];
        }

    }
    
    public function getDefault($field = null)
    {

        $this->db->select('id');
        $this->db->where('default', 'yes');
        $menu = $this->db->get('menus');
        $menu = $menu->result_array();

        if(empty($menu)){
            return;
        }
        
        $menu[0] = self::getDetails($menu[0]['id']); 
        
        if($field == null){
            return $menu[0];
        }
        else{  	
            return $menu[0][$field];
        }

    }
    
    public function getByCategory($category_id)
    {
        
        $this->menus = array();
        
        $this->db->select('id');
        $this->db->where('category_id', $category_id);
        $this->db->where('status', 'yes');
        $this->db->where('parent_id IS NULL', NULL);
        $this->db->order_by('order', 'asc');
        $menus = $this->db->get('menus');  	
        $menus = $menus->result_array();
 	
        foreach($menus as $menu){
            
            $menu = self::getDetails($menu['id']);
            
            $menu['lavel'] = 1;
            $this->menus[] = $menu;
            
            if(self::checkChildren($menu['id']) == true){
                self::getChildren($menu['id'], 2);
            }
            
        }
        
        return $this->menus;

    }
    
    public function checkChildren($menu_id)
    {
        
        $this->db->select('*');
        $this->db->where('parent_id', $menu_id);
        $this->db->where('status', 'yes');
        $menus = $this->db->get('menus');  	
        $menus = $menus->result_array();
        
        if(count($menus) > 0){
            return true;
        }
        else{
            return false;
        }
        
    }
    
    public function getChildren($menu_id, $lavel)
    {
        
        $this->db->select('id');
        $this->db->where('parent_id', $menu_id);
        $this->db->where('status', 'yes');
        $this->db->order_by('order', 'asc');
        $menus = $this->db->get('menus');  	
        $menus = $menus->result_array();

        foreach($menus as $menu){
            
            $menu = self::getDetails($menu['id']);
            
            $menu['lavel'] = $lavel;
            $this->menus[] = $menu;
            
            if(self::checkChildren($menu['id']) == true){
                self::getChildren($menu['id'], ($lavel+1));
            }
            
        }
        
    }
    
    public function getParents($menu_id)
    {
        $menus[] = $menu_id;
        
        $menu = self::getDetails($menu_id);
        
        $has_parent = true;
        while($has_parent == true){

            if($menu['parent_id'] == NULL){
               $has_parent = false;
               break;
            }
            
            $this->db->select('*');
            $this->db->where('id', $menu['parent_id']);
            $menu = $this->db->get('menus');  	
            $menu = $menu->result_array();
            
            $menu = $menu[0];
            
            $menus[] = $menu['id'];
        
        }
        
        return $menus;
        
    }
    
    public function getByArticle($article_id)
    {
        
        $query = "SELECT 
                      id
                    FROM
                      menus
                    WHERE
                      status = 'yes'";
        
        $menus = $this->db->query($query);   	
        $menus = $menus->result_array();
        
        foreach($menus as $menu){
        
            $menu = self::getDetails($menu['id']);
            
            if(isset($menu['params']['article_id']) && $menu['params']['article_id'] == $article_id){
                return $menu;
            }
            
        }
        
        return;
        
    }
    
}