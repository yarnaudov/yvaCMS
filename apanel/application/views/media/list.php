
<form name="list" action="<?php echo current_url(true);?>" method="post" enctype="multipart/form-data" >
    
    <input type="hidden" name="folder" value="<?php echo $folder;?>" >
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
            
            <img src="<?php echo base_url('img/iconMedia_25.png');?>" >
            
            <span><?php echo lang('label_media');?></span>
        </div>
	
	<div class="actions" >
		
            <a href="#" class="styled rename"   ><?php echo lang('label_rename');?></a>
            <a href="#" class="styled delete"   ><?php echo lang('label_delete');?></a>
	    <a href="#" class="styled download" ><?php echo lang('label_download');?></a>
            <a href="<?php echo site_url();?>"  class="styled cancel" ><?php echo lang('label_cancel');?></a>
	    		
	</div>
	
    </div>
    <!-- end page header -->
    

    <!-- start page content -->
    <div id="sub_actions" >
	<?php echo $this->menu_lib->create_menu($sub_menu); ?>
    </div>
    <!-- start page content -->

    <?php echo $this->load->view('messages');?>
    
    <?php if(!empty($error)){ ?>      
        <div class="error_msg" >
            <?php echo $error;?>
        </div>
    <?php } ?>

    <!-- start page content -->
    <div id="page_content" >
	
	<!--
	<div id="filter_content" >
		
            <div class="search" >		
                <input type="text" name="filters[search_v]" value="<?php echo isset($filters['search_v']) ? $filters['search_v'] : "";?>" >
                <button class="styled" type="submit" name="search" ><?php echo lang('label_search');?></button>
                <button class="styled" type="submit" name="clear"  ><?php echo lang('label_clear');?></button>		
            </div>
		
            <div class="filter" >
			
            </div>
		
	</div>
	-->
	
	<div id="media_conteiner" >
	    
	    <div id="media_entries_conteiner">
		<?php echo $this->load->view('media/entries'); ?>
	    </div>
	
	</div>
	
    </div>
    <!-- end page content -->

</form>

<!-- start jquery UI -->
<div id="dialog-edit1" title="<?php echo lang('label_error');?>" >
	<p><?php echo lang('msg_select_item');?></p>
</div>

<div id="dialog-edit2" title="<?php echo lang('label_error');?>" >
	<p><?php echo lang('msg_select_one_item');?></p>
</div>

<div id="dialog-delete" title="<?php echo lang('label_confirm');?>" >
	<p><?php echo lang('msg_delete_confirm');?></p>
</div>

<div id="dialog-rename" title="<?php echo lang('label_rename');?>" >
    <label><?php echo lang('label_name');?>:</label> <br/>
    <input type="text" id="new_name" style="width: 100%;" >
</div>
<!-- end jquery UI -->