<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_script extends CI_Controller {
    
    public function index()
    {
	
	$tables = array('menus', 'modules', 'banners');
        
	foreach($tables as $table){
	    
	    $items = $this->db->get($table)->result_array();
	    
	    if(!array_key_exists('type', $items[0])){
		$this->db->query('ALTER TABLE `'.$table.'` ADD `type` VARCHAR( 50 ) NOT NULL');
	    }
	    else{
		continue;
	    }
	    
	    foreach($items as $item){
		
		$params = json_decode($item['params'], true);		
		if(!isset($params['type'])){
		    continue;
		}
		
		$data['type'] = $params['type'];
		unset($params['type']);
		$data['params'] = json_encode($params);
			
		$this->db->where('id', $item['id']);
		$this->db->update($table, $data);
		
	    }
	    
	}
	
	echo "done!";
	
    }
    
    function revert()
    {
	
	$tables = array('menus', 'modules', 'banners');
	
	foreach($tables as $table){
	    
	    $items = $this->db->get($table)->result_array();
	    
	    if(array_key_exists('type', $items[0])){
		$this->db->query('ALTER TABLE `'.$table.'` DROP COLUMN `type`');
	    }
	    else{
		continue;
	    }
	    
	    foreach($items as $item){
		
		if(!isset($item['type'])){
		    continue;
		}
		
		$params = json_decode($item['params'], true);		
		$params['type'] = $item['type'];
		
		$data['params'] = json_encode($params);
			
		$this->db->where('id', $item['id']);
		$this->db->update($table, $data);
		
	    }
	    
	}
	
	echo "done!";
	
    }
    
    function add_menus_show_title()
    {
	
	$menus = $this->db->get('menus')->result_array();
	    
	if(!array_key_exists('show_title', $menus[0])){   
	    $this->db->query("ALTER TABLE `menus` ADD `show_title` ENUM( 'yes', 'no' ) NOT NULL");
	    $this->db->query("UPDATE `menus` SET `show_title` = 'yes'");
	}
	
	echo "done!";
	
    }
    
    function remove_menus_show_title()
    {
	
	$menus = $this->db->get('menus')->result_array();
	    
	if(array_key_exists('show_title', $menus[0])){
	    $this->db->query('ALTER TABLE `menus` DROP COLUMN `show_title`');
	}
	
	echo 'done!';
	
    }
    
    
    
}