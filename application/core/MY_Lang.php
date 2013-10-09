<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Lang.php";

class MY_Lang extends MX_Lang {

    public function load($langfile = array(), $lang = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '', $_module = '')	{

	if($lang == ''){
	    $lang = get_lang();
	}
	
        if ($lang = parent::load($langfile, $lang, $return, $add_suffix, $alt_path)) return $lang;
        
    }
        
}