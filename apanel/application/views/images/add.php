
<form name="add" action="<?=current_url();?>" method="post" enctype="multipart/form-data" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
	    <img src="<?=base_url('img/iconImages_25.png');?>" >
            <span><?=lang('label_images');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span>
              <?php if(isset($image_id)){
                      echo lang('label_edit');
                    }
                    else{
                      echo lang('label_add');  
                    } ?>
            </span>
	</div>
	
	<div class="actions" >
		
	    <button type="submit" name="save"   class="styled save"   ><?=lang('label_save');?></button>
	    <button type="submit" name="apply"  class="styled apply"  ><?=lang('label_apply');?></button>
	    <a href="<?=site_url('images/');?>" class="styled cancel" ><?=lang('label_cancel');?></a>
		
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
                        <table class="box_table" cellpadding="0" cellspacing="0" border="0">
                                                        
                            <tr>
                                <th rowspan="5" >
                                    <?php if(isset($image_id)){ ?>
                                    <a href="<?=base_url('../'.$this->config->item('images_dir').'/'.$image_id.'.'.$ext);?>" rel="lightbox" title="<?=${'title_'.$this->trl};?>" >
                                        <img class="image" src="<?=base_url('../'.$this->config->item('thumbs_dir').'/'.$image_id.'.'.$ext);?>" > 
                                    </a>
                                    <?php }else{ ?>
                                    <img src="<?=base_url('img/no_photo.jpg');?>" >
                                    <?php } ?>
                                </th>
                                <th><label class="multilang" ><?=lang('label_title');?>:</label></th>
                                <td><input type="text" name="title" value="<?=set_value('title', isset(${'title_'.$this->trl}) ? ${'title_'.$this->trl} : "");?>" ></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>

                            <tr>
                                <th><label><?=lang('label_file');?>:&nbsp;*</label></th>
                                <td>
                                    <div class="input_file" >
                                        <input type="file" name="file" size="30" class="file">
                                        <button type="button" class="styled" >Browse</button>
                                        <input type="text" class="text">
                                    </div>
                                    
                                </td>
                            </tr>
                            
                            <tr>
                                <th><label><?=lang('label_size');?>:</label></th>
                                <td>
                                     <?php $image_width = set_value('image_width', isset($image_width) ? $image_width : "");
                                           $image_width = $image_width == "" ? $this->config->item('default_image_width') : $image_width; ?>
                                    <select name="image_width" style="width: 55px;" >
                                        <?=create_options_array($this->config->item('image_width'), $image_width);?>
                                    </select>&nbsp;x

                                    <?php $image_height = set_value('image_height', isset($image_height) ? $image_height : "");
                                        $image_height = $image_height == "" ? $this->config->item('default_image_height') : $image_height; ?>
                                    <select name="image_height" style="width: 55px;" >
                                        <?=create_options_array($this->config->item('image_height'), $image_height);?>
                                    </select> px&nbsp;&nbsp;&nbsp;

                                    <?php $thumb_width = set_value('thumb_width', isset($thumb_width) ? $thumb_width : "");
                                        $thumb_width = $thumb_width == "" ? $this->config->item('default_thumb_width') : $thumb_width; ?>
                                    <select name="thumb_width" style="width: 55px;" >
                                        <?=create_options_array($this->config->item('thumb_width'), $thumb_width);?>
                                    </select>&nbsp;x

                                    <?php $thumb_height = set_value('thumb_height', isset($thumb_height) ? $thumb_height : "");
                                        $thumb_height = $thumb_height == "" ? $this->config->item('default_thumb_height') : $thumb_height; ?>
                                    <select name="thumb_height" style="width: 55px;" >
                                        <?=create_options_array($this->config->item('thumb_height'), $thumb_height);?>
                                    </select> px
                                </td>
                            </tr>
                            
                            <tr>
                                <th></th>
                                <td>* <?=lang('msg_image_info');?></td>
                            </tr>
                                                        
                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
	            
                    <div class="box" >
	      	        <span class="header multilang" ><?=lang('label_description');?></span>
                        <div class="editor_div" >
                          <textarea name="description" class="editor" ><?=set_value('description', isset(${'description_'.$this->trl}) ? ${'description_'.$this->trl} : "");?></textarea>
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
                                            <?=create_options('languages', 'abbreviation', 'title', $this->trl, array('status' => 'yes') );?>
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
                                            <?=create_options('categories', 'category_id', 'title_'.Language::getDefault(), set_value('category', isset($category_id) ? $category_id : ""), array('extension' => 'images', 'status' => 'yes') );?>
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
                                        <select name="language" >
                                            <option value="all" ><?=lang('label_all');?></option>
                                            <?=create_options('languages', 'language_id', 'title', set_value('translation', isset($language_id) ? $language_id : ""), array('status' => 'yes') );?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr><td colspan="2" class="empty_line" ></td></tr>
                                
                                <tr>	      			
                                    <th><label><?=lang('label_article');?>:</label></th>
                                    <td>                                                                               
                                        <input type="hidden" name="article" id="article" >
                                        
                                        <?php 
                                            $article = set_value('article', isset($article_id) ? $article_id : "");
                                            $article = $article != "" ? $this->Article->getDetails($article, 'title_'.Language::getDefault()) : "";
                                        ?>
                                        <input type="text" readonly name="article_name" id="article_name" value="<?=$article;?>" style="width: 70%">
                                        <a href="<?=site_url('articles');?>" 
                                           class = "load_jquery_ui_iframe" 
                                           lang  = "dialog-select-article" ><?=lang('label_select');?></a>
                                        
                                        <!-- start jquery UI -->
                                        <div id="dialog-select-article"
                                             class = "jquery_ui_iframe"
                                             title="<?=lang('label_select').' '.lang('label_article');?>" 
                                             lang="<?=site_url('articles/index/simple_ajax');?>" ></div>
                                        
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
                    
                    <?php if(isset($created_by)){ ?>
                    <div class="box" >
	      	        <span class="header" ><?=lang('label_information');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>	      			
                                    <th><label><?=lang('label_size');?>:</label></th>
                                    <td>
                                        <?php $image_data = getimagesize(base_url('../'.$this->config->item('images_dir').'/'.$image_id.'.'.$ext));
                                              $thumb_data = getimagesize(base_url('../'.$this->config->item('thumbs_dir').'/'.$image_id.'.'.$ext)); ?>
                                        <span class="image_size">
                                            <strong><?=$image_data[0];?></strong>x<strong><?=$image_data[1];?></strong>px,
                                            <strong><?=$thumb_data[0];?></strong>x<strong><?=$thumb_data[1];?></strong>px
                                        </span>
                                    </td>
                                </tr>
                                
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


