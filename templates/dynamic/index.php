<?php
 
 /*
  *  set default content templates
  */
  $this->Content->templates['main'] = '../../templates/'.$this->Setting->getTemplate().'/../vews/content/main';
                                    
?>

<!DOCTYPE html>
<html>
    
    <head>
        
        <?=$this->Content->header();?>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8;" />
        <link rel="stylesheet" type="text/css" href="<?=base_url('templates/dynamic/css/style.css');?>" />
        
        <?php echo is_object($this->jquery_ext) ? $this->jquery_ext->output() : ""; ?>
        
    </head>
    
    <body>
        
        <div id="wrapper">

           
            
            <div id="header_top" >
                
                <div id="search">
                    <include type="module" name="search" />
                </div>

                <div id="language_switch" >
                    <include type="module" name="language_switch" template="" />
                </div>
                
            </div>
            
            <div id="sitename" class="clear">
                <include type="module" name="logo" />             
            </div>
                        
            <div id="navbar">	
                <div class="clear">
                    <include type="module" name="menu" />
                </div>
            </div>

            <div id="header" class="clear">
            </div>
            <div class="header-bottom"></div>

            <div id="body-wrapper">

                <div class="bcnav">
                    <div class="bcnav-left">
                        <div class="bcnav-right clear">
                        </div>
                    </div>
                </div>

                <div id="body" class="clear">


                    <div class="clear">

                        <?php $sidebar_modules = $this->Module->count('sidebar'); ?>
                        
                        <div class="column <?php echo $sidebar_modules ? "column-650" : "column-auto"; ?> column-left">
                            <include type="content" />
                        </div>

                        <?php if($sidebar_modules){ ?>
                        <div id="sidebar" class="column column-240 column-right">
                            <include type="banner" name="sidebar" />
                            <include type="module" name="sidebar" /> 
                        </div>
                        <?php } ?>

                    </div>

                </div>

            </div>

            <div id="footer">
                <include type="module" name="footer_left" />
                <br/>
            </div>

        </div>
        
    </body>
</html>
