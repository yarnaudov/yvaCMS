
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
            var LANG              = '<?=get_lang();?>';
            var TEMPLATE          = '<?=$this->Setting->getTemplate();?>';
            var DOCUMENT_BASE_URL = '<?=base_url();?>../';
            var base_url          = '<?=base_url();?>';
            var site_url          = '<?=site_url();?>';
        </script>
        
    </head>

    <body>