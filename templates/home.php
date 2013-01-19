
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8;" />
        <link rel="shortcut icon" type="image/ico" href="http://melnik-bg.eu/img/icon.png" />
        <link rel="stylesheet" type="text/css" href="<?=base_url('css/main.css');?>" />
        <title>
            Мелник | 
            <?php
            if($this->article_alias){
                $article = $this->Article->getByAlias($this->article_alias);
                echo $article['title_'.$this->lang_lib->get()];
            }
            else{
                $menu = $this->Menu->getDetails($this->menu_id);
                echo $menu['title_'.$this->lang_lib->get()];
            }
            ?>
        </title>
    </head>
	<body>

            <div class="main">

                <div class="top_back">

                    <div style="width: 912px; margin: auto;">
                        <div class="top_text" style="float:left;" >
                            <?=$this->Module->load('languages');?>
                        </div>

                        <div class="top_back_menu">

                            <div>                        
                                <?=$this->Module->load('top_menu');?>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="main_content">

                    <div id="topleft"> 		
                        <?=$this->Module->load('main_menu');?>
                    </div> 

                    
                    
                    <div id="headers">
                        
                        <?=$this->Module->load('top_content');?>
                        <?=$this->Module->load('breadcrumb');?>
                        
                    </div>
                    

                    <div class="right_content" style="margin-top: -24px;" >
                        <div class="right_content_top" ></div>
                        <div class="right_content_back" >
                            <div style="overflow: hidden;width: 248px;height: 194px;" >
                                <div id="NetweatherContainer" style="height: 220px; margin-top: -20px;" >
                                    <?=$this->Banner->load('weather');?>
                                </div>
                            </div>                            
                        </div>
                        <div class="right_content_bottom" ></div>
                    </div>

                    <div class="main_content_left">                     
                        <?=$this->Content->load();?>
                    </div>
                    
                    <div class="main_content_right">
                        
                        <div class="right_content" >
                            <div class="right_content_top" ></div>
                            <div class="right_content_back" style="margin: -5px 0px -7px;*margin-bottom: -2px;" >
                                <?=$this->Banner->load('right_content');?>
                            </div>
                            <div class="right_content_bottom" ></div>
                        </div>
                        
                        <?=$this->Module->load('right_content', 'custom/module_main');?>
                    </div>
                    
                </div>

                <div class="footer_back">

                    <div id="copyright">
                        <span class="copyright_text" >
                            Designed by <u><a href="contacts.php">Yordan Arnaudov</a></u> - member of <u><a href="http://webrise.biz" target="blank">webrise</a></u> team<br/>&copy; 2010 Webrise
                        </span>
                    </div>
                </div>

        </div>
            
    </body>
    
</html>
