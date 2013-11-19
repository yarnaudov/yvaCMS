
<div id="media_content" >
    
    <?php if($folder != 'media/'){ ?>
    <div class="media_item" >
	<a href="#" id="up" >
	    <div><img src="<?php echo base_url('img/iconUp.png');?>" ></div>
	</a>
    </div>
    <?php } ?>

    <?php $media_dir = realpath(FCPATH.'../').'/'.$folder; ?>

    <?php foreach ($entries as $entry){ ?>

	<div class="media_item" >  

	    <?php if(is_dir($media_dir.$entry)){ ?>
	    <a href="#" class="folder" lang="<?php echo $entry;?>/" >
		<div><img src="<?php echo base_url('img/media/iconFolder.png');?>" ></div>
		<span>
		    <?php echo $entry;?>
		    (<?php echo count(directory_map($media_dir.$entry)); ?>)
		</span>
	    </a>
	    <?php }else{ 
		    $ext = strtolower(end(explode('.', $entry))); 

		    if(in_array($ext, array('gif','jpg','jpeg','jpe','png','tiff','tif'))){ ?>
		    <a <?php if(isset($image_settings)){ ?>
                       href  = "<?php echo site_url('media/image_settings/');?>?image=<?php echo $folder.$entry; ?>"
                       class = "load_jquery_ui_iframe"
                       title = "<?php echo lang('label_change_image');?>"
                       lang  = "dialog-select-article"
                       <?php }else{ ?>
                       href = "#"
                       <?php } ?> >
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
		    <button name="upload" type="submit" class="styled" value="1" ><?php echo lang('label_upload');?></button>
		</div>

	    </td>

	    <td>          
		<div class="second" >
		    <label><?php echo lang('label_create');?> <?php echo lang('label_folder');?>:</label>
		    <input type="text" name="folder_name" value="<?php echo isset($_POST['folder_name']) ? $_POST['folder_name'] : "";?>" >
		    <button name="create_folder" class="styled" value="1" ><?php echo lang('label_create_folder');?><?php echo lang('label_create');?></button>
		</div>
	    </td>

	</tr>

    </table>            

</div>