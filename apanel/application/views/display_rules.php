<div class="box" >
    <span class="header" ><?=lang('label_advanced');?> <?=lang('label_display');?> rules</span>

    <div class="box_content" >
        <table class="box_table" cellpadding="0" cellspacing="0" >

            <tr>
                <td>
                    
                    <div style="overflow: auto;" >
                        <input name="rule" type="text" style="float: left;width: 75%;" >
                        <button class="styled styled_small add" name="add_rule" type="button" ><?=lang('label_add');?></button>
                    </div>
                    
                    <ol class="rules" >
                      <?php $display_rules = set_value('params[display_rules]', isset($params['display_rules']) ? $params['display_rules'] : array());
                            foreach($display_rules as $display_rule){ ?>

                      <li>
                        <span><?=$display_rule;?></span>
                        <input type="hidden" name="params[display_rules][]" value="<?=$display_rule;?>" >
                        <img class="handle" src="<?=base_url('img/iconMove.png');?>" >
                        <a class="styled delete" title="<?=lang('label_delete');?>">&nbsp;</a>
                      </li>                                          
                      <?php } ?>
                    </ol>
                    
                </td>
            </tr>

        </table>

    </div>

</div>

<script type="text/javascript" >

    $('button[name=add_rule]').click(function(){

        var rule = $('input[name=rule]').val();

        if(rule == ''){
            return;
        }

        var html = '<li>'
                   +'<span>'+rule+'<\/span>'
                   +'<input type="hidden" name="params[display_rules][]" value="'+rule+'" >'
                   +'<img class="handle" src="'+DOCUMENT_BASE_URL+'<?=APANEL_DIR;?>/img/iconMove.png" >'
                   +'<a class="styled delete" title="<?=lang('label_delete');?>">&nbsp;<\/a>'
                   +'<\/li>';

        $('ol.rules').append(html);                                             
        $('input[name=rule]').val('');

    });

  $('a.delete').live('click', function(){

      var rule = $(this).parent().find('span').html();

      $('input[name=rule]').val(rule);
      $(this).parent().toggle('slow', function() {
          $(this).remove();
      });

  });
  
  $('ol.rules').sortable({handle : '.handle'});

</script>
                    