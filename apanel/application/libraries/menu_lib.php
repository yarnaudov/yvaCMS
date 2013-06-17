<?php
/**
 * Menu Class
 *
 * Navigation List with current page check
 *
 * @package		CodeIgniter
 * @category	Libraries
 * @start		4.43 02/11/2008
 * @version		1.3  - 15.30 17/10/2008
 * @credit		Fork: Html_Helper (CodeIgniter)
 * @author		Piro Fabio (dewosmail@gmail.com)
 */
 
class Menu_lib
{
	/**
	 * Constructor
	 */
	 
	function Menu_lib()
	{
		$this->ci =& get_instance();
		$this->ci->config->load('menu');
		
		log_message('debug', 'Menu Library: Initialized');
	}
	
	// ------------------------------------------------------------------------

        
        function create_menu($menus, $type = 'ul', $attributes=''){
            
            // Attributes submitted? 
            if (is_array($attributes))
            {
                    $atts = '';
                    foreach ($attributes as $key => $val)
                    {
                            $atts .= ' ' . $key . '="' . $val . '"';
                    }
                    $attributes = $atts;
            }
            
            
            // Opening
            $out = "<" . $type  . $attributes . ">\n";
                                    
            $numb = 1;
            foreach ( $menus as $value => $href ){ 
                
                $status = '';
                
                // Check  if it's the first item
                if($numb == 1){
                    $status .= "first ";
                }
                
                // Check  if it's the last item
                if(($numb) == count($menus)){
                    $status .= "last ";
                }

                // Check  if it's the current page per styling
                if ( ! is_array($href))
                {
                    $status .= $this->_check_current($href);
                }
                else{
                    $main_href = key($href);
                    $status .= $this->_check_current($main_href);
                }
                
                $class = $status != '' ? ' class="'.$status.'"' : '';

                // Open Li
                $out .= "<li" . $class . ">";
                
                if ( ! is_array($href))
                {
                    $out .= '<a href="'.site_url($href).'" >'.$value.'</a>';
                }
                else{
                    $main_href = key($href);
                    $out .= '<a href="'.site_url($main_href).'" >'.$value.'</a>';
                    $out .= self::create_menu($href[$main_href]);
                }
                
                //Close Li
                $out .= "</li>";
                
                
                $numb++;
                
            }
            
            
            $out .= "</" . $type . ">\n";
            
            return $out;
            
        }

	function _check_current($match)
	{
            
            $current_tag = $this->ci->config->item('current_tag');
            $segment = get_class($this->ci);// Current Controller Name
    	    
            $lang = get_lang();
           
            $match = str_replace('/', '\/', '/'.$lang.'/'.$match);
                        
            if(preg_match('/'.$match.'/', current_url())){
                return $current_tag;
            }
            else{
                return '';
            }
            
	}

	// ------------------------------------------------------------------------
}
/* End of file menu.php */
/* Location: ./application/libraries/menu.php */