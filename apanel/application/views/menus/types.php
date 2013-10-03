
<table class="menu_types" >
    
    <tr>
        <th><?php echo lang('label_general');?></th>
        <td></td>
        <th><?php echo lang('label_components');?></th>
    </tr>
    
    <tr>
        <td>
            <ul>
                <?php foreach($menus as $type => $menu){ ?>
                <li>
                    <a href="<?php echo $type;?>" class="type" ><?php echo lang($menu);?></a>
                </li>
                <?php } ?>
            </ul>
    
        </td>
        
        <td></td>

        <td>
            <ul>
                <?php foreach($this->components as $component => $data){ ?>
                
                    <?php if(isset($data['menus'])){ ?>
                
                        <?php if(count($data['menus']) == 1){ ?>
                        <li>
                            <a href="components/<?php echo $component;?>" class="type" ><?php echo lang('com_'.$component);?></a>
                        </li>
                        <?php }else{ ?>
                        <li>
                            <span><?php echo lang('com_'.$component);?></span>
                            <ul>
                                <?php foreach($data['menus'] as $menu => $text){ ?>
                                <li>
                                    - <a href="components/<?php echo $component;?>/<?php echo $menu;?>" class="type" ><?php echo lang($text);?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                        
                    <?php } ?>
                        
                <?php } ?>
            </ul>
        </td>
    </tr>
    
</table>