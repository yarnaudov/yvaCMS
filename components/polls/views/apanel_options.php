
<?php 
$this->load->language('polls/com_polls_labels');
$this->load->model('polls/Poll');
$polls = $this->Poll->getPolls();
?>

<tr><td colspan="2" class="empty_line" ></td></tr>
                                
<tr>	      			
    <th><label><?php echo lang('label_poll');?>:</label></th>
    <td>        
        <select name="params[poll_id]" >
            <?php foreach($polls as $poll){ ?>
            <option <?php echo $poll['poll_id'] == set_value('params[poll_id]', isset($params['poll_id']) ? $params['poll_id'] : "") ? "selected" : "";?> 
                    value="<?php echo $poll['poll_id'];?>" ><?php echo $poll['title'];?></option>
            <?php } ?>
        </select>
    </td>
</tr>
