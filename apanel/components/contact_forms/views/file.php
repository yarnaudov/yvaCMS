<?php $key = !isset($key) ? $this->input->get('key') : $key; ?>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?php echo lang('label_max_size');?>:</label></th>
    <td>
        <input type="text" name="fields[<?php echo $key;?>][max_size]" style="width: 100px;" value="<?php echo set_value('fields['.$key.'][max_size]', isset($fields[$key]['max_size']) ? $fields[$key]['max_size'] : "");?>" />
		KB
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?php echo lang('label_allowed_ext');?>:</label></th>
    <td>
        <input type="text" name="fields[<?php echo $key;?>][allowed_ext]" style="width: 100px;" value="<?php echo set_value('fields['.$key.'][allowed_ext]', isset($fields[$key]['allowed_ext']) ? $fields[$key]['allowed_ext'] : "");?>" />
		(jpg|png|txt)
    </td>
</tr>