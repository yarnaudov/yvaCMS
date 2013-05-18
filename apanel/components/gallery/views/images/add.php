
<form name="add" action="<?=current_url();?>" method="post" enctype="multipart/form-data" >
	
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
	    <img src="<?=base_url('components/gallery/img/iconImages_25.png');?>" >
            <span><?=lang('com_gallery_label_gallery');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span><?=lang('com_gallery_label_images');?></span>
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
		
	    <button type="submit" name="save"                     class="styled save"   ><?=lang('label_save');?></button>
	    <button type="submit" name="apply"                    class="styled apply"  ><?=lang('label_apply');?></button>
	    <a href="<?=site_url('components/gallery/images');?>" class="styled cancel" ><?=lang('label_cancel');?></a>
		
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
                                <!--
                                <th rowspan="5" >
                                    <?php if(isset($id)){ ?>
                                    <a href="<?=base_url('../'.$this->config->item('images_dir').'/'.$id.'.'.$ext);?>" rel="lightbox" title="<?=$title;?>" >
                                        <img class="image" src="<?=base_url('../'.$this->config->item('thumbs_dir').'/'.$id.'.'.$ext);?>" > 
                                    </a>
                                    <?php }else{ ?>
                                    <img src="<?=base_url('img/no_photo.jpg');?>" >
                                    <?php } ?>
                                </th>
                                -->
                                <th><label class="multilang" ><?=lang('label_title');?>:</label></th>
                                <td><input type="text" name="title" value="<?=set_value('title', isset($title) ? $title : "");?>" ></td>
                            </tr>

                            <!--
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
                            -->                     
                        </table>
                      </div>
	      	              
                    </div>
	            <!-- mandatory information  -->
                    
                    <div class="box" >
                        <span class="header" ><?=lang('label_image');?></span>
                            <div class="box_content" >
                                <table class="box_table" cellpadding="0" cellspacing="0" border="0" >

                                    <?php if(isset($id)){ ?>
                                    <tr>
                                        <td colspan="2" >

                                            <?php $image_src  = base_url('../'.$this->config->item('images_dir').'/'.$id.'.'.$ext);
                                                  $image_data = getimagesize(FCPATH.'../'.$this->config->item('images_dir').'/'.$id.'.'.$ext); ?>

                                            <img style="width: 100%;" data-width="<?=$image_data[0];?>" data-height="<?=$image_data[1];?>" class="image_" id="jcrop_target" src="<?=$image_src.'?'.time();?>" >

                                            <input type="hidden" id="tmp" name="tmp" value="0" >

                                            <input type="hidden" name="x" id="x" >
                                            <input type="hidden" name="y" id="y" >
                                            <input type="hidden" name="x2" id="x2" >
                                            <input type="hidden" name="y2" id="y2" >

                                            <link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v0.9.12/css/jquery.Jcrop.min.css" type="text/css" />
                                            <script src="http://jcrop-cdn.tapmodo.com/v0.9.12/js/jquery.Jcrop.min.js"></script>
                                            <script src="<?=base_url('components/gallery/js/crop.js');?>"></script>

                                            <!-- <? //=$this->load->view('gallery/images/upload');?> -->
                                        </td>

                                    </tr>

                                    <tr><td colspan="2" class="empty_line" ></td></tr>

                                    <tr>
                                        <td colspan="2">

                                            <span><?=lang('com_gallery_label_aspect_ratio');?>:</span>
                                            <select id="aspectRatio" name="params[aspect_ratio]" style="width: 50px;" >
                                                <?=create_options_array($this->config->item('aspect_ratio'), set_value('params[aspect_ratio]', isset($album_params['aspect_ratio']) ? $album_params['aspect_ratio'] : ""));?>
                                            </select>

                                            <input type="hidden" id="min_width"  value="<?=isset($album_params['min_width']) ? $album_params['min_width'] : '';?>" >
                                            <input type="hidden" id="min_height" value="<?=isset($album_params['min_height']) ? $album_params['min_height'] : '';?>" >
                                            <input type="hidden" id="max_width"  value="<?=isset($album_params['max_width']) ? $album_params['max_width'] : '';?>" >
                                            <input type="hidden" id="max_height" value="<?=isset($album_params['max_height']) ? $album_params['max_height'] : '';?>" >

                                            <span><?=lang('com_gallery_label_width');?>: </span><input type="text" readonly name="w" id="w" style="width: 30px;" >
                                            <span><?=lang('com_gallery_label_height');?>: </span><input type="text" readonly name="h" id="h" style="width: 30px;" >
                                            <button class="styled styled_small crop" type="button" lang="<?=site_url('components/gallery/images/crop');?>" ><?=lang('com_gallery_label_crop');?></button>

                                            &nbsp;&nbsp;|&nbsp;&nbsp;

                                            <select id="degrees" style="width: 50px;" >
                                                <?=create_options_array($this->config->item('rotate_degrees'), isset($params['rotate_degree']) ? $params['rotate_degree'] : '');?>
                                            </select>
                                            <button class="styled styled_small rotate" type="button" data-url="<?=site_url('components/gallery/images/rotate');?>" ><?=lang('com_gallery_label_rotate');?></button>
                                            
                                            &nbsp;&nbsp;|&nbsp;&nbsp;

                                            <?php $image_data = getimagesize(FCPATH.'../'.$this->config->item('images_origin_dir').'/'.$id.'.'.$ext); ?>
                                            <button class="styled styled_small origin" type="button" size="<?=$image_data[0];?>" desc="<?=$id;?>" lang="<?=site_url('components/gallery/images/origin/');?>" ><?=lang('com_gallery_label_load_original');?></button>
                                            <button class="styled styled_small rename" type="button" onclick="$('.file_conteiner').toggle('slow');" ><?=lang('com_gallery_label_change');?></button>


                                        </td>

                                    </tr>

                                    <?php } ?>

                                    <tbody class="file_conteiner" <?=(isset($id) ? 'style="display: none;"' : '');?> >

                                        <?php if(isset($id)){ ?>
                                        <tr><td colspan="2" class="empty_line" >&nbsp;<hr/>&nbsp;</td></tr>
                                        <?php } ?>

                                        <tr>                                            
                                            <th><label><?=lang('label_file');?>: </label></th>
                                            <td>
                                                <div class="input_file" >
                                                    <input type="file" name="file" size="30" class="file">
                                                    <button type="button" class="styled" >Browse</button>                                                    
                                                    <input type="text" class="text">
                                                </div>
                                                <?php if(isset($id)){ ?>
                                                <button type="button" class="styled styled_small" id="btn_change_image" data-url="<?=site_url('components/gallery/images/change/'.$id);?>" ><?=lang('com_gallery_label_change');?></button>
                                                <?php } ?>
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>
                            </div>
                    </div>

	            
                    <div class="box" >
	      	        <span class="header multilang" ><?=lang('label_description');?></span>
                        <div class="editor_div" >
                          <textarea name="description" class="editor" ><?=set_value('description', isset($description) ? $description : "");?></textarea>
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
                                            <?=create_options('languages', 'id', 'title', $this->language_id, array('status' => 'yes') );?>
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
                                    <th><label><?=lang('com_gallery_label_album');?>:</label></th>
                                    <td>
                                        <select name="album" >
                                            <?=create_options_array($albums, set_value('album', isset($album_id) ? $album_id : ""));?>
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
                                            <?=create_options('languages', 'id', 'title', set_value('show_in_language', isset($show_in_language) ? $show_in_language : ""), array('status' => 'yes') );?>
                                        </select>
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


