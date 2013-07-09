<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner extends CI_Model {

    public function getDetails($banner_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('banner_id', $banner_id);

        $banner = $this->db->get('banners');  	
        $banner = $banner->result_array();

        if($field == null){
            $banner[0]['custom_fields'] = $this->Custom_field->getValues('banners', $banner[0]['banner_id']);
            return $banner[0];
        }
        else{  	
            return $banner[0][$field];
        }

    }
    
    public function load($position, $all = true, $templates = array())
    {
        
        $banners = self::_getBanners($position);
 
        // get random only one banner and load only it
        if($all == false){
            $key = array_rand($banners);
            $banners = array($banners[$key]);
        }
        
        $banner_html = '';
        foreach($banners as $banner){
    
            $banner_html .= '<div class="banner banner_'.$banner['params']['type'].'" >';
            
            if($banner['show_title'] == 'yes'){
                $banner_html .= '<div class="title" >'.$banner['title'].'</div>';
            }

            switch($banner['params']['type']){
                case "image":
                    $banner_html .= self::_image($banner);
                break;
                case "flash":
                    $banner_html .= self::_flash($banner);
                break;
                case "html":
                    $banner_html .= self::_html($banner);
                break;
                case "link":
                    $banner_html .= self::_link($banner);
                break;                
            }                   
            
            $banner_html .= '</div>';
	    
	    $this->Banner->statistic($banner['id']);
                      
        }
  
        return $banner_html;
        
    }
    
    function _getBanners($position)
    {
        
        $this->db->select('*');
        $this->db->where('position', $position);
        $this->db->where('status', 'yes');
        $this->db->order_by('order', 'asc');
        $banners = $this->db->get('banners');  	
        $banners = $banners->result_array();           

        $banners_arr = array();
        foreach($banners as $banner){
            
            $banner['custom_fields'] = $this->Custom_field->getValues('banners', $banner['id']);
            
            /* --- check menus for module display --- */
            $banner['params'] = json_decode($banner['params'], true);            
            if($banner['params']['display_in'] == 'on_selected' && !in_array($this->menu_id, $banner['params']['display_menus'])){
                continue;
            }
            elseif($banner['params']['display_in'] == 'all_except_selected' && in_array($this->menu_id, $banner['params']['display_menus'])){
                continue;
            }
            
            
            /* --- check language for banner display --- */
            if($banner['show_in_language'] != NULL && $banner['show_in_language'] != $this->Language->getDetailsByAbbr(get_lang(), 'id')){
                continue;
            }
            
            
            /* --- check start end date for banner display --- */
            if($banner['start_publishing'] != NULL && $banner['start_publishing'] > date('Y-m-d')){
                continue;
            }
            elseif($banner['end_publishing'] != NULL && $banner['end_publishing'] < date('Y-m-d')){
                continue;
            }
            
            $banners_arr[] = $banner; 
            
        }
        
        return $banners_arr;
        
    }
    
    function _image($banner)
    {
	
        $banner_html = '';
	
	if(!empty($banner['params']['link'])){
	    $banner_html  .= '<a href="'.site_url($this->router->routes['default_controller'].'/banners/'.$banner['id']).'?url='.$banner['params']['link'].'" target="_blank" >';
	}
	
        $banner_html .= '    <img src="'.base_url($banner['params']['image']).'" >';
	
	if(!empty($banner['params']['link'])){
	    $banner_html .= '</a>';
	}
        
        return $banner_html;
        
    }
    
    function _flash($banner)
    {
        
        $banner_html  = '<object type="application/x-shockwave-flash"
                                 data="'.base_url($banner['params']['flash']).'"
                                 width="'.$banner['params']['width'].'" 
                                 height="'.$banner['params']['height'].'"
                                 border="0" >';
	$banner_html .= '    <param name="movie" value="'.base_url($banner['params']['flash']).'" >';
	$banner_html .= '    <param name="allowscriptaccess" value="always" />';
	$banner_html .= '</object>';
        
        return $banner_html;
        
    }
    
    function _html($banner)
    {
	
	$this->load->helper('simple_html_dom');
	
	$html = str_get_html($banner['params']['html']);
	foreach($html->find('a') as $key => $a){
                
	    $a->href = site_url($this->router->routes['default_controller'].'/banners/'.$banner['id']).'?url='.$a->href;
	    $html->find('a', $key)->href = $a->href;
	    $html->find('a', $key)->target = 'blank';
                        
        }
	
        return $html;
        
    }
    
    function _link($banner)
    {
	
        $banner_html = '';
	
	if(!empty($banner['params']['link'])){
	    $banner_html  .= '<a href="'.site_url($this->router->routes['default_controller'].'/banners/'.$banner['id']).'?url='.$banner['params']['link'].'" target="_blank" >';
	}
	
        $banner_html .=     $banner['params']['text'];
	
	if(!empty($banner['params']['link'])){
	    $banner_html .= '</a>';
	}
        
        return $banner_html;
        
    }
    
    function statistic($id, $type = 1)
    {
	
	$this->load->library('user_agent');
	
	# get user agent
	if ($this->agent->is_browser()){
	    $data['user_agent'] = $this->agent->browser().' '.$this->agent->version();
	}
	elseif ($this->agent->is_robot()){
	    $data['user_agent'] = $this->agent->robot();
	}
	elseif ($this->agent->is_mobile()){
	    $data['user_agent'] = $this->agent->mobile();
	}
	else{
	    $data['user_agent'] = 'Unidentified User Agent';
	}
	
	if($this->agent->is_referral()){
	    $data['user_referrer'] = $this->agent->referrer();
	}
	
	$data['page_url'] = $_SERVER['QUERY_STRING'] ? current_url().'?'.$_SERVER['QUERY_STRING'] : current_url();
	
	$data['banner_id'] = $id;
	$data['ip'] = $this->input->ip_address();
	$data['created_on'] = date('Y-m-d H:i:s');
	$data['type'] = $type;
	
	$this->db->insert('banners_statistics', $data);
	
    }
    
}