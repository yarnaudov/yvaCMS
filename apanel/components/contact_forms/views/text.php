<?php $key = !isset($key) ? $this->input->get('key') : $key; ?>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?php echo lang('label_value');?>:</label></th>
    <td>
        <input type="text" name="fields[<?php echo $key;?>][value]" value="<?php echo set_value('fields['.$key.'][value]', isset($fields[$key]['value']) ? $fields[$key]['value'] : "");?>" >
    </td>
</tr>