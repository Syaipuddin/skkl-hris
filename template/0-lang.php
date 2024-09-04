<div style="margin-top: 5px; margin-right: 0px;">
	<a tabindex="0"  class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="hierarchybreadcrumb2"><span class="ui-icon ui-icon-triangle-1-s">
	</span><?php echo $word[242]; //language ?></a>
	<?php $url_exp = explode('/', $_SERVER['PHP_SELF']);
		  $url_aja = $url_exp[1];
		  $langEN = 'EN';
		  $langID = 'ID';
		  ?>
	<div id="news-items-2" class="hidden">
		<ul>
			<li><a href="<?php echo $url_aja ?>?lang=EN" id="en">English</a></li>
			<li><a href="<?php echo $url_aja ?>?lang=ID" id="ind">Bahasa Indonesia</a></li>
		</ul>
	</div>
</div>