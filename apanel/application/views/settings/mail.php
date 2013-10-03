
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
	    <a href="<?php echo site_url('settings/');?>" class="styled cancel" ><?php echo lang('label_cancel');?></a>
		
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
	      	      <span class="header" ><?php echo lang('label_mail');?> <?php echo lang('label_settings');?></span>
	      	      
                      <div class="box_content" >
                        <table class="box_table" cellpadding="0" cellspacing="0" >

                            <tr>
                                <th><label><?php echo lang('label_from');?> <?php echo lang('label_email');?>:</label></th>
                                <td><input type="text" name="settings[from_email]" value="<?php echo set_value('settings[from_email]', isset($settings['from_email']) ? $settings['from_email'] : "");?>" ></td>
                            </tr>

                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_from');?> <?php echo lang('label_name');?>:</label></th>
                                <td><input type="text" name="settings[from_name]" value="<?php echo set_value('settings[from_name]', isset($settings['from_name']) ? $settings['from_name'] : "");?>" ></td>
                            </tr>
                            
                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_type');?>:</label></th>
                                <td>
                                    <?php $mailers_arr = array("mail" => "PHP Mail", "sendmail" => "Sendmail", "smtp" => "SMTP"); ?> 
                                    <select name="settings[mailer]" style="width: auto;" >
                                        <?php foreach($mailers_arr as $key => $mailer){ 
                                                $mailers = set_value('settings[mailer]', isset($settings['mailer']) ? $settings['mailer'] : ""); ?>                                        
                                        
                                        <option value="<?php echo $key;?>" <?php echo $mailers == $key ? "selected" : "";?> ><?php echo $mailer;?></option>
                                        
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_sendmail_path');?>:</label></th>
                                <td><input type="text" name="settings[sendmail]" value="<?php echo set_value('settings[sendmail]', isset($settings['sendmail']) ? $settings['sendmail'] : "/usr/sbin/sendmail");?>" ></td>
                            </tr>
                                                        
                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_ssmt');?> <?php echo lang('label_security');?>:</label></th>
                                <td>
                                    <?php $security_arr = array("" => "- - -", "ssl" => "SSL", "tls" => "TLS"); ?> 
                                    <select name="settings[ssmt_security]" style="width:auto;" >
                                        <?php foreach($security_arr as $key => $security){ 
                                                $ssmt_security = set_value('settings[ssmt_security]', isset($settings['ssmt_security']) ? $settings['ssmt_security'] : ""); ?>                                        
                                        
                                        <option value="<?php echo $key;?>" <?php echo $ssmt_security == $key ? "selected" : "";?> ><?php echo $security;?></option>
                                        
                                        <?php } ?>
                                    </select>
                            </tr>
                            
                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_ssmt');?> <?php echo lang('label_port');?>:</label></th>
                                <td><input type="text" name="settings[ssmt_port]" value="<?php echo set_value('settings[ssmt_port]', isset($settings['ssmt_port']) ? $settings['ssmt_port'] : "25");?>" ></td>
                            </tr>
                            
                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_ssmt');?> <?php echo lang('label_user');?>:</label></th>
                                <td><input type="text" name="settings[ssmt_user]" value="<?php echo set_value('settings[ssmt_user]', isset($settings['ssmt_user']) ? $settings['ssmt_user'] : "");?>" ></td>
                            </tr>
                            
                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_ssmt');?> <?php echo lang('label_password');?>:</label></th>
                                <td><input type="text" name="settings[ssmt_pass]" value="<?php echo set_value('settings[ssmt_pass]', isset($settings['ssmt_pass']) ? $settings['ssmt_pass'] : "");?>" ></td>
                            </tr>
                            
                            <tr><td colspan="2" class="empty_line" ></td></tr>
                            
                            <tr>
                                <th><label><?php echo lang('label_ssmt');?> <?php echo lang('label_host');?>:</label></th>
                                <td><input type="text" name="settings[ssmt_host]" value="<?php echo set_value('settings[ssmt_host]', isset($settings['ssmt_host']) ? $settings['ssmt_host'] : "");?>" ></td>
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
