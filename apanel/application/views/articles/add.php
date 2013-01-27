
<form name="add" action="<?=current_url();?>" method="post" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
	    <img src="<?=base_url('img/iconArticles_25.png');?>" >
            <span><?=lang('label_articles');?></span>
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
                        
	    <button type="submit" name="save"     class="styled save"   ><?=lang('label_save');?></button>
	    <button type="submit" name="apply"    class="styled apply"  ><?=lang('label_apply');?></button>
	    <a href="<?=site_url('articles/');?>" class="styled cancel" ><?=lang('label_cancel');?></a>
		
	</div>
	
    </div>
    <!-- end page header -->
    
    
    <!-- start messages -->
    <?php $errors = validation_errors();
        if(!empty($errors)){ ?>      
        <div class="error_msg" >
            <ul>
              <?=validation_errors('<li>', '</li>');?>
            </ul>
        </div>
    <?php } ?>

    <?php $good_msg = $this->session->userdata('good_msg');
          $this->session->unset_userdata('good_msg');
          if(!empty($good_msg)){ ?>
          <div class="good_msg" >
              <?=$good_msg;?>            
          </div>
    <?php } ?>

    <?php $error_msg = $this->session->userdata('error_msg');
          $this->session->unset_userdata('error_msg');
          if(!empty($error_msg)){ ?>
          <div class="error_msg" >
              <?=$error_msg;?>            
          </div>
    <?php } ?>
    <!-- end messages -->
    
    
    <!-- start page content -->
    <div id="page_content" >
	
	<table class="add" cellpadding="0" cellspacing="0" >
            
            <tr>
                
                <!-- start left content  -->
	        <td class="left" >
	            
                    <!-- mandatory information  -->
	            <div class="box" >
	      	      <span class="header" ><?=lang('label_mandatory');?> <?=lang('label_information');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label class="multilang" ><?=lang('label_title');?>:</label></th>
                                <td><input type="text" name="title" value="<?=set_value('title', isset($title) ? $title : "");?>" ></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>

                            <tr>
                                <th><label><?=lang('label_alias');?>:</label></th>
                                <td><input type="text" name="alias" value="<?=set_value('alias', isset($alias) ? $alias : "");?>" ></td>
                            </tr>

                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
	            
                    <div class="box" >
	      	        <span class="header multilang" ><?=lang('label_text');?></span>
                        <div class="editor_div" >
                            <textarea name="text" class="editor"  ><?=set_value('text', isset($text) ? $text : "");?></textarea>
                          
                            <div class="editor_options" >

                                <a href  = "<?=site_url('articles/index/simple_ajax');?>"
                                   class = "load_jquery_ui_iframe"
                                   title = "<?=lang('label_select').' '.lang('label_article');?>"
                                   lang  = "dialog-select-article" >
                                    <img src="<?=base_url('img/article.png');?>" >
                                    <span><?=lang('label_article');?></span>
                                </a>

                                <a href = "<?=site_url('media/browse');?>"
                                   class = "load_jquery_ui_iframe"
                                   title = "<?=lang('label_browse').' '.lang('label_media');?>"
                                   lang = "dialog-media-browser" >
                                    <img src="<?=base_url('img/image.png');?>" >
                                    <span><?=lang('label_image');?> / <?=lang('label_video');?></span>
                                </a>
                                
                                <a href = "<?=site_url('modules/article_list');?>"
                                   class = "load_jquery_ui_iframe"
                                   title = "<?=lang('label_select');?> <?=lang('label_module');?>"
                                   lang = "dialog-select-module" >
                                    <img src="<?=base_url('img/module.png');?>" >
                                    <span><?=lang('label_module');?></span>
                                </a>                               
                                
                                <a href="#" onclick="tinyMCE.execCommand('mceInsertContent', false, '<hr class=pagebreak />');return false;" >
                                    <img src="<?=base_url('img/page_break.png');?>" >
                                    <span><?=lang('label_page_break');?></span>
                                </a>

                                <a href="#" onclick="tinyMCE.execCommand('mceInsertContent', false, '<hr class=readmore />');return false;" >
                                    <img src="<?=base_url('img/read_more.png');?>" >
                                    <span><?=lang('label_read_more');?></span>
                                </a>
                                
                            </div>
                            

                        </div>
                        
	            </div>
	      
                </td>
                <!-- end left content  -->
	        
                
                <!-- start right content  -->
	        <td class="right" >
	      
                    <div class="box" >
                        <span class="header" ><?=lang('label_translation');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>
                                    <td>
                                        <select name="translation" >
                                            <?=create_options('languages', 'id', 'title', $this->trl, array('status' => 'yes'));?>
                                        </select>
                                    </td>
                                </tr>

                            </table>
                        </div>
                        
	            </div>
                    
	      
	            <div class="box" >
	      	        <span class="header" ><?=lang('label_options');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label><?=lang('label_category');?>:</label></th>
                                    <td>
                                        <select name="category" >
                                            <?=create_options_array($categories, set_value('category', isset($category_id) ? $category_id : ""));?>
                                        </select>
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?=lang('label_status');?>:</label></th>
                                    <td>
                                        <select name="status" >
                                            <?=create_options_array($this->config->item('statuses'), set_value('status', isset($status) ? $status : ""));?>
                                        </select>
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?=lang('label_language');?>:</label></th>
                                    <td>
                                        <select name="show_in_language" >
                                            <option value="all" ><?=lang('label_all');?></option>
                                            <?=create_options('languages', 'id', 'title', set_value('language', isset($show_in_language) ? $show_in_language : ""), array('status' => 'yes') );?>
                                        </select>
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?=lang('label_start_date');?>:</label></th>
                                    <td>
                                        <input type="text" class="datepicker" name="start_publishing" value="<?=set_value('start_publishing', isset($start_publishing) ? $start_publishing : "");?>" >
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?=lang('label_end_date');?>:</label></th>
                                    <td>
                                        <input type="text" class="datepicker" name="end_publishing" value="<?=set_value('end_publishing', isset($end_publishing) ? $end_publishing : "");?>" >
                                    </td>
                                </tr>
                                
                                <?php if(count($custom_fields) > 0){ ?>
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                <tr>
                                    <td colspan="2" class="empty_line" >
                                        <fieldset style="border:none;border-top: 1px solid #aaa;padding-left: 10px;">
                                            <legend style="font-weight: bold;padding: 0 5px;" ><?=lang('label_custom_fields');?></legend>
                                        </fieldset>
                                    </td>
                                </tr>
                                
                                <?php $this->load->view('custom_fields/load_fields'); ?>
                                
                                <?php } ?>
  
                            </table>
                        </div>
                            
                    </div>
                    
                    <div class="box" >
	      	        <span class="header" ><?=lang('label_images');?></span>
                        
                        <div class="box_content" >
                            
                            <ol id="article_images" >
                                
                                <?php $images = set_value('params[images]', isset($params['images']) ? $params['images'] : array());
                                      foreach($images as $image){ ?>
                                <li>
                                    <input type="hidden" value="<?=$image;?>" name="params[images][]">
                                    
                                    <?php if(is_dir('../'.$image)){ ?>
                                    <img class="directory" src="<?=base_url('img/media/iconFolder.png');?>" >
                                    <?php }else{ ?>
                                    <img src="<?=base_url('../'.$image);?>" >
                                    <?php } ?>
                                    
                                    <span><?=$image;?></span>
                                    <a class="styled delete" >&nbsp;</a>
                                </li>
                                <?php } ?>
                                
                            </ol>
                            
                            <a href="<?=site_url('media/browse/article');?>"
                               class = "load_jquery_ui_iframe styled add"
                               title="<?=lang('label_article');?> <?=lang('label_images');?>"
                               lang="dialog-article-images" ><?=lang('label_add');?></a>
                            
                        </div>
                    </div>
                    
                    <?php if(isset($created_by)){ ?>
                    <div class="box" >
	      	        <span class="header" ><?=lang('label_information');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label><?=lang('label_created_by');?>:</label></th>
                                    <td>
                                        <strong><?=User::getDetails($created_by, 'user');?></strong>
                                    </td>
                                </tr>                                                       

                                <tr>	      			
                                    <th><label><?=lang('label_created_on');?>:</label></th>
                                    <td>
                                        <strong><?=$created_on;?></strong>
                                    </td>
                                </tr>
                                
                                <?php if(isset($updated_by)){ ?>
                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>	      			
                                    <th><label><?=lang('label_updated_by');?>:</label></th>
                                    <td>
                                        <strong><?=isset($updated_by) ? User::getDetails($updated_by, 'user') : "";?></strong>
                                    </td>
                                </tr>

                                <tr>	      			
                                    <th><label><?=lang('label_updated_on');?>:</label></th>
                                    <td>
                                        <strong><?=isset($updated_on) ? $updated_on : "";?></strong>
                                    </td>
                                </tr>
                                <?php } ?>
                                
                                <tr>
                                    <th></th>
                                    <td style="text-align: right;">
                                        <a href = "<?=site_url('/articles/history/'.$id);?>"
                                           class = "load_jquery_ui_iframe"
                                           title = "<?=lang('label_article');?> <?=lang('label_history');?>"
                                           lang = "dialog-article-history" >Show full history</a>
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


