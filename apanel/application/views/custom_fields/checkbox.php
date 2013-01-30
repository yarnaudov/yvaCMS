<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th><label><?=lang('label_options');?>:</label></th>
    <td>
        <ul id="checkboxes" >
            <li>
                <input type="checkbox" name="options[]" value="1" >
                <input type="text" name="labels[]" value="" >
                <a class="styled delete" >&nbsp;</a>
            </li>
        </ul>
    </td>
</tr>

<tr><td colspan="2" class="empty_line" ></td></tr>

<tr>
    <th></th>
    <td>
        <a class="styled add" ><?=lang('label_add');?></a>
    </td>
</tr>

<script type="text/javascript" >
    
    $('a.add').click(function(event){
        
        event.preventDefault();
        
        $('#checkboxes').append('<li>'+$('#checkboxes li').first().html()+'</li>');
        
    });
    
    $('a.delete').live('click', function(event){
        
        event.preventDefault();
        
        $(this).parent().toggle('slow', function() {
            if($('#checkboxes li').length > 1){
                $(this).remove();
            }
        });
        
    });
    
    $('#checkboxes').sortable();
    
</script>