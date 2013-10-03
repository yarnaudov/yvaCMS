<?php $key = !isset($key) ? $this->input->get('key') : $key; ?>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?php echo lang('label_value');?>:</label></th>
    <td>
        <input type="text" class="datepicker" name="fields[<?php echo $key;?>][value]" value="<?php echo set_value('fields['.$key.'][value]', isset($fields[$key]['value']) ? $fields[$key]['value'] : "");?>" >
        <script type="text/javascript" >
            $('.datepicker').datepicker({
                showOn: 'button',
                dateFormat: 'yy-mm-dd',
                buttonImage: '<?php echo base_url('img/iconCalendar.png');?>',
                buttonImageOnly: true
            });
        </script>
    </td>
</tr>