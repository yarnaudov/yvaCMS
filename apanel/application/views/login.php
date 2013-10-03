
<div id="header" style="height: auto;" >
    
    <div class="top_bar" >

	<div class="language_switch" >

	    <select id="language_switch" style="width: 120px;">
	    <?php foreach($this->config->item('lang_desc') as $abbr => $language){ ?>             

	    <option <?php echo $abbr == get_lang() ? 'selected' : '';?> value="<?php echo str_replace(site_url(), base_url().$abbr, current_url());?>" data-image="<?php echo base_url('img/'.$abbr.'.png');?>" >
		<?php echo $language;?>
	    </option>
	    <!--
	    <a href="<?php echo str_replace(site_url(), base_url().$abbr, current_url());?>" <?php echo (site_url() == base_url().$abbr) ? 'class="current"' : '';?> >
		    <img src="<?php echo base_url('img/'.$abbr.'.png');?>" >
		    <span><?php echo $language;?></span>
		</a>
	    -->
	    <?php } ?>
	    </select>
	</div>

	<div class="links" >
	    <a href="<?php echo base_url('../');?>" target="blank" ><?php echo lang('label_go_to_site');?></a>
	</div> 

    </div>
    
</div>

<form action="<?php echo current_url();?>" method="post">
    
    <div class="login" >
        
        <table cellspacing="0" cellpadding="0" >
            <tr>
             
                <td>
                    
                    <div class="login_intro" >
                    
                        <div class="text" >

                            <div class="logo" >
                                <img src="<?php echo base_url('img/iconAdministration.png');?>" >
                                <span><?php echo lang('label_tool_title');?></span>
                            </div>

			    <?php echo lang('login_text');?>

                        </div>
            
                        <div class="browsers" >

                            <span class="header" ><?php echo lang('label_tested_with');?></span>

                            <a href="http://www.firefox.com" target="_blank" >
                                <img src="<?php echo base_url('img/iconFirefox.png');?>" >
                                <span>Firefox</span>
                            </a>

                            <a href="https://www.google.com/chrome" target="_blank" >
                                <img src="<?php echo base_url('img/iconChrome.png');?>" >
                                <span>Chrome</span>
                            </a>

                            <a href="http://www.opera.com/" target="_blank" >
                                <img src="<?php echo base_url('img/iconOpera.png');?>" >
                                <span>Opera</span>
                            </a>

                            <a href="http://ie9.com" target="_blank" >
                                <img src="<?php echo base_url('img/iconIE9.png');?>" >
                                <span>IE 9</span>
                            </a>

                            <a href="http://www.apple.com/safari/" target="_blank" >
                                <img src="<?php echo base_url('img/iconSafari.png');?>" >
                                <span>Safari</span>
                            </a>

                        </div>
                        
                    </div>
                        
                </td>
        
                <td style="width: 30%;">
                    
                    <div class="login_form" >
                    
                        <div class="login_form_header">
                            <?php echo lang('label_login_header');?>
                        </div>

                        <!-- start messages -->
                        <?php $error_msg = $this->session->userdata('login_error_msg');
                            $this->session->unset_userdata('login_error_msg');
                            if(!empty($error_msg)){ ?>
                            <div class="error_msg" >
                                <?php echo $error_msg;?>            
                            </div>
                        <?php } ?>
                        <!-- end messages -->

                        <div>
                            <label><?php echo lang('label_user');?>:</label>
                            <input type="text" name="user" >
                        </div>

                        <div>
                            <label><?php echo lang('label_password');?>:</label>
                            <input type="password" name="pass" >
                        </div>

                        <button class="styled login" type="submit" name="login" ><?php echo lang('label_login');?></button>
                        
                    </div>
                        
                </td>
                
            </tr>
            
        </table>
        
    </div>
    
</form>

<script type="text/javascript" >
    $('input[name=user]').focus();
</script>