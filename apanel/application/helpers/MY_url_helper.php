<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('current_url'))
{
	function current_url($get_query_string = false)
	{
            $query_string = "";
            if($_SERVER['QUERY_STRING'] && $get_query_string == true){
                $query_string = "?".$_SERVER['QUERY_STRING'];
            }
            
            $CI =& get_instance();
            return $CI->config->site_url($CI->uri->uri_string()).$query_string;
	}
}