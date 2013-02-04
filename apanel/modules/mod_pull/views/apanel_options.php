
<?php 
$this->load->language('components/com_pulls_labels');
$this->load->model('pulls/Pull');
$pulls = $this->Pull->getPulls();
?>

<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_pull');?>:</label></th>
    <td>        
        <select name="params[pull_id]" >
            <?php foreach($pulls as $pull){ ?>
            <option <?=$pull['id'] == set_value('params[id]', isset($params['id']) ? $params['id'] : "") ? "selected" : "";?> 
                    value="<?=$pull['id'];?>" ><?=$pull['title'];?></option>
            <?php } ?>
        </select>
    </td>
</tr>
