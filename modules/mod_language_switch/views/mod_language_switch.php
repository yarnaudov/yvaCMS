<?php if($module['params']['menu_type'] == 'dropdown'){ ?>

<select>
    
    <?php foreach($languages as $language){ ?>
    <option <?=$language['abbreviation'] == get_lang() ? 'selected' : ''; ?> value="<?=base_url($language['abbreviation'].'/'.$this->uri->uri_string);?>" ><?=$language['title'];?></option>
    <?php } ?>
    
</select>

<?php }elseif($module['params']['menu_type'] == 'list'){ ?>

<ul>
    
    <?php $numb = 0;
          foreach($languages as $language){
              $numb++;  
              $class = '';
    
              if($numb == 1){
                  $class = 'first';
              }
              elseif($numb == count($languages)){
                  $class = 'last';
              }

              if($language['abbreviation'] == get_lang()){
                  $class = 'current '.$class;
              } ?>
    
    <li <?=$class != '' ? 'class="'.$class.'"' : '';?> >
        <a href="<?=base_url($language['abbreviation'].'/'.$this->uri->uri_string);?>" >
             
            <?php if($module['params']['images'] == 'yes'){
                      if(!empty($language['image'])){
                          $image = $language['image'];
                      }else{
                          $image = 'modules/mod_language_switch/images/flag_'.$language['abbreviation'].'.png';
                      } ?>
            
            <img src="<?=base_url($image);?>" alt="<?=$language['title'];?>" >
            
            <?php } ?>
            
            <span><?=$language['title'];?></span>
        </a>
    </li>
    
    <?php } ?>
    
</ul>

<?php } ?>