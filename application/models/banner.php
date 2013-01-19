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
    
    public function load($category_alias, $all = true)
    {
        
        $banners = self::_getBanners($category_alias);
        
        // get random only one banner and load only it
        if($all == false){
            $key = array_rand($banners);
            $banners = array($banners[$key]);
        }
        
        $banner_html = '';
        foreach($banners as $banner){
            
            $banner_html .= '<div class="banner banner_'.$banner['type'].'" >';
            
            if($banner['show_title'] == 'yes'){
                $banner_html .= '<div class="title" >'.$banner['title'].'</div>';
            }

            switch($banner['type']){
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
                      
        }
        
        return $banner_html;
        
    }
    
    function _getBanners($category_alias)
    {
        
        $category_id = @$this->Category->getByAlias($category_alias, 'banners', 'category_id');
        
        if(empty($category_id)){
            return array();
        }
        
        $this->db->select('*');
        $this->db->where('category_id', $category_id);
        $this->db->where('status', 'yes');
        $this->db->order_by('order', 'asc');
        $banners = $this->db->get('banners');  	
        $banners = $banners->result_array();           

        $banners_arr = array();
        foreach($banners as $banner){
            
            $banner['custom_fields'] = $this->Custom_field->getValues('banners', $banner['banner_id']);
            
            /* --- check menus for module display --- */
            $banner['params'] = json_decode($banner['params'], true);            
            if($banner['display_in'] == 'on_selected' && !in_array($this->menu_id, $banner['params']['display_menus'])){
                continue;
            }
            elseif($banner['display_in'] == 'all_except_selected' && in_array($this->menu_id, $banner['params']['display_menus'])){
                continue;
            }
            
            
            /* --- check language for banner display --- */
            if($banner['language_id'] != NULL && $banner['language_id'] != $this->Language->getDetailsByAbbr(get_lang(), 'language_id')){
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
        
        $banner_html  = '<a href="'.$banner['params']['link'].'" target="blank" >';
        $banner_html .= '    <img src="'.base_url($banner['params']['image']).'" >';
        $banner_html .= '</a>';
        
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
  
        return $banner['params']['html'];
        
    }
    
    function _link($banner)
    {
        
        $banner_html  = '<a href="'.$banner['params']['link'].'" target="blank" >';
        $banner_html .=     $banner['params']['text'];
        $banner_html .= '</a>';
        
        return $banner_html;
        
    }
    
}