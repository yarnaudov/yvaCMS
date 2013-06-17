<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mod_html extends CI_Model{
	
	  function run($module)
	  {
	  	
	  	  return $module['params']['html'];
	  	
	  }
    
}