<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* * 
 * Lang library by Yordan Arnaudov v1.0
 * */

class Lang_lib
{
    public  $lang;
    
    function __construct() 
    {        
        global $URI, $CFG, $CI;
        
        $config =& $CFG->config; 
        
        $this->ci =& get_instance();
        
        /* get the language abbreviation from uri ---------------------- */
        $this->lang = $URI->segment(1);
                
        if(!isset($_COOKIE['admin_lang']) || $_COOKIE['admin_lang'] != $this->lang){
            setcookie("admin_lang", $this->lang, time()+60*60*24*30, "/");
        }
        
        $lang_abbr     = isset($_COOKIE['admin_lang']) ? $_COOKIE['admin_lang'] : $config['language_abbr'];;
        $lang_uri_abbr = $config['lang_uri_abbr'];
        
        if(!isset($lang_uri_abbr[$this->lang])){
            header('Location: '.base_url($lang_abbr));
            exit;
        }
        
        /* reset uri segments and uri string --------------------------- */
        $URI->_reindex_segments(array_shift($URI->segments));
        $URI->uri_string = preg_replace("|^\/?$this->lang/?|", '', $URI->uri_string);
        
        
        /* reset index page value -------------------------------------- */
        $index_page  = $config['index_page'];
        $index_page .= empty($index_page) ? $this->lang : "/$this->lang";
        $config['index_page'] = $index_page;
        
    }
    
    function get()
    {
        return $this->lang;
    }
    
}

function get_lang()
{
    
    $CI =& get_instance();
    return $CI->lang_lib->lang;    
    
}