<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Model {
    
    private $menus = array();
    
    public function getDetails($menu_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('menu_id', $menu_id);

        $menu = $this->db->get('menus');  	
        $menu = $menu->result_array();
        
        if(empty($menu)){
            return '';
        }
        
        $menu[0]['params'] = @json_decode($menu[0]['params'], true);
        
        if($field == null){
            $menu[0]['custom_fields'] = $this->Custom_field->getValues('menus', $menu[0]['menu_id']);
            return $menu[0];
        }
        else{  	
            return $menu[0][$field];
        }

    }    
    
    public function getByAlias($alias, $field = null)
    {

        $this->db->select('*');
        $this->db->where('alias',  $alias);
        $this->db->where('status', 'yes');
        $menu = $this->db->get('menus');  	
        $menu = $menu->result_array();

        if(empty($menu)){
            return '';
        }
        
        if($field == null){
            $menu[0]['custom_fields'] = $this->Custom_field->getValues('menus', $menu[0]['menu_id']);
            return $menu[0];
        }
        else{  	
            return $menu[0][$field];
        }

    }
    
    public function getDefault($field = null)
    {

        $this->db->select('*');
        $this->db->where('default', 'yes');
        $menu = $this->db->get('menus');  	
        $menu = $menu->result_array();

        if($field == null){
            $menu[0]['custom_fields'] = $this->Custom_field->getValues('menus', $menu[0]['menu_id']);
            return $menu[0];
        }
        else{  	
            return $menu[0][$field];
        }

    }
    
    public function getByCategory($category_id)
    {
        
        $this->menus = array();
        
        $this->db->select('*');
        $this->db->where('category_id', $category_id);
        $this->db->where('status', 'yes');
        $this->db->where('parent_id IS NULL', NULL);
        $this->db->order_by('order', 'asc');
        $menus = $this->db->get('menus');  	
        $menus = $menus->result_array();
 	
        foreach($menus as $menu){
            
            $menu['custom_fields'] = $this->Custom_field->getValues('menus', $menu['menu_id']);
            
            $menu['lavel'] = 1;
            $this->menus[] = $menu;
            
            if(self::checkChildren($menu['menu_id']) == true){
                self::getChildren($menu['menu_id'], 2);
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
        
        $this->db->select('*');
        $this->db->where('parent_id', $menu_id);
        $this->db->where('status', 'yes');
        $this->db->order_by('order', 'asc');
        $menus = $this->db->get('menus');  	
        $menus = $menus->result_array();

        foreach($menus as $menu){
            
            $menu['lavel'] = $lavel;
            $this->menus[] = $menu;
            
            if(self::checkChildren($menu['menu_id']) == true){
                self::getChildren($menu['menu_id'], ($lavel+1));
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
            $this->db->where('menu_id', $menu['parent_id']);
            $menu = $this->db->get('menus');  	
            $menu = $menu->result_array();
            
            $menu = $menu[0];
            
            $menus[] = $menu['menu_id'];
        
        }
        
        return $menus;
        
    }
    
    public function getByArticle($article_id)
    {
        
        $query = "SELECT 
                      menu_id
                    FROM
                      menus
                    WHERE
                      status = 'yes'
                     AND
                      (type = 'article'
                        OR
                       type = 'component')";
        
        $menus = $this->db->query($query);   	
        $menus = $menus->result_array();
        
        foreach($menus as $menu){
        
            $menu = self::getDetails($menu['menu_id']);
            
            if($menu['params']['article_id'] == $article_id){
                return $menu;
            }
            
        }
        
        return;
        
    }
    
}