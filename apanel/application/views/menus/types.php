
<table class="menu_types" >
    
    <tr>
        <th><?=lang('label_general');?></th>
        <td></td>
        <th><?=lang('label_components');?></th>
    </tr>
    
    <tr>
        <td>
            <ul>
                <?php foreach($menus as $type => $menu){ ?>
                <li>
                    <a href="<?=$type;?>" ><?=lang($menu);?></a>
                </li>
                <?php } ?>
            </ul>
    
        </td>
        
        <td></td>

        <td>
            <ul>
                <?php foreach($this->components as $component => $data){ ?>
                
                    <?php if(count($data['menus']) == 1){ ?>
                    <li>
                        <a href="<?=$component;?>" class="type" ><?=lang('com_'.$component);?></a>
                    </li>
                    <?php }else{ ?>
                    <li>
                        <?=lang('com_'.$component);?>
                        <ul>
                            <?php foreach($data['menus'] as $menu => $text){ ?>
                            <li>
                                - <a href="<?=$menu;?>" class="type" ><?=lang($text);?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                
                <?php } ?>
            </ul>
        </td>
    </tr>
    
</table>