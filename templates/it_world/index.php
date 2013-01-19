<!DOCTYPE html>
<html>
    <head>
        
        <?=$this->Content->header();?>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="stylesheet" type="text/css" href="<?=base_url('templates/it_world/css/style.css');?>" />
        <link rel="stylesheet" type="text/css" href="<?=base_url('templates/it_world/css/layout.css');?>" />
        <!--[if lt IE 7]>
        <link rel="stylesheet" type="text/css" href="<?=base_url('templates/it_world/css/style_ie.css');?>" />
        <![endif]-->
        
        <?php echo is_object($this->jquery_ext) ? $this->jquery_ext->output() : ""; ?>
        
    </head>
    
    <body>
        
        <div class="tail-top-right"></div>
        <div class="tail-top">
            <div class="tail-bottom">
                <div id="main">
                
                    <div id="header">                    
                        <?=$this->Module->load('search');?>	
                        
                        <?=$this->Module->load('language_switch');?>

                        <?=$this->Module->load('top_menu_right');?>
                        
                        <div class="main_menu" >
                            <?=$this->Module->load('main_menu');?>
                        </div>                        

                        <div class="logo">
                            <a href="home.html"><img src="<?=base_url('templates/it_world/images/logo.gif');?>" alt="" /></a>
                        </div>

                        <div class="slogan">
                            <img src="<?=base_url('');?>" alt="" />
                        </div>  
                        
                        <div class="breadcrumb">
                            <?=$this->Module->load('breadcrumb');?>
                        </div>

                    </div>        
      
                    <div id="content">
                        <div class="wrapper">
                            
                            <?php $sidebar_modules = $this->Module->load('sidebar'); ?>
                            
                            <div class="col-1" <?php echo $sidebar_modules ? '' : 'style="width:100%;"'; ?> >
                                <?=$this->Content->load();?>
                            </div>
                            
                            <?php if($sidebar_modules){ ?>
                            <div class="col-2">
                                <?=$sidebar_modules;?>
                            </div>
                            <?php } ?>
                            
                        </div>
                    </div>
                                
                    <div id="footer">
                        <div class="indent">
                            <div class="fleft">
                                <?=$this->Module->load('footer_left');?>
                            </div>
                            <div class="fright">
                                <?=$this->Module->load('footer_right');?>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
        
    </body>
    
</html>