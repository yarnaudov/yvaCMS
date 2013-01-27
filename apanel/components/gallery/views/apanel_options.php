
<?php 
echo $param;
$this->load->language('components/com_gallery_labels');
$this->load->model('gallery/Album');
$albums_arr = $this->Album->getAlbums();

$albums = set_value('params[albums]', isset($params['albums']) ? $params['albums'] : "");
!is_array($albums) ? $albums = array() : '';

?>

<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('com_gallery_label_albums');?>:</label></th>
    <td>
        <div class="menu_list">
            <table class="menu_list" cellspacing="0" cellpadding="0">
                
                <tr>
                    <td style="width: 1%;">
                        <input id="all" type="checkbox" value="*" name="params[albums][]" <?php echo @$albums[0] == '*' ? 'checked' : ''; ?> style="width:16px;">
                    </td>
                    <td>
                        <label for="all" ><?=lang('label_all');?></label>
                    </td>
                </tr>
                
                <?php foreach($albums_arr as $album){ ?>
                <tr>
                    <td style="width: 1%;">
                        <input id="album<?=$album['id'];?>" type="checkbox" value="<?=$album['id'];?>" name="params[albums][]" <?php echo in_array($album['id'], $albums) ? 'checked' : ''; ?> style="width:16px;">
                    </td>
                    <td>
                        <label for="album<?=$album['id'];?>" ><?=$album['title'];?></label>
                    </td>
                </tr>
                <?php } ?>
                
            </table>
        </div>
        
        <script type="text/javascript" >

        $('input#all').bind('click, change', function(){

            if($(this).attr('checked')){
                $('input[id^=album]').attr('disabled', true);
            }
            else{
                $('input[id^=album]').removeAttr('disabled');
            }

        });
        $('input#all').trigger('change');

        </script>
        
        <!--
        <select name="params[album_id]" >
            <option value="none" >- - -</option>
            <?=create_options('com_gallery_albums', 'album_id', 'title_'.$this->Language->getDefault(), set_value('params[album_id]', isset($params['album_id']) ? $params['album_id'] : ""));?>
        </select>
        -->
    </td>
</tr>