
<!-- start page header -->
<div id="page_header" >

    <div class="text" >
        <img src="<?php echo base_url('img/iconMedia_25.png');?>" >
        <span><?php echo lang('label_media');?></span>
        <span>&nbsp;»&nbsp;</span>
        <span><?php echo lang('label_change_image'); ?></span>
    </div>

    <div class="actions" >

    </div>

</div>
<!-- end page header -->

<?php echo $this->load->view('messages');?>    

<div id="image-settings">

    <div id="image">

        <img style="width: 100%;" data-width="<?php echo $image_data[0];?>" data-height="<?php echo $image_data[1];?>" class="image_" id="jcrop_target" src="<?php echo base_url('../'.$image).'?'.time();?>" >

    </div>

    <div id="settings" >

        
        <h4><?php echo lang('label_resize'); ?></h4>
        
        <form method="post" action="<?php echo current_url(true); ?>" >
            <div class="row" >
                <label><?php echo lang('label_width');?>: </label>
                <input type="text" name="width" value="<?php echo $image_data[0]; ?>" > px
            </div>
            <div class="row" >
                <label><?php echo lang('label_height');?>: </label>
                <input type="text" name="height" value="<?php echo $image_data[1]; ?>" > px
            </div>
            <div class="row" >  
                <label><?php echo lang('label_maintain_ratio'); ?>:</label>
                <input type="checkbox" name="maintain_ratio" value="1" checked >
            </div>
            <div class="row" >  
                <label><?php echo lang('label_keep_original'); ?>:</label>
                <input type="checkbox" name="keep_original" value="1" checked >
            </div>
            <div class="row" >&nbsp;</div>
            <div class="row" >
                <button class="styled styled_small resize" type="submit" name="resize" value="1" ><?php echo lang('label_resize');?></button>
            </div>
        </form>
        
        <div class="row" >&nbsp;</div>
        
        <h4><?php echo lang('label_crop'); ?></h4>
        
        <form method="post" action="<?php echo current_url(true); ?>" >
            
            <input type="hidden" name="x" id="x" >
            <input type="hidden" name="y" id="y" >
                                            
            <div class="row" >
                <label><?php echo lang('label_width');?>: </label>
                <input type="text" readonly name="width" id="w" > px
            </div>
            <div class="row" >
                <label><?php echo lang('label_height');?>: </label>
                <input type="text" readonly name="height" id="h" > px
            </div>
            <div class="row" >  
                <label><?php echo lang('label_keep_original'); ?>:</label>
                <input type="checkbox" name="keep_original" value="1" checked >
            </div>
            <div class="row" >&nbsp;</div>
            <div class="row" >
                <button class="styled styled_small crop" type="submit" name="crop" value="1" ><?php echo lang('label_crop');?></button>
            </div>
        </form>

    </div>
    
</div>