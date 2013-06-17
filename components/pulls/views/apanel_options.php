
<?php 
$this->load->language('pulls/com_pulls_labels');
$this->load->model('pulls/Pull');
$pulls = $this->Pull->getPulls();
?>

<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?=lang('label_pull');?>:</label></th>
    <td>        
        <select name="params[pull_id]" >
            <?php foreach($pulls as $pull){ ?>
            <option <?=$pull['pull_id'] == set_value('params[pull_id]', isset($params['pull_id']) ? $params['pull_id'] : "") ? "selected" : "";?> 
                    value="<?=$pull['pull_id'];?>" ><?=$pull['title'];?></option>
            <?php } ?>
        </select>
    </td>
</tr>
