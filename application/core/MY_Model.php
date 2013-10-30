<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Model extends CI_Model {
	
	function check_item_display($item)
	{
		
		/* --- check status --- */          
		if(isset($item['status']) && $item['status'] != 'yes'){
			return false;
		}
		
		/* --- check menus --- */          
		if(isset($item['params']['display_in']) && $item['params']['display_in'] == 'on_selected' && !in_array($this->menu_id, $item['params']['display_menus'])){
			return false;
		}
		elseif(isset($item['params']['display_in']) && $item['params']['display_in'] == 'all_except_selected' && in_array($this->menu_id, $item['params']['display_menus'])){
			return false;
		}            

		/* --- check language --- */
		if(isset($item['show_in_language']) && $item['show_in_language'] != NULL && $item['show_in_language'] != $this->Language->getDetailsByAbbr(get_lang(), 'id')){
			return false;
		}

		/* --- check start end date --- */
		if(isset($item['start_publishing']) && $item['start_publishing'] != NULL && $item['start_publishing'] > date('Y-m-d')){
			return false;
		}
		elseif(isset($item['end_publishing']) && $item['end_publishing'] != NULL && $item['end_publishing'] < date('Y-m-d')){
			return false;
		}

		/* --- check display rules --- */
		$display_rules = isset($item['params']['display_rules']) ? $item['params']['display_rules'] : array();
		$continue = true;
		foreach($display_rules as $display_rule){
			if(@preg_match('/'.$display_rule.'/', current_url())){
				$continue = false; 
			}
		}
		if($continue == true && count($display_rules) > 0){
			return false;
		}
		
		return true;
		
	}
    
}