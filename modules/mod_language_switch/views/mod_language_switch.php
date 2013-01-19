<?php

echo '<ul>';
        
$numb = 0;
foreach($languages as $abbr => $language){
    $numb++;  
    $class = '';
    
    if($numb == 1){
        $class = 'first';
    }
    elseif($numb == count($languages)){
        $class = 'last';
    }

    if($this->lang_lib->get() == $abbr){
        $class = 'current '.$class;
    }
    
    echo '    <li '.($class != '' ? 'class="'.$class.'"' : '').' >';
    echo '        <a href="'.base_url($abbr.'/'.$this->uri->uri_string).'" >';
    if($module['params']['images'] == 'yes'){
        echo '        <img src="'.base_url('modules/mod_language_switch/images/flag_'.$abbr.'.png').'" alt="'.$abbr.'" >';
    }
    echo '            <span>'.$language.'</span>';
    echo '        </a>';
    echo '    </li>';


}

echo '</ul>';
        
?>
