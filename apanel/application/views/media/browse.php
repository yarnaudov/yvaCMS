
<form name="list" action="<?php echo current_url(true);?>" method="post" enctype="multipart/form-data" >
    
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
        
        <div id="filter_content" >
		
            <div class="search" >		
                <input type="text" name="filters[search_v]" value="<?php echo isset($filters['search_v']) ? $filters['search_v'] : "";?>" >
                <button class="styled" type="submit" name="search" ><?php echo lang('label_search');?></button>
                <button class="styled" type="submit" name="clear"  ><?php echo lang('label_clear');?></button>		
            </div>
		
            <div class="filter" >
			
                <?php 
                $orders_by[DIR_SORT_NAME.';'.SORT_ASC]   = lang('label_order_by_name_asc');
                $orders_by[DIR_SORT_NAME.';'.SORT_DESC]  = lang('label_order_by_name_desc');
                $orders_by[DIR_SORT_SIZE.';'.SORT_ASC]   = lang('label_order_by_size_asc');
                $orders_by[DIR_SORT_SIZE.';'.SORT_DESC]  = lang('label_order_by_size_desc');
                $orders_by[DIR_SORT_MTIME.';'.SORT_ASC]  = lang('label_order_by_mtime_asc');
                $orders_by[DIR_SORT_MTIME.';'.SORT_DESC] = lang('label_order_by_mtime_desc');
                ?>
                
                <select name="filters[order_by]" >
                    <option value="none" > - <?php echo lang('label_select');?> <?php echo lang('label_order');?> - </option>
                    <?php echo create_options_array($orders_by, isset($filters['order_by']) ? $filters['order_by'] : "");?>
                </select>
                
            </div>
		
	</div>
        
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