<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
  
<?php $this->load->view('header'); ?>

<div id="wrap" >
    
<div id="main" >
                 
    <div id="header" >

        <div class="logo" >
            <a href="<?=site_url();?>" >
                <img src="<?=base_url('img/iconAdministration.png');?>" >
                <span><?=lang('label_tool_title');?></span>
            </a>
        </div>
        
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

        <div class="user" >
            <span><?=lang('label_logged_in_as');?>:</span>
            <img src="<?=base_url('img/iconUser2.png');?>" >
            
            <span>
                <a href="<?=site_url('/users/edit/'.$this->user['id']);?>" >
                    <strong><?=$this->user['user'];?></strong>
                </a>
                <span>(</span>
                <a href="<?=site_url('/groups/edit/'.$this->user['user_group_id'].'/users');?>" ><?=$this->user['user_group_title'];?></a>
                <span>), </span>
            </span>
            
            <img src="<?=base_url('img/iconLogout.png');?>" >
            <a href="<?=site_url('logout');?>" ><?=lang('label_logout');?></a>
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