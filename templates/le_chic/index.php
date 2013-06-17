<?php
 
 /*
  *  set default content templates
  */
  $this->Content->templates['main'] = '../../'.TEMPLATES_DIR.'/'.$this->Settings->getTemplate().'/vews/content/main';
 
?>

<!DOCTYPE html>
<html>
    <head>
        
        <include type="header" />
        
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="<?=base_url(TEMPLATES_DIR.'/le_chic/css/style.css');?>" />
        
    </head>

    <body>
        
        <div id="wrapper">

            <div id="preface">

                <div id="left">
                     <?=$this->Module->load('top_menu_left');?>				
                </div>

                <div id="right">
                    <?=$this->Module->load('language_switch');?>
                </div>

            </div><!-- End Top Area -->

            <div id="header">
                <div id="logo">
                    <h1><a href="#">Le Chic by Anthony Licari</a></h1>
                                    <h2>A Premium versatile theme with multiple regions</h2>
                </div>
                <div id="middle">
                    <p>You can put an ad or other information here</p>
                </div>
                <div id="search">    
                    <?=$this->Module->load('search');?>
                </div>
            </div>

            <div id="navigation" class="menu">
                <?=$this->Module->load('main_menu');?>
            </div>
            
            <div id="breadcrumb" class="breadcrumb">
                <?=$this->Module->load('breadcrumb');?>
            </div>
                       
            <?=$this->Content->load();?>

            <?php $sidebar_modules = $this->Module->load('sidebar', array('menu' => '../../'.TEMPLATES_DIR.'/'.$this->Settings->getTemplate().'/vews/modules/sidebar') );
                  if($sidebar_modules){ ?>
            <div id="sidebar">
                <div id="sidebar_top">
                    <?=$sidebar_modules;?>
                </div>                
            </div>
            <?php } ?>
            
            <div style="clear: both;">&nbsp;</div>			

        </div>

        <div id="footer">
            
            <div id="footer_regions">	
                <div id="footer_left">
                    <?=$this->Module->load('footer_regions1');?>
                </div>

                <div id="footer_leftmiddle">	
                    <?=$this->Module->load('footer_regions2');?>
                </div>

                <div id="footer_rightmiddle">	
                    <?=$this->Module->load('footer_regions3');?>
                </div>

                <div id="footer_right">
                    <?=$this->Module->load('footer_regions4');?>
                </div>  
            </div>
            
        </div>

        <div id="footer_bottom">
            <div id="footer_bottom_region">
                <div class="footer_left">
                    <?=$this->Module->load('footer_left');?>
                </div>
                
                <div class="footer_right">
                    <?=$this->Module->load('footer_right');?>
                </div>                
            </div>
        </div>

    </body>
</html>