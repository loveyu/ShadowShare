<div class="footer">
	<p><span class="love">❤</span>来自 <a href="https://www.loveyu.org">loveyu</a> 的小创意</p>

	<p class="notice help-block">非特殊数据，访问一次后即失效！</p>
</div>
</div>
<?php if($_SERVER['HTTP_HOST'] != "cd.loc"): ?>
	<script type="text/javascript">
		var _paq = _paq || [];
		_paq.push(["setCookieDomain", "*.changda.club"]);
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
		(function () {
			var u = "https://www.loveyu.org/tj/";
			_paq.push(['setTrackerUrl', u + 'piwik.php']);
			_paq.push(['setSiteId', 6]);
			var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
			g.type = 'text/javascript';
			g.async = true;
			g.defer = true;
			g.src = u + 'piwik.js';
			s.parentNode.insertBefore(g, s);
		})();
	</script>
	<noscript><p><img src="https://www.loveyu.org/tj/piwik.php?idsite=6" style="border:0;" alt=""/></p></noscript>
<?php endif; ?>
<script>
	console.log("页面加载 <?php echo c()->getTimer()->get_second() ?> 秒， 数据库查询 <?php	echo get_db_query_count() ?> 次。");
</script>
<?php footer_hook(); ?>
</body>
</html>