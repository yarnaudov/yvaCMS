<?php if($module['params']['menu_type'] == 'dropdown'){ ?>

<select>
    
    <?php foreach($languages as $language){ ?>
    <option <?php echo $language['abbreviation'] == get_lang() ? 'selected' : ''; ?> value="<?php echo base_url($language['abbreviation'].'/'.$this->uri->uri_string);?>" ><?php echo $language['title'];?></option>
    <?php } ?>
    
</select>

<?php }elseif($module['params']['menu_type'] == 'list'){ ?>

<ul>
    
    <?php $numb = 0;
          foreach($languages as $language){
              $numb++;  
              $class = ' ';
    
              if($numb == 1){
                  $class = 'first';
              }
              elseif($numb == count($languages)){
                  $class = 'last';
              }

              if($language['abbreviation'] == get_lang()){
                  $class = 'current '.$class;
              } 
              
              $class = $language['abbreviation'].' '.$class;
              $class = trim($class); ?>
    
    <li <?php echo $class != '' ? 'class="'.$class.'"' : '';?> >
        <a href="<?php echo base_url($language['abbreviation'].'/'.$this->uri->uri_string);?>" >
             
            <?php if($module['params']['images'] == 'yes'){
                      if(!empty($language['image'])){
                          $image = $language['image'];
                      }else{
                          $image = 'modules/mod_language_switch/images/flag_'.$language['abbreviation'].'.png';
                      } ?>
            
            <img src="<?php echo base_url($image);?>" alt="<?php echo $language['title'];?>" >
            
            <?php } ?>
            
            <?php if(isset($module['params']['text']) && $module['params']['text'] == 'yes'){ ?>
            <span><?php echo $language['title'];?></span>
            <?php } ?>
            
        </a>
    </li>
    
    <?php } ?>
    
</ul>

<?php } ?>