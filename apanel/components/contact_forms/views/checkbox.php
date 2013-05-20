<?php !isset($input_type) ? $input_type = 'checkbox' : '';
      !isset($action)     ? $action     = ''         : '';
      $key = !isset($key) ? $this->input->get('key') : $key; ?>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?=lang('label_options');?>:</label></th>
    <td>
        <ul class="checkboxes" >
            <?php $options   = isset($fields[$key]['options'])   ? $fields[$key]['options']   : '';
                  $labels    = isset($fields[$key]['labels'])    ? $fields[$key]['labels']    : '';
                  $optgroups = isset($fields[$key]['optgroups']) ? $fields[$key]['optgroups'] : '';
                  for($key2 = 0; $key2 < count($options); $key2++){ ?>
            <li>
                <input class="option_hidden"   type="hidden"            name="fields[<?=$key;?>][options][]"   value="0" >
                <input class="option"          type="<?=$input_type;?>" name="fields[<?=$key;?>][options][]"   value="1" <?=(isset($options[$key2]) && $options[$key2] == 1) ? "checked" : "";?> >
                <input class="text"            type="text"              name="fields[<?=$key;?>][labels][]"    value="<?=isset($labels[$key2]) ? $labels[$key2] : "";?>" >
                
                <?php if($action == 'dropdown'){ ?>
                <input class="optgroup_hidden" type="hidden"            name="fields[<?=$key;?>][optgroups][]" value="0" >
                <input class="optgroup"        type="checkbox"          name="fields[<?=$key;?>][optgroups][]" value="1" <?=(isset($optgroups[$key]) && $optgroups[$key2] == 1) ? "checked" : "";?> title="Make this option group">
                <?php } ?>
                
                <img src="<?=base_url('img/iconMove.png');?>" class="handle" alt="move" >
                
                <a class="styled delete delete_option" title="<?=lang('label_delete');?>" >&nbsp;</a>
            </li>
            <?php } ?>
        </ul>
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th></th>
    <td>
        <a class="styled add add_option" ><?=lang('label_add');?></a>
    </td>
</tr>