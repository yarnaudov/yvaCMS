
<?php 
//echo $param;
$this->load->language('components/com_gallery_labels');
$this->load->model('gallery/Album');
$albums_arr = $this->Album->getAlbums();

$albums = set_value('params[albums]', isset($params['albums']) ? $params['albums'] : "");
!is_array($albums) ? $albums = array() : '';

?>

<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?php echo lang('com_gallery_label_albums');?>:</label></th>
    <td>

    <div class="menu_list">
	<table class="menu_list" cellspacing="0" cellpadding="0">

	    <tr>
		<td style="width: 1%;">
		    <input id="all" type="checkbox" value="*" name="params[albums][]" <?php echo @$albums[0] == '*' ? 'checked' : ''; ?> >
		</td>
		<td>
		    <label for="all" ><?php echo lang('label_all');?></label>
		</td>
	    </tr>

	    <?php foreach($albums_arr as $album){ ?>
	    <tr>
		<td style="width: 1%;">
		    <input id="album<?php echo $album['id'];?>" type="checkbox" value="<?php echo $album['id'];?>" name="params[albums][]" <?php echo in_array($album['id'], $albums) ? 'checked' : ''; ?> >
		</td>
		<td>
		    <label for="album<?php echo $album['id'];?>" ><?php echo $album['title'];?></label>
		</td>
	    </tr>
	    <?php } ?>

	</table>
    </div>
        
    <script type="text/javascript" >

    $('input#all').bind('click, change', function(){
	console.log($(this).attr('checked'));
	if($(this).attr('checked')){
	    $('input[id^=album]').attr('disabled', true);
	}
	else{
	    $('input[id^=album]').removeAttr('disabled');
	}

    });
    $('input#all').trigger('change');

    </script>
        
    </td>
</tr>