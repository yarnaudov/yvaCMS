
<form name="list" action="<?=current_url(true);?>" method="post" >
        
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            <img src="<?=base_url('img/iconSettings_25.png');?>" >
            <span><?=lang('label_settings');?></span>
        </div>
	
	<div class="actions" >
	    
            <button type="submit" name="save"     class="styled save"   ><?=lang('label_save');?></button>
	    <button type="submit" name="apply"    class="styled apply"  ><?=lang('label_apply');?></button>
	    <a href="<?=site_url('');?>"          class="styled cancel" ><?=lang('label_cancel');?></a>
		
	</div>
        
    </div>
    <!-- end page header -->

    <!-- start page content -->
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>
    <!-- start page content -->


    <!-- start messages -->
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
	      	      <span class="header" ><?=lang('label_general');?> <?=lang('label_information');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label class="multilang" ><?=lang('label_site_name');?>:</label></th>
                                <td><input type="text" name="settings[site_name]" value="<?=set_value('settings[site_name]', isset($settings['site_name']) ? $settings['site_name'] : "");?>" ></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th></th>
                                <td>                                    
                                    <label style="display: inline;"><?=lang('label_site_name_in_title');?>:</label>
                                    <select name="settings[site_name_in_title]" style="width: auto;" >
                                        <?=create_options_array($this->config->item('yes_no'), set_value('settings[site_name_in_title]', isset($settings['site_name_in_title']) ? $settings['site_name_in_title']: ""));?>
                                    </select>
                                    <span>&nbsp;<?=lang('label_with_separator');?>&nbsp;</span>
                                    <input type="text" style="width: 40px;" name="settings[site_name_in_title_separator]" value="<?=set_value('settings[site_name_in_title_separator]', isset($settings['site_name_in_title_separator']) ? $settings['site_name_in_title_separator'] : "");?>" >
                                </td>
                            </tr>
                            
                            <?php $this->load->view('templates', array('name' => 'settings[template]', 'template' => set_value('settings[template]', isset($settings['template']) ? $settings['template'] : ''))); ?>
                                                        
                        </table>
                      </div>
	      	              
                    </div>
                    
                    <div class="box" >
	      	      <span class="header" ><?=lang('label_metadata');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label class="multilang" ><?=lang('label_description');?>:</label></th>
                                <td><textarea name="settings[meta_description]" ><?=set_value('settings[meta_description]', isset($settings['meta_description']) ? $settings['meta_description'] : "");?></textarea></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>

                            <tr>
                                <th><label class="multilang" ><?=lang('label_keywords');?>:</label></th>
                                <td><textarea name="settings[meta_keywords]" ><?=set_value('settings[meta_keywords]', isset($settings['meta_keywords']) ? $settings['meta_keywords'] : "");?></textarea></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>

                            <tr>
                                <th><label><?=lang('label_robots');?>:</label></th>
                                <td>
                                    <?php $robots_arr = array("" => "Index, Follow", "noindex, follow" => "No index, follow", "index, nofollow" => "Index, No follow", "noindex, nofollow" => "No index, no follow"); ?> 
                                    <select name="settings[robots]" style="width: auto;" >
                                        <?php foreach($robots_arr as $key => $robot){ 
                                                $robots = set_value('settings[robots]', isset($settings['robots']) ? $settings['robots'] : ""); ?>
                                        
                                        <option value="<?=$key;?>" <?=$robots == $key ? "selected" : "";?> ><?=$robot;?></option>
                                        
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            
                        </table>
                      </div>
	      	              
                    </div>
		    
		    <div class="box" >
	      	      <span class="header" ><?=lang('label_system');?> <?=lang('label_settings');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label><?=lang('label_environment');?>:</label></th>
                                <td>
				    <?php $environments = array('development' => 'Development', 'testing' => 'Testing', 'production' => 'Production'); ?>
				    <select name="settings[environment]" style="width: auto;" >
					 <?=create_options_array($environments, set_value('settings[environment]', isset($settings['environment']) ? $settings['environment']: ""));?>
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
                        <span class="header" ><?=lang('label_translation');?></span>
                        
                        <div class="box_content" >
                            <table class="box_table" cellpadding="0" cellspacing="0" >

                                <tr>
                                    <td>
                                        <select name="translation" >
                                            <?=create_options('languages', 'id', 'title', $this->language_id, array('status' => 'yes'));?>
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
