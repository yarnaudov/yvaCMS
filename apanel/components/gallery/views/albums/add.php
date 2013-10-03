
<form name="add" action="<?php echo current_url();?>" method="post" enctype="multipart/form-data" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
	    <img src="<?php echo base_url('components/gallery/img/iconAlbums_25.png');?>" >
            <span><?php echo lang('com_gallery_label_gallery');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span><?php echo lang('com_gallery_label_albums');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span>
              <?php if(isset($album_id)){
                      echo lang('label_edit');
                    }
                    else{
                      echo lang('label_add');  
                    } ?>
            </span>
	</div>
	
	<div class="actions" >
		
	    <button type="submit" name="save"                     class="styled save"   ><?php echo lang('label_save');?></button>
	    <button type="submit" name="apply"                    class="styled apply"  ><?php echo lang('label_apply');?></button>
	    <a href="<?php echo site_url('components/gallery/albums');?>" class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
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
	      	      <span class="header" ><?php echo lang('label_mandatory');?> <?php echo lang('label_information');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" border="0">
                                                        
                            <tr>
                                <th><label class="multilang" ><?php echo lang('label_title');?>:</label></th>
                                <td><input class="required" type="text" name="title" value="<?php echo set_value('title', isset($title) ? $title : "");?>" ></td>
                            </tr>
                                                        
                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
	            
                    <div class="box" >
	      	        <span class="header multilang" ><?php echo lang('label_description');?></span>
                        <div class="editor_div" >
                          <textarea name="description" class="editor" ><?php echo set_value('description', isset($description) ? $description : "");?></textarea>
                        </div>
	            </div>
	      
                </td>
                <!-- end left content  -->
	        
                
                <!-- start right content  -->
	        <td class="right" >
	      
                    <div class="box" >
                        <span class="header" ><?php echo lang('label_translation');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>
                                    <td>
                                        <select name="translation" >
                                            <?php echo create_options('languages', 'id', 'title', $this->language_id, array('status' => 'yes') );?>
                                        </select>
                                    </td>
                                </tr>

                            </table>
                        </div>
                        
	            </div>
                    
	      
	            <div class="box" >
	      	        <span class="header" ><?php echo lang('label_options');?></span>
	                
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

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
                                            <?php echo create_options('languages', 'id', 'title', set_value('show_in_language', isset($show_in_language) ? $show_in_language : ""), array('status' => 'yes') );?>
                                        </select>
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>
                                    <td colspan="2" class="empty_line">
                                        <fieldset style="border:none;border-top: 1px solid #aaa;padding-left: 10px;">
                                            <legend style="font-weight: bold;padding: 0 5px;"><?php echo lang('com_gallery_label_water_crop_settings');?></legend>
                                        </fieldset>
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>                    
                                    <th><label><?php echo lang('com_gallery_label_min_width');?>:</label></th>
                                    <td>
                                        <input type="text" name="params[min_width]" value="<?php echo set_value('params[min_width]', isset($params['min_width']) ? $params['min_width'] : "");?>" >
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>                    
                                    <th><label><?php echo lang('com_gallery_label_min_height');?>:</label></th>
                                    <td>
                                        <input type="text" name="params[min_height]" value="<?php echo set_value('params[min_height]', isset($params['min_height']) ? $params['min_height'] : "");?>" >
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>                    
                                    <th><label><?php echo lang('com_gallery_label_max_width');?>:</label></th>
                                    <td>
                                        <input type="text" name="params[max_width]" value="<?php echo set_value('params[max_width]', isset($params['max_width']) ? $params['max_width'] : "");?>" >
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>                    
                                    <th><label><?php echo lang('com_gallery_label_max_height');?>:</label></th>
                                    <td>
                                        <input type="text" name="params[max_height]" value="<?php echo set_value('params[max_height]', isset($params['max_height']) ? $params['max_height'] : "");?>" >
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>                    
                                    <th><label><?php echo lang('com_gallery_label_aspect_ratio');?>:</label></th>
                                    <td>
                                        <select name="params[aspect_ratio]" >
                                            <?php echo create_options_array($this->config->item('aspect_ratio'), set_value('params[aspect_ratio]', isset($params['aspect_ratio']) ? $params['aspect_ratio'] : ""));?>
                                        </select>
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>
                                    <td colspan="2" class="empty_line">
                                        <fieldset style="border:none;border-top: 1px solid #aaa;padding-left: 10px;">
                                            <legend style="font-weight: bold;padding: 0 5px;"><?php echo lang('com_gallery_label_water_mark');?></legend>
                                        </fieldset>
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tr>                    
                                    <th><label><?php echo lang('label_type');?>:</label></th>
                                    <td>
                                        <?php $water_mark_type = set_value('params[water_mark_type]', isset($params['water_mark_type']) ? $params['water_mark_type'] : ""); ?>
                                        <select class="water_mark_type" name="params[water_mark_type]" >
                                            <option value="" >- - -</option>
                                            <option value="text"  <?php echo $water_mark_type == 'text'  ? 'selected' : '';?> ><?php echo lang('label_text');?></option>
                                            <option value="image" <?php echo $water_mark_type == 'image' ? 'selected' : '';?> ><?php echo lang('label_image');?></option>
                                        </select>
                                    </td>
                                </tr>

                                <tr><td colspan="2" class="empty_line" ></td></tr>

                                <tbody id="water_mark_image" style="display: none;" >

                                    <tr>                    
                                        <th><label><?php echo lang('label_image');?>:</label></th>
                                        <td>
                                            <input class="image" type="text" readonly name="params[water_mark_image]" id="album_font" value="<?php echo set_value('params[water_mark_image]', isset($params['water_mark_image']) ? $params['water_mark_image'] : "");?>" style="width: 58%">
                                           
                                            <a href  = "<?php echo site_url('media/browse');?>" 
                                               class = "load_jquery_ui_iframe"
                                               title = "<?php echo lang('label_browse').' '.lang('label_media');?>"
                                               lang  = "dialog-media-browser"
					       target = "album_font" ><?php echo lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                                                             class = "clear_jquery_ui_inputs"
                                                                                                                             lang  = "image" ><?php echo lang('label_clear');?></a>
                                                                                    
                                        </td>
                                    </tr>

                                    <tr><td colspan="2" class="empty_line" ></td></tr>

                                </tbody>

                                <tbody id="water_mark_text" style="display: none;" >

                                    <tr>                    
                                        <th><label><?php echo lang('label_text');?>:</label></th>
                                        <td>
                                            <input type="text" name="params[water_mark_text]" value="<?php echo set_value('params[water_mark_text]', isset($params['water_mark_text']) ? $params['water_mark_text'] : "");?>" >
                                        </td>
                                    </tr>

                                    <tr><td colspan="2" class="empty_line" ></td></tr>

                                    <tr>                    
                                        <th><label><?php echo lang('label_size');?>:</label></th>
                                        <td>
                                            <input type="text" name="params[water_mark_size]" value="<?php echo set_value('params[water_mark_size]', isset($params['water_mark_size']) ? $params['water_mark_size'] : "");?>" >
                                        </td>
                                    </tr>

                                    <tr><td colspan="2" class="empty_line" ></td></tr>

                                    <tr>
                                        <th><label><?php echo lang('com_gallery_label_font');?>:</label></th>
                                        <td>                                              
                                            <input class="water_mark_font" type="text" readonly name="params[water_mark_font]" id="water_mark_font" value="<?php echo set_value('params[water_mark_font]', isset($params['water_mark_font']) ? $params['water_mark_font'] : "");?>" style="width: 58%">
                                            <a href   = "<?php echo site_url('media/browse');?>" 
                                               class  = "load_jquery_ui_iframe"
                                               title  = "<?php echo lang('label_browse').' '.lang('label_media');?>"
                                               lang   = "dialog-media-browser"
                                               target = "water_mark_font" ><?php echo lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
                                                                                                                         class = "clear_jquery_ui_inputs"
                                                                                                                         lang  = "water_mark_font" ><?php echo lang('label_clear');?></a>
                                        </td>
                                    </tr>

                                    <tr><td colspan="2" class="empty_line" ></td></tr>

                                </tbody>

                                <tr>                    
                                    <th><label><?php echo lang('label_position');?>:</label></th>
                                    <td>
                                        <select name="params[water_mark_position]" >
                                            <?php echo create_options_array($this->config->item('water_mark_positions'), set_value('params[water_mark_position]', isset($params['water_mark_position']) ? $params['water_mark_position'] : ""));?>
                                        </select>
                                    </td>
                                </tr>
  
                            </table>
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


