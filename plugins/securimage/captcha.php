
<img id="siimage" align="left" style="padding-right: 5px; border: 0px solid;" src="<?=base_url();?>plugins/securimage/securimage_show.php?sid=<?php echo md5(time()) ?>" />
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="19" height="19" id="SecurImage_as3" align="middle">
  <param name="allowScriptAccess" value="sameDomain" />
  <param name="allowFullScreen" value="true" />
  <param name="movie" value="<?=base_url();?>plugins/securimage/securimage_play.swf?audio=<?=base_url();?>plugins/securimage/securimage_play.php&amp;bgColor1=#777&amp;bgColor2=#fff&amp;iconColor=#000&amp;roundedCorner=5" />
  <param name="quality" value="high" />
  <param name="bgcolor" value="#ffffff" />
  <embed src="<?=base_url();?>plugins/securimage/securimage_play.swf?audio=<?=base_url();?>plugins/securimage/securimage_play.php&amp;bgColor1=#777&amp;bgColor2=#fff&amp;iconColor=#000&amp;roundedCorner=5" quality="high" bgcolor="#ffffff" width="19" height="19" name="SecurImage_as3" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>

<br />
      
<!-- pass a session id to the query string of the script to prevent ie caching -->
<a style="border-style: none" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = '<?=base_url();?>plugins/securimage/securimage_show.php?sid=' + Math.random(); return false"><img src="<?=base_url();?>plugins/securimage/images/refresh.gif" alt="Reload Image" border="0" onclick="this.blur()" align="bottom" /></a>
