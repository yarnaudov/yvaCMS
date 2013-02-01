<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?=lang('label_value');?>:</label></th>
    <td>
        <input type="text" class="datepicker" name="value" value="<?=set_value('value', isset($value) ? $value : "");?>" >
        <script type="text/javascript" >
            $('.datepicker').datepicker({
                showOn: 'button',
                dateFormat: 'yy-mm-dd',
                buttonImage: '<?=base_url('img/iconCalendar.png');?>',
                buttonImageOnly: true
            });
        </script>
    </td>
</tr>