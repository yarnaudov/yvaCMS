<div class="tagcloud">
<?php

$meta_keywords = get_instance()->meta_keywords;
$meta_keywords = explode(',', $meta_keywords);

foreach($meta_keywords as $meta_keyword){
    
    $meta_keyword = trim($meta_keyword);
    
    if(empty($meta_keyword)){
	continue;
    }
    
    $count = $this->Article->getByKeyword($meta_keyword, TRUE);
    $font_size = ($count*4)+8;
    
    if($font_size > 24){
	$font_size = 24;
    }
    
    echo "<a style=\"font-size: ".$font_size."pt;\" title=\"".$count." topics\" href=\"".site_url('search/?tag='.$meta_keyword)."\" >".$meta_keyword."</a>\n";
    
}

?>
</div>