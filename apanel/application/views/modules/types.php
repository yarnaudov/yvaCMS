
<?php

$size = count($modules)/2;
$modules = array_chunk($modules, ceil($size));

?>

<table class="menu_types" >
    
    <!--
    <tr>
        <th><?=lang('label_general');?></th>
        <td></td>
        <th><?=lang('label_components');?></th>
    </tr>
    -->
    
    <tr>
        <td>
   
            <ul>
            <?php foreach($modules[0] as $module){ ?> 

                <li>
                    <a href="<?=$module;?>" class="type" >
                        <?=lang('label_'.$module);?>
                    </a>
                </li>

            <?php } ?>
            </ul>

        </td>
        
        <td>
   
            <ul>
            <?php foreach($modules[1] as $module){ ?> 

                <li>
                    <a href="<?=$module;?>" class="type" >
                        <?=lang('label_'.$module);?>
                    </a>
                </li>

            <?php } ?>
            </ul>

        </td>
        
    </tr>
    
</table>

