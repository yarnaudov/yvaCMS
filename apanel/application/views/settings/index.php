
<form name="list" action="<?php echo current_url(true);?>" method="post" >
        
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?php echo base_url('img/iconSettings_25.png');?>" >
            <span><?php echo lang('label_settings');?></span>
        </div>
	
	<div class="actions" >
	    
            <button type="submit" name="save"     class="styled save"   ><?php echo lang('label_save');?></button>
	    <button type="submit" name="apply"    class="styled apply"  ><?php echo lang('label_apply');?></button>
	    <a href="<?php echo site_url('');?>"          class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
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
	        <td class="left" >
	            
                    <!-- mandatory information  -->
	            <div class="box" >
	      	      <span class="header" ><?php echo lang('label_general');?> <?php echo lang('label_information');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label class="multilang" ><?php echo lang('label_site_name');?>:</label></th>
                                <td><input type="text" name="settings[site_name]" value="<?php echo set_value('settings[site_name]', isset($settings['site_name']) ? $settings['site_name'] : "");?>" ></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th></th>
                                <td>                                    
                                    <label style="display: inline;"><?php echo lang('label_site_name_in_title');?>:</label>
                                    <select name="settings[site_name_in_title]" style="width: auto;" >
                                        <?php echo create_options_array($this->config->item('yes_no'), set_value('settings[site_name_in_title]', isset($settings['site_name_in_title']) ? $settings['site_name_in_title']: ""));?>
                                    </select>
                                    <span>&nbsp;<?php echo lang('label_with_separator');?>&nbsp;</span>
                                    <input type="text" style="width: 40px;" name="settings[site_name_in_title_separator]" value="<?php echo set_value('settings[site_name_in_title_separator]', isset($settings['site_name_in_title_separator']) ? $settings['site_name_in_title_separator'] : "");?>" >
                                </td>
                            </tr>
                            
                            <?php $this->load->view('templates', array('name' => 'settings[template]', 'template' => set_value('settings[template]', isset($settings['template']) ? $settings['template'] : ''))); ?>
                               
			    <tr><td colspan="2" class="empty_line" ></td></tr>
			    
			    <tr>
                                <th></th>
                                <td>                                    
                                    <label style="display: inline;"><?php echo lang('label_default_language_in_url');?>:</label>
                                    <select name="settings[default_language_in_url]" style="width: auto;" >
                                        <?php echo create_options_array($this->config->item('yes_no'), set_value('settings[default_language_in_url]', isset($settings['default_language_in_url']) ? $settings['default_language_in_url']: ""));?>
                                    </select>                                    
                                </td>
                            </tr>
			    
                        </table>
                      </div>
	      	              
                    </div>
                    
                    <div class="box" >
	      	      <span class="header" ><?php echo lang('label_metadata');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label class="multilang" ><?php echo lang('label_description');?>:</label></th>
                                <td><textarea name="settings[meta_description]" ><?php echo set_value('settings[meta_description]', isset($settings['meta_description']) ? $settings['meta_description'] : "");?></textarea></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>

                            <tr>
                                <th><label class="multilang" ><?php echo lang('label_keywords');?>:</label></th>
                                <td><textarea name="settings[meta_keywords]" ><?php echo set_value('settings[meta_keywords]', isset($settings['meta_keywords']) ? $settings['meta_keywords'] : "");?></textarea></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>

                            <tr>
                                <th><label><?php echo lang('label_robots');?>:</label></th>
                                <td>
                                    <?php $robots_arr = array("" => "Index, Follow", "noindex, follow" => "No index, follow", "index, nofollow" => "Index, No follow", "noindex, nofollow" => "No index, no follow"); ?> 
                                    <select name="settings[robots]" style="width: auto;" >
                                        <?php foreach($robots_arr as $key => $robot){ 
                                                $robots = set_value('settings[robots]', isset($settings['robots']) ? $settings['robots'] : ""); ?>
                                        
                                        <option value="<?php echo $key;?>" <?php echo $robots == $key ? "selected" : "";?> ><?php echo $robot;?></option>
                                        
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            
                        </table>
                      </div>
	      	              
                    </div>
		    
		    <div class="box" >
	      	      <span class="header" ><?php echo lang('label_system');?> <?php echo lang('label_settings');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label><?php echo lang('label_environment');?>:</label></th>
                                <td>
				    <?php $environments = array('development' => 'Development', 'testing' => 'Testing', 'production' => 'Production'); ?>
				    <select name="settings[environment]" style="width: auto;" >
					 <?php echo create_options_array($environments, set_value('settings[environment]', isset($settings['environment']) ? $settings['environment']: ""));?>
                                    </select>				    
				</td>
                            </tr>
			    
                        </table>
                      </div>
	      	              
                    </div>
                    
                </td>
                
                <!-- start right content  -->
	        <td class="right" >
	      
                    <div class="box" >
                        <span class="header" ><?php echo lang('label_translation');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>
                                    <td>
                                        <select name="translation" >
                                            <?php echo create_options('languages', 'id', 'title', $this->language_id, array('status' => 'yes'));?>
                                        </select>
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
