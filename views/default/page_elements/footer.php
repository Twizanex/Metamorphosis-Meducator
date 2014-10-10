<?php

	/**
	 * Elgg footer
	 * The standard HTML footer that displays across the site
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 * 
	 */
	 
	 // get the tools menu
	//$menu = get_register('menu');

?>

<div class="clearfloat"></div>

<div id="layout_footer">
<table width="958" height="79" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="210" height="50">

		</td>
		
		<td width="548" height="50" align="right">
		<p class="footer_toolbar_links">
		<?php
			echo elgg_view('footer/links');
		?>
		</p>
		</td>
	</tr>
	
	<tr>
		<td width="400" height="28">
		<a href="http://www.elgg.org" target="_blank">
		<img src="<?php echo $vars['url']; ?>_graphics/powered_by_elgg_badge_light_bckgnd.gif" border="0" />
		<a href="http://www.meducator.net"><img src="<?php echo $vars['url']; ?>_graphics/b1_160x33_wb.jpg" border="0" alt="mEducator" height="33" width="160" /></a>
		</a>
		</td>
		
		<td width="748" height="28" align="right">
		<p class="footer_legal_links"><small>
		 	Website under <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0-faq.html">GNU-GPL v2</a>.<br />
						<br/><font color="gray"> For any assistance contact <a href="http://metamorphosis.med.duth.gr/pg/profile/lordanton">Nikolas Dovrolis </a></font>AT alex.duth.gr <br/>
						<a href="http://metamorphosis.med.duth.gr/pg/profile/kaldoudi">Eleni Kaldoudi </a></font>AT med.duth.gr 
		</p>
		      <br />
	
		 <img src="<?php echo $vars['url']; ?>mod/powered/graphics/rss-powered.png" border="0" />
		 <img src="<?php echo $vars['url']; ?>mod/powered/graphics/openid-powered.png" border="0" />
		 <img src="<?php echo $vars['url']; ?>mod/powered/graphics/foaf-powered.png" border="0" />
 <img src="<?php echo $vars['url']; ?>mod/powered/graphics/rdf-powered.png" border="0" />
  		<!-- <img src="<?php echo $vars['url']; ?>mod/powered/graphics/oauth-powered.png" border="0" />-->
		<!-- <img src="<?php echo $vars['url']; ?>mod/powered/graphics/omb-powered.png" border="0" />-->
		<!-- <img src="<?php echo $vars['url']; ?>mod/powered/graphics/listserv-powered.png" border="0" />-->
  		<!-- <img src="<?php echo $vars['url']; ?>mod/powered/graphics/xmmp-powered.png" border="0" />-->
		
       <br />

		</td>
	</tr>
</table>
</div><!-- /#layout_footer -->

<div class="clearfloat"></div>

</div><!-- /#page_wrapper -->
</div><!-- /#page_container -->
<!-- insert an analytics view to be extended -->
<?php
	echo elgg_view('footer/analytics');
?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-12813298-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
  })();
  
  if(typeof onLoad != 'function') {
    function onLoad() { return; }
  }

</script>
</body>
</html>