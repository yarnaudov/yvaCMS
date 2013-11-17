
    <!-- start page header -->
    <div id="page_header" >
	
        <div class="text" >
	    <img src="<?php echo base_url('img/iconMedia_25.png');?>" >
            <span><?php echo lang('label_media');?></span>
            <span>&nbsp;»&nbsp;</span>
            <span><?php echo lang('label_change_image'); ?>промяна на снимка</span>
	</div>
	
	<div class="actions" >
             
	</div>
	
    </div>
    <!-- end page header -->

<div id="image-settings">

    <div id="image">

        <img style="width: 100%;" data-width="<?php echo $image_data[0];?>" data-height="<?php echo $image_data[1];?>" class="image_" id="jcrop_target" src="<?php echo base_url('../'.$image).'?'.time();?>" >

        <input type="hidden" name="x" id="x" >
        <input type="hidden" name="y" id="y" >
        <input type="hidden" name="x2" id="x2" >
        <input type="hidden" name="y2" id="y2" >

    </div>

    <div id="settings" >

        <?php /*
        <span><?php echo lang('com_gallery_label_aspect_ratio');?>:</span>
        <select id="aspectRatio" name="params[aspect_ratio]" style="width: 50px;" >
            <?php echo create_options_array($this->config->item('aspect_ratio'), set_value('params[aspect_ratio]', isset($album_params['aspect_ratio']) ? $album_params['aspect_ratio'] : ""));?>
        </select>
        */ ?>
        
        <span><?php echo lang('com_gallery_label_width');?>: </span><input type="text" readonly name="w" id="w" style="width: 30px;" >
        <span><?php echo lang('com_gallery_label_height');?>: </span><input type="text" readonly name="h" id="h" style="width: 30px;" >
        <button class="styled styled_small crop" type="button" lang="<?php echo site_url('components/gallery/images/crop');?>" ><?php echo lang('com_gallery_label_crop');?></button>

        <?php /*
        &nbsp;&nbsp;|&nbsp;&nbsp;

        <select id="degrees" style="width: 50px;" >
            <?php echo create_options_array($this->config->item('rotate_degrees'), isset($params['rotate_degree']) ? $params['rotate_degree'] : '');?>
        </select>
        <button class="styled styled_small rotate" type="button" data-url="<?php echo site_url('components/gallery/images/rotate');?>" ><?php echo lang('com_gallery_label_rotate');?></button>

        &nbsp;&nbsp;|&nbsp;&nbsp;

        <?php $image_data = getimagesize(FCPATH.'../'.$this->config->item('images_origin_dir').'/'.$id.'.'.$ext); ?>
        <button class="styled styled_small origin" type="button" size="<?php echo $image_data[0];?>" desc="<?php echo $id;?>" lang="<?php echo site_url('components/gallery/images/origin/');?>" ><?php echo lang('com_gallery_label_load_original');?></button>
        <button class="styled styled_small change" type="button" ><?php echo lang('com_gallery_label_change');?></button>
        */ ?>

    </div>
    
</div>