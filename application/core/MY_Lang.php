<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Lang.php";

class MY_Lang extends MX_Lang {

    public function load($langfile = array(), $lang = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '', $_module = '')	{

        $lang = get_lang();               

        if (is_array($langfile)) {
            foreach($langfile as $_lang) $this->load($_lang);
            return $this->language;
        }

        $deft_lang = CI::$APP->config->item('language');
        $idiom = ($lang == '') ? $deft_lang : $lang;

        if (in_array($langfile.'_lang'.EXT, $this->is_loaded, TRUE))
            return $this->language;

        $_module OR $_module = CI::$APP->router->fetch_module();
        list($path, $_langfile) = Modules::find($langfile.'_lang', $_module, 'language/'.$idiom.'/');

        if ($path === FALSE) {
	
	    # try to load the language from template directory first
	    if($lang = parent::load($langfile, $lang, $return, $add_suffix, TEMPLATES_DIR . '/' . CI::$APP->template_main . '/')){
		return $lang;
	    }
            else if ($lang = parent::load($langfile, $lang, $return, $add_suffix, $alt_path)){
                return $lang;
	    }

        } else {

            if($lang = Modules::load_file($_langfile, $path, 'lang')) {
                if ($return) return $lang;
                $this->language = array_merge($this->language, $lang);
                $this->is_loaded[] = $langfile.'_lang'.EXT;
                unset($lang);
            }
            
        }

        return $this->language;
        
    }
        
}