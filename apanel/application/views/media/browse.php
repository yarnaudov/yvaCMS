
<form name="list" action="<?php echo current_url();?>" method="post" enctype="multipart/form-data" >
    
    <input type="hidden" name="folder" value="<?php echo $folder;?>" >
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >            
            <span><?php echo lang('label_directory');?>:</span> 
            <span class="directory_navigation" ><?php echo $folder;?></span>
        </div>
        
        <div class="actions" >
            	    
            <button class="styled refresh" type="submit" ><?php echo lang('label_refresh');?></button>
            
            <a href="#" class="styled rename" ><?php echo lang('label_rename');?></a>
	    <a href="#" class="styled delete" ><?php echo lang('label_delete');?></a>
	    <a href="#" class="styled download" ><?php echo lang('label_download');?></a>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="#" class="styled select <?php echo $param;?>" ><?php echo lang('label_select');?></a>
		
	</div>
		
    </div>
    <!-- end page header -->
    
    <?php if(!empty($error)){ ?>      
        <div class="error_msg" >
            <?php echo $error;?>
        </div>
    <?php } ?>
    
    <!-- start page content -->
    <div id="page_content" >
	<?php echo $this->load->view('media/entries'); ?>        
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