
<form name="add" action="<?php echo current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
	    <img src="<?php echo base_url('img/iconArticles_25.png');?>" >
            <span><?php echo lang('label_articles');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span>
              <?php if(isset($article_id)){
                      echo lang('label_edit');
                    }
                    else{
                      echo lang('label_add');  
                    } ?>
            </span>
	</div>
	
	<div class="actions" >
                        
	    <button type="submit" name="save"     class="styled save"   ><?php echo lang('label_save');?></button>
	    <button type="submit" name="apply"    class="styled apply"  ><?php echo lang('label_apply');?></button>
	    <a href="<?php echo site_url('articles/');?>" class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
	</div>
	
    </div>
    <!-- end page header -->

    <?php echo $this->load->view('messages');?>    
    
    <!-- start page content -->
    <div id="page_content" >
	
	<table class="add" cellpadding="0" cellspacing="0" >
            
            <tr>
                
                <!-- start left content  -->
	        <td class="left" >
	            
                    <!-- mandatory information  -->
	            <div class="box" >
	      	      <span class="header" ><?php echo lang('label_main_information');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label class="multilang" ><?php echo lang('label_title');?>:</label></th>
                                <td><input class="required" type="text" name="title" value="<?php echo set_value('title', isset($title) ? $title : "");?>" ></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>

                            <tr>
                                <th><label><?php echo lang('label_alias');?>:</label></th>
                                <td><input class="required" type="text" name="alias" value="<?php echo set_value('alias', isset($alias) ? $alias : "");?>" ></td>
                            </tr>

                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
	            
                    <div class="box" >
	      	        <span class="header multilang" ><?php echo lang('label_text');?></span>
                        <div class="editor_div" >
                            <textarea name="text" class="editor"  ><?php echo set_value('text', isset($text) ? $text : "");?></textarea>
                          
                            <div class="editor_options" >

                                <a href  = "<?php echo site_url('articles/index/simple_ajax');?>"
                                   class = "load_jquery_ui_iframe"
                                   title = "<?php echo lang('label_select').' '.lang('label_article');?>"
                                   lang  = "dialog-select-article" >
                                    <img src="<?php echo base_url('img/article.png');?>" >
                                    <span><?php echo lang('label_article');?></span>
                                </a>

                                <a href = "<?php echo site_url('media/browse');?>"
                                   class = "load_jquery_ui_iframe"
                                   title = "<?php echo lang('label_browse').' '.lang('label_media');?>"
                                   lang = "dialog-media-browser" >
                                    <img src="<?php echo base_url('img/image.png');?>" >
                                    <span><?php echo lang('label_image');?> / <?php echo lang('label_video');?></span>
                                </a>
                                
                                <a href = "<?php echo site_url('modules/article_list');?>"
                                   class = "load_jquery_ui_iframe"
                                   title = "<?php echo lang('label_select');?> <?php echo lang('label_module');?>"
                                   lang = "dialog-select-module" >
                                    <img src="<?php echo base_url('img/module.png');?>" >
                                    <span><?php echo lang('label_module');?></span>
                                </a>                               
                                
                                <a href="#" onclick="tinyMCE.execCommand('mceInsertContent', false, '<hr class=pagebreak />');return false;" >
                                    <img src="<?php echo base_url('img/page_break.png');?>" >
                                    <span><?php echo lang('label_page_break');?></span>
                                </a>

                                <a href="#" onclick="tinyMCE.execCommand('mceInsertContent', false, '<hr class=readmore />');return false;" >
                                    <img src="<?php echo base_url('img/read_more.png');?>" >
                                    <span><?php echo lang('label_read_more');?></span>
                                </a>
                                
                            </div>
                            

                        </div>
                        
	            </div>
		    
		    <div class="box" >
	      	        <span class="header" ><?php echo lang('label_metadata');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label class="multilang" ><?php echo lang('label_keywords');?>:</label></th>
                                    <td>
                                        <textarea name="meta_keywords" ><?php echo set_value('meta_keywords', isset($meta_keywords) ? $meta_keywords : "");?></textarea> 
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label class="multilang" ><?php echo lang('label_description');?>:</label></th>
                                    <td>
                                        <textarea name="meta_description" ><?php echo set_value('meta_description', isset($meta_description) ? $meta_description : "");?></textarea>                                            
                                    </td>
                                </tr>
                                
                            </table>
                                
                        </div>
                    </div>
	      
                </td>
                <!-- end left content  -->
	        
                
                <!-- start right content  -->
	        <td class="right" >
	      
                    <?php $this->load->view('translation'); ?>                    
	      
	            <div class="box" >
	      	        <span class="header" ><?php echo lang('label_options');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <?php $this->load->view('categories'); ?> 

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?php echo lang('label_status');?>:</label></th>
                                    <td>
                                        <select name="status" >
                                            <?php echo create_options_array($this->config->item('statuses'), set_value('status', isset($status) ? $status : ""));?>
                                        </select>
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?php echo lang('label_language');?>:</label></th>
                                    <td>
                                        <select name="show_in_language" >
                                            <option value="all" ><?php echo lang('label_all');?></option>
                                            <?php echo create_options('languages', 'id', 'title', set_value('language', isset($show_in_language) ? $show_in_language : ""), array('status' => 'yes') );?>
                                        </select>
                                    </td>
                                </tr>

                                <?php $this->load->view('start_end_dates'); ?>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?php echo lang('label_show_title');?>:</label></th>
                                    <td>
                                        <select name="show_title" >
                                            <?php echo create_options_array($this->config->item('yes_no'), set_value('show_title', isset($show_title) ? $show_title : ""));?>
                                        </select>
                                    </td>
                                </tr>
				
				<tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?php echo lang('label_show_comments');?>:</label></th>
                                    <td>
                                        <select name="params[show_comments]" >
                                            <?php echo create_options_array($this->config->item('yes_no'), set_value('params[show_comments]', isset($params['show_comments']) ? $params['show_comments'] : ""));?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tbody id="custom_fields" >
                                <?php if(count($custom_fields) > 0){
                                          $this->load->view('custom_fields/load_fields');
                                      } ?>
                                </tbody>
                                
                            </table>
                        </div>
                            
                    </div>
                    
                    <div class="box" >
	      	        <span class="header" ><?php echo lang('label_images');?></span>
                        
                        <div class="box_content" >
                            
                            <ol id="article_images" >
                                
                                <?php $images = set_value('params[images]', isset($params['images']) ? $params['images'] : array());
                                      foreach($images as $image){ ?>
                                <li>
                                    <input type="hidden" value="<?php echo (is_array($image) ? $image['id'] : $image);?>" name="params[images][]">
                                    
                                    <table>
                                        <tr>
                                            <td class="img" >
						<?php if(is_array($image)){ ?>
						<img src="<?php echo base_url('../'.$this->config->item('images_dir').'/'.$image['id'].'.'.$image['ext']);?>" >
                                                <?php }elseif(is_dir('../'.$image)){ ?>
                                                <img class="directory" src="<?php echo base_url('img/media/iconFolder.png');?>" >                                                
						<?php }else{ ?>
                                                <img src="<?php echo base_url('../'.$image);?>" >
                                                <?php } ?>
                                            </td>
                                            
                                            <td><?php echo (is_array($image) ? $image['title'] : $image);?></td>
                                            
                                            <td class="actions" >
                                                <img class="handle" src="<?php echo base_url('img/iconMove.png');?>" >
                                                <a class="styled delete" >&nbsp;</a>
                                            </td>
                                            
                                        </tr>
                                    </table>
                                                                        
                                </li>
                                <?php } ?>
                                
                            </ol>
                            
                            <a href="<?php echo site_url('media/browse/article');?>"
                               class = "load_jquery_ui_iframe styled add"
                               title="<?php echo lang('label_article');?> <?php echo lang('label_images');?>"
                               lang="dialog-article-images" ><?php echo lang('label_media');?></a>
                            
			    <a href="<?php echo site_url('components/gallery/images/index/simple_ajax');?>"
                               class = "load_jquery_ui_iframe styled add"
                               title="<?php echo lang('label_article');?> <?php echo lang('label_images');?>"
                               lang="dialog-article-images" ><?php echo lang('label_gallery');?></a>
			   			    
                        </div>
                    </div>
                    
                    <?php if(isset($created_by)){ ?>
                    <div class="box" >
	      	        <span class="header" ><?php echo lang('label_information');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label><?php echo lang('label_created_by');?>:</label></th>
                                    <td>
                                        <strong><?php echo User::getDetails($created_by, 'user');?></strong>
                                    </td>
                                </tr>                                                       

                                <tr>	      			
                                    <th><label><?php echo lang('label_created_on');?>:</label></th>
                                    <td>
                                        <strong><?php echo $created_on;?></strong>
                                    </td>
                                </tr>
                                
                                <?php if(isset($updated_by)){ ?>
                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?php echo lang('label_updated_by');?>:</label></th>
                                    <td>
                                        <strong><?php echo isset($updated_by) ? User::getDetails($updated_by, 'user') : "";?></strong>
                                    </td>
                                </tr>

                                <tr>	      			
                                    <th><label><?php echo lang('label_updated_on');?>:</label></th>
                                    <td>
                                        <strong><?php echo isset($updated_on) ? $updated_on : "";?></strong>
                                    </td>
                                </tr>
                                <?php } ?>
                                
                                <tr>
                                    <th></th>
                                    <td style="text-align: right;">
					
					<?php if($this->uri->segment(4) == 'history'){ ?>
					<a href="<?php echo site_url('articles/edit/'.$id);?>" >Текуща версия</a> | 
					<?php } ?>
					
                                        <a href = "<?php echo site_url('/articles/history/'.$id);?>"
                                           class = "load_jquery_ui_iframe"
                                           title = "<?php echo lang('label_article');?> <?php echo lang('label_history');?>"
                                           lang = "dialog-article-history" ><?php echo lang('show_full_history');?></a>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                                            
                    </div>
                    <?php } ?>
                    
                </td>
                <!-- end right content  -->
                
            </tr>
            
        </table>
	
    </div>
    <!-- end page content -->

</form>


