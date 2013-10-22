
<form name="list" action="<?php echo current_url(true);?>" method="post" >

    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >            
            <img src="<?php echo base_url('img/iconMedia_25.png');?>" >            
            <span><?php echo lang('label_media');?></span>
	    <span>&nbsp;»&nbsp;</span>
            <span><?php echo lang('label_settings');?></span>
        </div>
	
	<div class="actions" >
		
            <button type="submit" name="save"     class="styled save"   ><?php echo lang('label_save');?></button>
	    <button type="submit" name="apply"    class="styled apply"  ><?php echo lang('label_apply');?></button>
	    <a href="<?php echo site_url('media');?>" class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
	</div>
	
    </div>
    <!-- end page header -->
    

    <!-- start page content -->
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>
    <!-- start page content -->

    <?php echo $this->load->view('messages');?>

    <!-- start page content -->
    <div id="page_content" >

	<table class="add" cellpadding="0" cellspacing="0" >
            
            <tr>
                
                <!-- start left content  -->
	        <td class="left" style="padding: 0;" >
	            
                    <!-- mandatory information  -->
	            <div class="box" >
	      	      <span class="header" ><?php echo lang('label_media');?> <?php echo lang('label_settings');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label><?php echo lang('label_media_max_file_size');?>:</label></th>
                                <td>
				    <?php $media_file_size = set_value('settings[media_file_size]', $settings['media_file_size']); ?>
				    <input type="text" name="settings[media_file_size]" value="<?php echo $media_file_size; ?>" style="width: 100px;" >
				    &nbsp;<strong>KB</strong> (<?php echo round($media_file_size/1024, 2); ?>&nbsp;<strong>MB</strong>)
				</td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>
	
			    <tr>
                                <th><label><?php echo lang('label_media_allowed_extensions');?>:</label></th>
                                <td>
				    <div class="menu_list" >
					<table class="menu_list" cellpadding="0" cellspacing="0" >

					    <?php $sel_mimes = set_value('settings[media_file_ext]', $settings['media_file_ext']);
						  foreach($mimes as $ext => $mime){ 
						      $checked = "";
						      if(@in_array($ext, $sel_mimes)){
							  $checked = "checked";
						      } ?>

					    <tr>
						<td style="width: 1%;" >
						    <input class="categories" type="checkbox" <?php echo $checked;?> name="settings[media_file_ext][]" id="category<?php echo $ext;?>" value="<?php echo $ext;?>" >
						</td>
						<td>
						    <label for="category<?php echo $ext;?>" ><?php echo $ext;?></label>
						</td>
					    </tr>

					    <?php } ?>

					</table>
				    </div>
				</td>
                            </tr>
			    
			 </table>
                      </div>
	      	              
                    </div>
                    
                    
                </td>
                
                
            </tr>
            
        </table>
    </div>
    <!-- end page content -->

</form>