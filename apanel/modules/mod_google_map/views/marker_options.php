
<?php $this->load->language('modules/mod_google_map'); ?>

<div id="marker_info" title="<?php echo lang('label_mod_google_map_marker_info');?>" style="display:none;" >
    
    <div class="marker_info_conteiner" >

	<input type="hidden" id="marker_numb" >

	<div>
	    <label><?php echo lang('label_position');?>:</label>
	    <span id="position" >
		    <strong></strong> lat &nbsp;
		    <strong></strong> lng
	    </span>
	</div>

	<div>
	    <label><?php echo lang('label_title');?>:</label>
	    <span><input type="text" id="marker_title" ></span>
	</div>

	<div>
	    <label><?php echo lang('label_description');?>:</label>
	    <textarea class="marker_description" id="marker_description" ></textarea>
	</div>

	<div>	      			
	    <label><?php echo lang('label_mod_google_map_icon');?>:</label>
	    <span>
		<input class="marker_image" type="text" readonly id="marker_image" style="width: 55%">
		<a href   = "<?php echo site_url('media/browse');?>" 
		   class  = "load_jquery_ui_iframe"
		   title  = "<?php echo lang('label_browse').' '.lang('label_media');?>"
		   lang   = "dialog-media-browser"
		   target = "marker_image" ><?php echo lang('label_select');?></a>&nbsp;|&nbsp;<a href  = "#"
											  class = "clear_jquery_ui_inputs"
											  lang  = "marker_image" ><?php echo lang('label_clear');?></a>
	    </span>
	</div>

	<div>	
	    <label>&nbsp;</label>  			
	    <a id="save_marker" class="styled apply" ><?php echo lang('label_apply');?></a>
	    <a id="delete_marker" class="styled delete" ><?php echo lang('label_delete');?></a>
	    <span style="display: inline;" >|</span>
	    <a id="cancel_marker" href="#" ><?php echo lang('label_cancel');?></a>
	</div>

    </div>
    
</div>

<img src="<?php echo base_url('img/iconAdministration.png');?>" style="display:none;" onload="marker_info_dialog();marker_description_editor();" >