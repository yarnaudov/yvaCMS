<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY Site URL
 *
 * Create a local URL based on your basepath. Segments can be passed via the
 * first parameter either as a string or an array. Do not include language if it is default
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('site_url'))
{
	function site_url($uri = '')
	{
		$CI =& get_instance();
		
		if($CI->Setting->getDefaultLanguageInUrl() == 'no' && get_lang() == $CI->Language->getDefault()){
		    return $CI->config->base_url($uri);
		}
		
		return $CI->config->site_url($uri);
	}
}

// ------------------------------------------------------------------------