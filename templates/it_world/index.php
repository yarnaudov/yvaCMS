<!DOCTYPE html>
<html>
    <head>
        
        <include type="header" />
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="stylesheet" type="text/css" href="<?=base_url(TEMPLATES_DIR.'/it_world/css/style.css');?>" />
        <link rel="stylesheet" type="text/css" href="<?=base_url(TEMPLATES_DIR.'/it_world/css/layout.css');?>" />
        <!--[if lt IE 7]>
        <link rel="stylesheet" type="text/css" href="<?=base_url(TEMPLATES_DIR.'/it_world/css/style_ie.css');?>" />
        <![endif]-->
                
    </head>
    
    <body>
        
        <div class="tail-top-right"></div>
        <div class="tail-top">
            <div class="tail-bottom">
                <div id="main">
                
                    <div id="header">                    
                        <include type="module" name="search" />	
                        
                        <include type="module" name="language-switch" />

                        <include type="module" name="top-menu-right" />
                        
                        <div class="main_menu" >
                            <include type="module" name="menu" />
                        </div>                        

                        <div class="logo">
                            <!--
                            <a href="<?=site_url();?>"><img src="<?=base_url(TEMPLATES_DIR.'/it_world/images/logo.gif');?>" alt="" /></a>
                            -->
                        </div>

                        <div class="slogan">
                            <img src="<?=base_url('');?>" alt="" />
                        </div>  
                        
                        <div class="breadcrumb">
                            <include type="module" name="navigation" />
                        </div>

                    </div>        
      
                    <div id="content">
                        <div class="wrapper">
                            
                            <?php $sidebar_modules = $this->Module->count('sidebar'); ?>
                            
                            <div class="col-1" <?php echo $sidebar_modules ? '' : 'style="width:100%;"'; ?> >
                                <include type="content" />
                            </div>
                            
                            <?php if($sidebar_modules){ ?>
                            <div class="col-2">
                                <include type="module" name="sidebar" /> 
                            </div>
                            <?php } ?>
                            
                        </div>
                    </div>
                                
                    <div id="footer">
                        <div class="indent">
                            <div class="fleft">
                                <?=$this->Module->load('footer-left');?>
                            </div>
                            <div class="fright">
                                <?=$this->Module->load('footer-right');?>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
        
    </body>
    
</html>