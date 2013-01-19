<?php
 
 /*
  *  set default content templates
  */
  $this->Content->templates['main'] = '../../templates/'.$this->Settings->getTemplate().'/vews/content/main';
                                    
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
                    <?=$this->Module->load('search');?>
                </div>

                <div id="language_switch" >
                    <?=$this->Module->load('language_switch');?>
                </div>
                
            </div>
            
            <div id="sitename" class="clear">
                <h1>
                    <a href="<?=site_url();?>">
                        <?=$this->Module->load('logo');?>
                    </a>
                </h1>               
            </div>
                        
            <div id="navbar">	
                <div class="clear">
                    <?=$this->Module->load('main_menu');?>
                </div>
            </div>

            <div id="header" class="clear">
                <?=$this->Banner->load('');?>
            </div>
            <div class="header-bottom"></div>

            <div id="body-wrapper">

                <div class="bcnav">
                    <div class="bcnav-left">
                        <div class="bcnav-right clear">
                            <?=$this->Module->load('breadcrumb');?>
                        </div>
                    </div>
                </div>

                <div id="body" class="clear">


                    <div class="clear">

                        <?php $sidebar_modules = $this->Module->load('sidebar'); ?>
                        
                        <div class="column <?php echo $sidebar_modules ? "column-650" : "column-auto"; ?> column-left">
                            <?=$this->Content->load();?>
                        </div>

                        <?php if($sidebar_modules){ ?>
                        <div id="sidebar" class="column column-240 column-right">
                            <?=$sidebar_modules?>
                        </div>
                        <?php } ?>

                    </div>

                </div>

            </div>

            <div id="footer">
                <?=$this->Module->load('footer_left');?>
                <br/>
            </div>

        </div>
        
    </body>
</html>
