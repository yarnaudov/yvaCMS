
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8;" />
        <link rel="shortcut icon" type="image/ico" href="<?php echo base_url('img/iconAdministration.png'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('css/css.css'); ?>" />
	  
        <?php echo is_object($this->jquery_ext) ? $this->jquery_ext->output() : ""; ?>
	
        <?php echo isset($meta) ? $meta : ""; ?>
        
        <title>
            <?php
            echo lang('label_tool_title');
            
            $class = @$this->tool_title;
            
            if(empty($class)){
                $class = lang('label_'.$this->router->class);
            }
            
            $method = lang('label_'.$this->router->method);
            
            if(!empty($class)){
                echo " - ".$class;
            }
            if(!empty($method)){
                echo " ".$method;
            }
            ?>
        </title>
        
        <script type="text/javascript" >
            var LANG              = '<?php echo get_lang();?>';
            var TEMPLATE          = '<?php echo $this->Setting->getTemplate();?>';
            var DOCUMENT_BASE_URL = '<?php echo base_url();?>../';
            var base_url          = '<?php echo base_url();?>';
            var site_url          = '<?php echo site_url();?>';
        </script>
        
    </head>

    <body>