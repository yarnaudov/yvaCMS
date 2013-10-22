<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Update_script extends CI_Controller {
    
    public $language_id;
    
    function index()
    {
	
	$methods = get_class_methods('Update_script');
	
	unset($methods[0]);
	array_pop($methods);
	array_pop($methods);
	
	$html = "<h2>Actions</h2>\n";
	$html .= "<ul>\n";
	foreach($methods as $method){
	    
	    $html .= "<li><a href=\"".site_url('update_script/'.$method)."\" >".$method."</a></li>\n";
	    
	}
	$html .= "</ul>\n";
	
	$this->load->view('layouts/simple', array('content' => $html));
	
    }
    
    function add_type()
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
    
    function remove_type()
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
    
    function add_ap_sessions_table()
    {
	
	$this->db->query("CREATE TABLE IF NOT EXISTS `ap_sessions` (
			    `user_id` int(4) NOT NULL,
			    `ip_address` varchar(45) NOT NULL DEFAULT '0',
			    `user_agent` varchar(120) NOT NULL,
			    `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
			    `user_data` text NOT NULL,
			    UNIQUE KEY `user_id` (`user_id`),
			    KEY `last_activity_idx` (`last_activity`),
			    KEY `user_id_2` (`user_id`)
			  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	
	echo 'done!';
	
    }
    
    function remove_ap_sessions_table()
    {
	
	$this->db->query("DROP TABLE IF EXISTS `ap_sessions`");
	
	echo 'done!';
	
    }
    
    function add_multiple_categories()
    {
	
	$this->db->query("CREATE TABLE IF NOT EXISTS `articles_categories` (
			    `article_id` int(4) NOT NULL,
			    `category_id` int(4) NOT NULL,
			    `order` int(4) NOT NULL,
			    UNIQUE KEY `article_category` (`article_id`,`category_id`),
			    KEY `article_id` (`article_id`),
			    KEY `category_id` (`category_id`)
			  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;	");
	
	$this->db->query("ALTER TABLE `articles_categories`
	                    ADD CONSTRAINT `articles_categories_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON UPDATE CASCADE,
			    ADD CONSTRAINT `articles_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;");

    
	$articles = $this->db->get('articles')->result_array();
	
	foreach($articles as $article){
	    
	    $category['article_id']  = $article['id'];
	    $category['category_id'] = $article['category_id'];
	    $category['order']       = $article['order'];
	    
	    $query = $this->db->insert_string('articles_categories', $category);
	    //echo $query."<---<br/>";
	    $this->db->query($query);
	     
	}
	
	$this->db->query("ALTER TABLE `articles` DROP FOREIGN KEY `articles_ibfk_3`;");
	$this->db->query("ALTER TABLE `articles` DROP `category_id`");
	$this->db->query("ALTER TABLE `articles` DROP `order`");
	
	$this->db->query("ALTER TABLE `articles_history` DROP FOREIGN KEY `articles_history_ibfk_2`;");
	$this->db->query("ALTER TABLE `articles_history` DROP `category_id`");
	$this->db->query("ALTER TABLE `articles_history` DROP `order`");
	$this->db->query("ALTER TABLE `articles_history` ADD `categories` TEXT NOT NULL AFTER `status`");
	
	echo "done!";
	
    }
    
    function remove_multiple_categopries()
    {
	
	echo "this is not completed yet!";
	
    }
    
    function add_articles_meta_data()
    {
	
	
	$this->db->query("ALTER TABLE `articles_data` ADD `meta_keywords` VARCHAR( 1000 ) NOT NULL ,
			                              ADD `meta_description` VARCHAR( 1000 ) NOT NULL ");
		
	echo "done!";
	
    }
    
    function remove_articles_meta_data()
    {
		
	$this->db->query("ALTER TABLE `articles_data` DROP `meta_keywords`,
			                              DROP `meta_description`");
	
	echo "done!";
	
    }
    
    function add_media_menu()
    {
	
	$this->db->query("INSERT INTO `ap_menus` (`title_bg`, `title_en`, `alias`, `parent_id`, `type`, `component`, `check_access`, `order`)
					                  VALUES ('Медия', 'Media', 'media', NULL , 'general', 'no', 'yes', '6');");
	
	$id = $this->db->insert_id();
	
	$this->db->query("INSERT INTO `ap_menus` (`title_bg`, `title_en`, `alias`, `parent_id`, `type`, `component`, `check_access`, `order`)
									  VALUES ('Разглеждане', 'Browse', 'media/index', '".$id."', 'sub_action', 'no', 'yes', '1'),
											 ('Настройки', 'Settings', 'media/settings', '".$id."', 'sub_action', 'no', 'yes', '2');");
		
	echo "done!";
	
    }
    
    function remove_media_menu()
    {
	
	$this->db->query("DELETE FROM `ap_menus` WHERE `alias` LIKE 'media%';");
		
	echo "done!";
	
    }
    
    
    
}