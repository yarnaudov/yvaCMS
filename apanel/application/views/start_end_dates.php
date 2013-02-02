<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_start_date');?>:</label></th>
    <td>
        <input type="text" class="start_end_dates datepicker" name="start_publishing" value="<?=set_value('start_publishing', isset($start_publishing) ? $start_publishing : "");?>" >
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>	      			
    <th><label><?=lang('label_end_date');?>:</label></th>
    <td>
        <input type="text" class="start_end_dates datepicker" name="end_publishing" value="<?=set_value('end_publishing', isset($end_publishing) ? $end_publishing : "");?>" >
        
        <script type="text/javascript" >
            
            $('.start_end_dates').datepicker({
                showOn: 'button',
                dateFormat: 'yy-mm-dd',
                buttonImage: '<?=base_url('img/iconCalendar.png');?>',
                buttonImageOnly: true
            });
            
        </script>
        
    </td>
</tr>