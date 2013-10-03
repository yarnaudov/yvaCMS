
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
        
        <div id="media_content" >
            <?php if($folder != 'media/'){ ?>
            <div class="media_item" >
                <a href="#" id="up" >
                    <div><img src="<?php echo base_url('img/iconUp.png');?>" ></div>
                </a>
            </div>
            <?php } ?>
            
            <?php
            $media_dir = realpath(FCPATH.'../').'/'.$folder;
            $handle = opendir($media_dir); 
            ?>

            <?php while (false !== ($entry = readdir($handle))) { 
                    $entry = iconv("Windows-1251", "UTF-8", $entry);
                    if (substr($entry, 0, 1) == "." || $entry == "index.html"){
                        continue;
                    } ?>

                <div class="media_item" >  
                                        
                    <?php if(is_dir($media_dir.$entry)){ ?>
                    <a href="#" class="folder" lang="<?php echo $entry;?>/" >
                        <div><img src="<?php echo base_url('img/media/iconFolder.png');?>" ></div>
                        <span><?php echo $entry;?></span>
                    </a>
                    <?php }else{ 
                            $ext = strtolower(end(explode('.', $entry))); 

                            if(in_array($ext, array('jpg', 'png', 'gif'))){ ?>
                            <a href="#" >
                                <div><img src="<?php echo base_url('../'.$folder.$entry);?>" ></div>
                                <span><?php echo $entry;?></span>
                            </a>                              
                            <?php }elseif(file_exists('img/media/icon_'.$ext.'.png')){ ?>
                            <a href="#" >
                                <div><img src="<?php echo base_url('img/media/icon_'.$ext.'.png');?>" ></div>
                                <span><?php echo str_replace('.'.$ext, '', $entry);?></span>
                            </a>
                            <?php }else{ ?>
                            <a href="#" >
                                <div><img src="<?php echo base_url('img/media/icon_file.png');?>" ></div>
                                <span><?php echo $entry;?></span>
                            </a>
                            <?php } ?>
                    <?php } ?>             
                    
                    <input type="checkbox" class="checkbox<?php echo is_dir($media_dir.$entry) ? ' directory' : ''; ?>" name="item[]" value="<?php echo $entry;?>" >
                    
                </div>

            <?php } ?>
        
        </div>
        
        <div class="selected_file" >
            
            <table cellpadding="0" cellspaicing="0" >
                
                <tr>
                    
                    <td> 
                        <div class="first" >
                            <label><?php echo lang('label_upload');?> <?php echo lang('label_file');?>:</label>
                            <div class="input_file" >
                              <input type="file" name="file[]" size="30" multiple class="file">
                              <button type="button" class="styled" >Browse</button>
                              <input type="text" class="text">
                            </div>
                            <button name="upload" type="submit" class="styled" ><?php echo lang('label_upload');?></button>
                        </div>
                        
                    </td>
                    
                    <td>          
                        <div class="second" >
                            <label><?php echo lang('label_create');?> <?php echo lang('label_folder');?>:</label>
                            <input type="text" name="folder_name" value="<?php echo isset($_POST['folder_name']) ? $_POST['folder_name'] : "";?>" >
                            <button name="create_folder" class="styled" ><?php echo lang('label_create_folder');?><?php echo lang('label_create');?></button>
                        </div>
                    </td>
                    
                </tr>
                
            </table>            
            
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