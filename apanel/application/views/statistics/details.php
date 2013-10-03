<table class="list" cellpadding="0" cellspacing="2" >

    <?php ob_start(); ?>
    <tr>
        <th style="width:6%;"  ><?php echo lang('label_time');?></th>
        <th style="width:8%;" >IP</th>
        <th style="width:14%;" ><?php echo lang('label_user_agent');?></th>
        <th style="width:30%;" ><?php echo lang('label_page_url');?></th>
        <th style="width:42%;" ><?php echo lang('label_user_refferer');?></th>
    </tr>
    <?php $header = ob_get_contents();          
          ob_end_clean();
          echo $header; ?>

    <?php $numb = 0;
          foreach($details as $detail){
              $numb++; ?>

    <?php if($numb > 9){ 
              $numb = 0;
              echo $header;
          } ?>

    <tr>
        <td><?php echo end(explode(" ", $detail['created_on']));?></td>
        <td>
            <a href="http://whatismyipaddress.com/ip/<?php echo $detail['ip'];?>" target="_blank" ><?php echo $detail['ip'];?></a>
        </td>
        <td><?php echo $detail['user_agent'];?></td>
        <td style="text-align: left;" >
            <a href="<?php echo $detail['page_url'];?>" target="_blank" ><?php echo $detail['page_url'];?></a>
        </td>
        <td style="text-align: left;" >
            <a href="<?php echo $detail['user_referrer'];?>" target="_blank" ><?php echo $detail['user_referrer'];?></a>
        </td>
    </tr>
    <?php } ?>

</table>