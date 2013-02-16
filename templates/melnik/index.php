<?php

/*
 * set default modules templates
 */
 //$this->Module->templates = array('main'            => 'modules/main',
 //                                 'menu'            => 'modules/menu',
 //                                 'custom_menu'     => 'modules/custom_menu',
 //                                 'article'         => 'modules/article',
 //                                 'articles_list'   => 'modules/articles_list',
 //                                 'search_form'     => 'modules/search_form',
 //                                 'language_switch' => 'modules/language_switch',
 //                                 'breadcrumb'      => 'modules/breadcrumb',
 //                                 'image'           => 'modules/image');
 
 /*
  *  set default content templates
  */
  //$this->Content->templates = array('main'         => '../../templates/'.$this->Settings->getTemplate().'/vews/content/main',
  //                                  'article'      => 'content/article',
  //                                  'article_list' => 'content/article_list');
  
  $menu_alias = $this->uri->segment(1);
 
?>

<!DOCTYPE html>
<html>
    <head>

        <include type="header" />
        
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="<?=base_url('templates/'.$this->Setting->getTemplate().'/../css/style.css');?>" />
        <link rel="stylesheet" type="text/css" href="<?=base_url('templates/'.$this->Setting->getTemplate().'/../css/stickyfooter.css');?>" />
        
        <script type="text/javascript" src="<?=base_url('templates/'.$this->Setting->getTemplate().'/../js/slides.min.jquery.js');?>" ></script>
        <script type="text/javascript" >
            $(function(){
                $('#slides').slides({
                    preload: true,
                    preloadImage: '<?=base_url('templates/'.$this->Setting->getTemplate().'/../images/loading.gif');?>',
                    play: 5000,
                    pause: 2500,
                    hoverPause: true
                });
            });
        </script>
        <link rel="stylesheet" type="text/css" href="<?=base_url('templates/'.$this->Setting->getTemplate().'/../css/slider.css');?>" />
        
    </head>

    <body>
        
        <div id="wrap" >
        
            <div id="main" >
       
                <div id="top" >
           
                    <div class="center" >
           	    
                        <div id="languages_switch" >
                            <include type="module" name="language_switch" />
                        </div>
           	             
                    </div>      	
                      
                </div>
         
         
                <div id="menu" class="center" >
                    <include type="module" name="main_menu" />
                </div>
         
                <div id="map" class="center" >
                    <include type="module" name="map" />
                </div>
         
                <?php if($menu_alias == 'home'){ ?>
                <div id="image_slider" class="center" >
                    <div id="slides">
                        <div class="slides_container">
                            <a href="http://www.flickr.com/photos/jliba/4665625073/" title="145.365 - Happy Bokeh Thursday! | Flickr - Photo Sharing!" target="_blank"><img src="http://slidesjs.com/examples/standard/img/slide-1.jpg" width="990" height="270" alt="Slide 1"></a>
                            <a href="http://www.flickr.com/photos/stephangeyer/3020487807/" title="Taxi | Flickr - Photo Sharing!" target="_blank"><img src="http://slidesjs.com/examples/standard/img/slide-2.jpg" width="990" height="270" alt="Slide 2"></a>
                            <a href="http://www.flickr.com/photos/childofwar/2984345060/" title="Happy Bokeh raining Day | Flickr - Photo Sharing!" target="_blank"><img src="http://slidesjs.com/examples/standard/img/slide-3.jpg" width="990" height="270" alt="Slide 3"></a>
                            <a href="http://www.flickr.com/photos/b-tal/117037943/" title="We Eat Light | Flickr - Photo Sharing!" target="_blank"><img src="http://slidesjs.com/examples/standard/img/slide-4.jpg" width="990" height="270" alt="Slide 4"></a>
                            <a href="http://www.flickr.com/photos/bu7amd/3447416780/" title="“I must go down to the sea again, to the lonely sea and the sky; and all I ask is a tall ship and a star to steer her by.” | Flickr - Photo Sharing!" target="_blank"><img src="http://slidesjs.com/examples/standard/img/slide-5.jpg" width="990" height="270" alt="Slide 5"></a>
                            <a href="http://www.flickr.com/photos/streetpreacher/2078765853/" title="twelve.inch | Flickr - Photo Sharing!" target="_blank"><img src="http://slidesjs.com/examples/standard/img/slide-6.jpg" width="990" height="270" alt="Slide 6"></a>
                            <a href="http://www.flickr.com/photos/aftab/3152515428/" title="Save my love for loneliness | Flickr - Photo Sharing!" target="_blank"><img src="http://slidesjs.com/examples/standard/img/slide-7.jpg" width="990" height="270" alt="Slide 7"></a>
                        </div>
                        <a href="#" class="prev"><img src="<?=base_url('templates/'.$this->Setting->getTemplate().'/../images/arrow-prev.png');?>" width="24" height="43" alt="Arrow Prev"></a>
                        <a href="#" class="next"><img src="<?=base_url('templates/'.$this->Setting->getTemplate().'/../images/arrow-next.png');?>" width="24" height="43" alt="Arrow Next"></a>
                    </div>
                    <img src="<?=base_url('templates/'.$this->Setting->getTemplate().'/../images/example-frame.png');?>" alt="Example Frame" id="frame">
                </div>
                <?php } ?>
				 
				 
                <div id="content" class="center" >
				 
                    <table border="0" >
				   
                        <tr>
				     
                            <td class="left" >
                                <div class="content" >
                                    <include type="content" />
                                </div>
                            </td>
				       
                            <td class="right" >
                                <include type="module" name="weather" />
                                <include type="banner" name="sidebar" />
                                <include type="module" name="sidebar" />
                            </td>
				     
                        </tr>
				   
                    </table>
				 
                </div>
				 
            </div>
			
        </div>
			
		  
        <div id="footer" >
				 
            <div class="center" >
				 
                <img src="<?=base_url('templates/'.$this->Setting->getTemplate().'/../images/logo.png');?>" >

                <span class="footer_separator" ></span>

                <div id="footer_middle" >
                    <include type="module" name="footer_left" />
                </div>

                <span class="footer_separator" ></span>

                <div id="footer_right" > 
                    <include type="module" name="footer_right" />
                </div>
				   
            </div>
				 
        </div>
			

    </body>
</html>