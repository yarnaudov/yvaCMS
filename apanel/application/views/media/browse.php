
<form name="list" action="<?=current_url();?>" method="post" enctype="multipart/form-data" >
    
    <input type="hidden" name="folder" value="<?=$folder;?>" >
    
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >            
            <span><?=lang('label_directory');?>:</span> 
            <span class="directory_navigation" ><?=$folder;?></span>
        </div>
        
        <div class="actions" >
            	    
            <button class="styled refresh" type="submit" ><?=lang('label_refresh');?></button>
            
            <a href="#" class="styled rename" ><?=lang('label_rename');?></a>
	    <a href="#" class="styled delete" ><?=lang('label_delete');?></a>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="#" class="styled select <?=$param;?>" ><?=lang('label_select');?></a>
		
	</div>
		
    </div>
    <!-- end page header -->
    
    <?php if(!empty($error)){ ?>      
        <div class="error_msg" >
            <?=$error;?>
        </div>
    <?php } ?>
    
    <!-- start page content -->
    <div id="page_content" >
        
        <div id="media_content" >
            <?php if($folder != 'media/'){ ?>
            <div class="media_item" >
                <a href="#" id="up" >
                    <div><img src="<?=base_url('img/iconUp.png');?>" ></div>
                </a>
            </div>
            <?php } ?>
            
            <?php
            $media_dir = realpath(FCPATH.'../').'/'.$folder;
            $handle = opendir($media_dir); 
            ?>

            <?php while (false !== ($entry = readdir($handle))) { 
                    $entry = iconv("Windows-1251", "UTF-8", $entry);
                    if (substr($entry, 0, 1) == "."){
                        continue;
                    } ?>

                <div class="media_item" >  
                                        
                    <?php if(is_dir($media_dir.$entry)){ ?>
                    <a href="#" class="folder" lang="<?=$entry;?>/" >
                        <div><img src="<?=base_url('img/media/iconFolder.png');?>" ></div>
                        <span><?=$entry;?></span>
                    </a>
                    <?php }else{ 
                            $ext = strtolower(end(explode('.', $entry))); 

                            if(in_array($ext, array('jpg', 'png', 'gif'))){ ?>
                            <a href="#" >
                                <div><img src="<?=base_url('../'.$folder.$entry);?>" ></div>
                                <span><?=$entry;?></span>
                            </a>                              
                            <?php }elseif(file_exists('img/media/icon_'.$ext.'.png')){ ?>
                            <a href="#" >
                                <div><img src="<?=base_url('img/media/icon_'.$ext.'.png');?>" ></div>
                                <span><?=str_replace('.'.$ext, '', $entry);?></span>
                            </a>
                            <?php }else{ ?>
                            <a href="#" >
                                <div><img src="<?=base_url('img/media/icon_file.png');?>" ></div>
                                <span><?=$entry;?></span>
                            </a>
                            <?php } ?>
                    <?php } ?>             
                    
                    <input type="checkbox" class="checkbox<?=is_dir($media_dir.$entry) ? ' directory' : ''; ?>" name="item[]" value="<?=$entry;?>" >
                    
                </div>

            <?php } ?>
        
        </div>
        
        <div class="selected_file" >
            
            <table cellpadding="0" cellspaicing="0" >
                
                <tr>
                    
                    <td> 
                        <div class="first" >
                            <label><?=lang('label_upload');?> <?=lang('label_file');?>:</label>
                            <div class="input_file" >
                              <input type="file" name="file[]" size="30" multiple class="file">
                              <button type="button" class="styled" >Browse</button>
                              <input type="text" class="text">
                            </div>
                            <button name="upload" type="submit" class="styled" ><?=lang('label_upload');?></button>
                        </div>
                        
                    </td>
                    
                    <td>          
                        <div class="second" >
                            <label><?=lang('label_create');?> <?=lang('label_folder');?>:</label>
                            <input type="text" name="folder_name" value="<?=isset($_POST['folder_name']) ? $_POST['folder_name'] : "";?>" >
                            <button name="create_folder" class="styled" ><?=lang('label_create_folder');?><?=lang('label_create');?></button>
                        </div>
                    </td>
                    
                </tr>
                
            </table>            
            
        </div>
        
    </div>
    <!-- end page content -->
    
</form>

<!-- start jquery UI -->
<div id="dialog-edit1" title="<?=lang('label_error');?>" >
	<p><?=lang('msg_select_item');?></p>
</div>

<div id="dialog-edit2" title="<?=lang('label_error');?>" >
	<p><?=lang('msg_select_one_item');?></p>
</div>

<div id="dialog-delete" title="<?=lang('label_confirm');?>" >
	<p><?=lang('msg_delete_confirm');?></p>
</div>

<div id="dialog-rename" title="<?=lang('label_rename');?>" >
    <label><?=lang('label_name');?>:</label> <br/>
    <input type="text" id="new_name" style="width: 100%;" >
</div>
<!-- end jquery UI -->