<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* * 
 * Lang library by Yordan Arnaudov v1.0
 * */

class Lang_lib
{
    public  $lang;
    
    function __construct() 
    {        
        global $URI, $CFG;
        
        $config =& $CFG->config; 
        
        $CI =& get_instance();
        
        /* get the language abbreviation from uri ---------------------- */
        $this->lang = $URI->segment(1);
        
        /* get all available languages --------------------------------- */
        $lang_uri_abbr = $CI->Language->getLanguages(true);
        
        if(!isset($lang_uri_abbr[$this->lang])){
	    
	    $lang_abbr = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : $CI->Language->getDefault();
	    
	    if($CI->Setting->getDefaultLanguageInUrl() == 'no' && $lang_abbr == $CI->Language->getDefault()){
		$this->lang = $lang_abbr;
	    }
	    else{
		header('Location: '.base_url($lang_abbr));
		exit;
	    }
	    
        }
	else{
	    /* reset uri segments and uri string --------------------------- */
	    $URI->_reindex_segments(array_shift($URI->segments));
	    $URI->uri_string = preg_replace("|^\/?$this->lang/?|", '', $URI->uri_string);
	}
        
	/* set language cookie ----------------------------------------- */
	if(!isset($_COOKIE['lang']) || $_COOKIE['lang'] != $this->lang){
            setcookie("lang", $this->lang, time()+60*60*24*30, "/");
        }        
        
        /* reset index page value -------------------------------------- */
        $index_page  = $config['index_page'];
        $index_page .= empty($index_page) ? $this->lang : "/$this->lang";
        $config['index_page'] = $index_page;
        
    }
    
}

function get_lang()
{
    
    $CI =& get_instance();
    return $CI->lang_lib->lang;    
    
}