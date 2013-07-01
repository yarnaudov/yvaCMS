<div class="article" >
    
    <?php if($article['show_title'] == 'yes'){ ?>
    <div class="title" ><?=$article['title'];?></div>
    <?php } ?>
    
    <div class="content" >
        <?=$article['text'];?>
    </div>
    
    <div class="comments" >
	
	<form name="comment" action="<?=current_url();?>" method="post" >
	    
	    <input type="hidden" name="article_id" value="<?=$article['id'];?>" >
	    
	    <div>
		<label>Име:</label>
		<input type="text" name="name" >
	    </div>
	    
	    <div>
		<label>Коментар:</label>
		<textarea name="comment" ></textarea>
	    </div>
	    
	    <div>
		<label>&nbsp;</label>
		<img id="siimage" style="margin-right: 15px;width: 200px;height: 70px;" src="<?=base_url();?>/plugins/securimage/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" />
		<object type="application/x-shockwave-flash" data="<?=base_url();?>/plugins/securimage/securimage_play.swf?bgcol=#ffffff&amp;icon_file=./images/audio_icon.gif&amp;audio_file=<?=base_url();?>/plugins/securimage/securimage_play.php" width="19" height="19" >
		    <param name="movie" value="<?=base_url();?>/plugins/securimage/securimage_play.swf?bgcol=#ffffff&amp;icon_file=./images/audio_icon.gif&amp;audio_file=<?=base_url();?>/plugins/securimage/securimage_play.php" />
		</object>
		&nbsp;
		<a tabindex="-1" style="border-style: none;" href="#" id="refresh_image" title="Refresh Image" onclick="document.getElementById('siimage').src = '<?=base_url();?>/plugins/securimage/securimage_show.php?sid=' + Math.random(); this.blur(); return false">
		    <img src="<?=base_url();?>plugins/securimage/images/refresh.gif" alt="Reload Image" onclick="this.blur()" align="bottom" border="0" />
		</a>
		<br />
	    </div>
	    
	    <div>
		<button class="add_comment" name="add_comment" >Добави коментар</button>
	    </div>
	    
	</form>
	
	<div class="comments_list" >
	    
	</div>
	
    </div>
    
</div>