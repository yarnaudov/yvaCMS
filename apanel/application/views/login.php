
<div id="header" style="height: auto;" >
    
    <div class="top_bar" >

	<div class="language_switch" >

	    <select id="language_switch" style="width: 120px;">
	    <?php foreach($this->config->item('lang_desc') as $abbr => $language){ ?>             

	    <option <?=$abbr == get_lang() ? 'selected' : '';?> value="<?=str_replace(site_url(), base_url().$abbr, current_url());?>" data-image="<?=base_url('img/'.$abbr.'.png');?>" >
		<?=$language;?>
	    </option>
	    <!--
	    <a href="<?=str_replace(site_url(), base_url().$abbr, current_url());?>" <?=(site_url() == base_url().$abbr) ? 'class="current"' : '';?> >
		    <img src="<?=base_url('img/'.$abbr.'.png');?>" >
		    <span><?=$language;?></span>
		</a>
	    -->
	    <?php } ?>
	    </select>
	</div>

	<div class="links" >
	    <a href="<?=base_url('../');?>" target="blank" ><?=lang('label_go_to_site');?></a>
	</div> 

    </div>
    
</div>

<form action="<?=current_url();?>" method="post">
    
    <div class="login" >
        
        <table cellspacing="0" cellpadding="0" >
            <tr>
             
                <td>
                    
                    <div class="login_intro" >
                    
                        <div class="text" >

                            <div class="logo" >
                                <img src="<?=base_url('img/iconAdministration.png');?>" >
                                <span><?=lang('label_tool_title');?></span>
                            </div>

                            Welcome to administration panel for simple menagment of your site content 

                        </div>
            
                        <div class="browsers" >

                            <span class="header" >Tested with</span>

                            <a href="http://www.firefox.com" target="_blank" >
                                <img src="<?=base_url('img/iconFirefox.png');?>" >
                                <span>Firefox</span>
                            </a>

                            <a href="https://www.google.com/chrome" target="_blank" >
                                <img src="<?=base_url('img/iconChrome.png');?>" >
                                <span>Chrome</span>
                            </a>

                            <a href="http://www.opera.com/" target="_blank" >
                                <img src="<?=base_url('img/iconOpera.png');?>" >
                                <span>Opera</span>
                            </a>

                            <a href="http://ie9.com" target="_blank" >
                                <img src="<?=base_url('img/iconIE9.png');?>" >
                                <span>IE 9</span>
                            </a>

                            <a href="http://www.apple.com/safari/" target="_blank" >
                                <img src="<?=base_url('img/iconSafari.png');?>" >
                                <span>Safari</span>
                            </a>

                        </div>
                        
                    </div>
                        
                </td>
        
                <td style="width: 30%;">
                    
                    <div class="login_form" >
                    
                        <div class="login_form_header">
                            <?=lang('label_login_header');?>
                        </div>

                        <!-- start messages -->
                        <?php $error_msg = $this->session->userdata('login_error_msg');
                            $this->session->unset_userdata('login_error_msg');
                            if(!empty($error_msg)){ ?>
                            <div class="error_msg" >
                                <?=$error_msg;?>            
                            </div>
                        <?php } ?>
                        <!-- end messages -->

                        <div>
                            <label><?=lang('label_user');?>:</label>
                            <input type="text" name="user" >
                        </div>

                        <div>
                            <label><?=lang('label_password');?>:</label>
                            <input type="password" name="pass" >
                        </div>

                        <button class="styled login" type="submit" name="login" ><?=lang('label_login');?></button>
                        
                    </div>
                        
                </td>
                
            </tr>
            
        </table>
        
    </div>
    
</form>

<script type="text/javascript" >
    $('input[name=user]').focus();
</script>