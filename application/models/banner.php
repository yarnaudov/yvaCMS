<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner extends MY_Model {

	public $templates = array('main' => 'banners/main');
	
    public function getDetails($banner_id, $field = null)
    {

        $this->db->select('*');
        $this->db->where('id', $banner_id);

        $banner = $this->db->get('banners');  	
        $banner = $banner->result_array();

		$banner[0]['params'] = json_decode($banner[0]['params'], true);
		$banner[0]           = array_merge($banner[0], $this->Custom_field->getValues('banners', $banner[0]['id']));
		
        if($field == null){           
            return $banner[0];
        }
        else{  	
            return $banner[0][$field];
        }

    }
    
    public function load($position, $random = false)
    {

		$templates = $this->templates;
		if(isset($this->templates[$position])){
			$templates = array_merge($this->templates, $this->templates[$position]);
		}
				
        $banners = self::_getBanners($position);
 
        // get random only one banner and load only it
        if($random == true){
            $key = array_rand($banners);
            $banners = array($banners[$key]);
        }
        
        $banner_html = '';
        foreach($banners as $banner){

            switch($banner['type']){
                case "image":
                    $content = self::_image($banner);
                break;
                case "flash":
                    $content = self::_flash($banner);
                break;
                case "html":
                    $content = self::_html($banner);
                break;
                case "link":
                    $content = self::_link($banner);
                break;                
            }
			
			$banner_html .= $this->load->view($templates['main'], compact('banner', 'content'), true); 
	    
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

			$banner['params'] = json_decode($banner['params'], true);
			$banner           = array_merge($banner, $this->Custom_field->getValues('banners', $banner['id']));
            
            if(parent::check_item_display($banner)){
				$banners_arr[] = $banner; 
			}
            
        }
        
        return $banners_arr;
        
    }
    
    function _image($banner)
    {
	
        $banner_html = '';
	
		if(!empty($banner['params']['link'])){
			$banner_html  .= '<a href="'.site_url('banners/'.$banner['id']).'" target="_blank" >';
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

			$a->href = site_url('banners/'.$banner['id']).'?url='.$a->href;
			$html->find('a', $key)->href = $a->href;
			$html->find('a', $key)->target = 'blank';
                        
        }
	
        return $html;
        
    }
    
    function _link($banner)
    {
	
        $banner_html = '';
	
		if(!empty($banner['params']['link'])){
			$banner_html  .= '<a href="'.site_url('banners/'.$banner['id']).'" target="_blank" >';
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

		if($type == 2){
			$data['page_url'] = '';
		}
		else{
			$data['page_url'] = $_SERVER['QUERY_STRING'] ? current_url().'?'.$_SERVER['QUERY_STRING'] : current_url();
		}

		$data['banner_id'] = $id;
		$data['ip'] = $this->input->ip_address();
		$data['created_on'] = date('Y-m-d H:i:s');
		$data['type'] = $type;

		$this->db->insert('banners_statistics', $data);
	
    }
    
}