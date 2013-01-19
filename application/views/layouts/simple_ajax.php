<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
  
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8;" />
	  
        <?php echo is_object($this->jquery_ext) ? $this->jquery_ext->output() : ""; ?>
        
        <title> </title>
        
    </head>

    <body>
  
        <div id="main" >

            <div id="content" >
                <?php echo $content; ?>
            </div>

        </div>
        
    </body>
</html>