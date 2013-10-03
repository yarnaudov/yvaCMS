<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
  
<?php $this->load->view('header'); ?>

<div id="wrap" >
    
<div id="main" >
                 
    <div id="header" >

        <div class="logo" >
            <a href="<?php echo site_url();?>" >
                <img src="<?php echo base_url('img/iconAdministration.png');?>" >
                <span><?php echo lang('label_tool_title');?></span>
            </a>
        </div>
        
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

        <div class="user" >
            <span><?php echo lang('label_logged_in_as');?>:</span>
            <img src="<?php echo base_url('img/iconUser2.png');?>" >
            
            <span>
                <a href="<?php echo site_url('/users/edit/'.$this->user['id']);?>" >
                    <strong><?php echo $this->user['user'];?></strong>
                </a>
                <span>(</span>
                <a href="<?php echo site_url('/groups/edit/'.$this->user['user_group_id'].'/users');?>" ><?php echo $this->user['user_group_title'];?></a>
                <span>), </span>
            </span>
            
            <img src="<?php echo base_url('img/iconLogout.png');?>" >
            <a href="<?php echo site_url('logout');?>" ><?php echo lang('label_logout');?></a>
        </div>


    </div>

    <div id="menu" >
        <?php $this->load->view('menu'); ?>
    </div>

    <div id="content" >
    <?php echo $content; ?>
    </div>
  
</div>

</div>

<div id="footer" >
    
  <div id="copyright" >
  	Administration Panel &copy; 2012 
  	<a href="#" >All rights reserved</a>
  	<br/>
  	designed by 
  	<a href="#" >Yordan Arnaudov</a>
  </div>
  
</div>
    
<?php $this->load->view('footer'); ?>