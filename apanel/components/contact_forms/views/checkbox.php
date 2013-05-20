<?php !isset($input_type) ? $input_type = 'checkbox' : '';
      !isset($action)     ? $action     = ''         : ''; ?>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?=lang('label_options');?>:</label></th>
    <td>
        <ul class="checkboxes" >
            <?php $options   = set_value('params[options]',   isset($params['options'])   ? $params['options']   : "");
                  $labels    = set_value('params[labels]',    isset($params['labels'])    ? $params['labels']    : "");
                  $optgroups = set_value('params[optgroups]', isset($params['optgroups']) ? $params['optgroups'] : "");
                  for($key = 0; $key < count($options); $key++){ ?>
            <li>
                <input class="option_hidden"   type="hidden"            name="params[options][]"   value="0" >
                <input class="option"          type="<?=$input_type;?>" name="params[options][]"   value="1" <?=(isset($options[$key]) && $options[$key] == 1) ? "checked" : "";?> >
                <input class="text"            type="text"              name="params[labels][]"    value="<?=isset($labels[$key]) ? $labels[$key] : "";?>" >
                
                <?php if($action == 'dropdown'){ ?>
                <input class="optgroup_hidden" type="hidden"            name="params[optgroups][]" value="0" >
                <input class="optgroup"        type="checkbox"          name="params[optgroups][]" value="1" <?=(isset($optgroups[$key]) && $optgroups[$key] == 1) ? "checked" : "";?> title="Make this option group">
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