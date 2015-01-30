
@style(URL::asset('css/master.css'))
@style(URL::asset('css/layout.css'))
@script(URL::asset('js/jquery.min.js'))
@link('shortcut icon', 'image/x-icon', URL::asset('img/favicon.ico'), '')

<div class="wrapper">
	<div class="content_wrapper">
		<div class="content">
		@yield('content')
		</div>
	</div>
	<div class="footer">
		This survey system was created for <a href="http://rpc.rice.edu/" target="_blank">Rice Program Council</a> by <a href="mailto:mschurr@rice.edu">Matthew Schurr</a>. The source code is available <a href="https://github.com/mschurr/rpccrushparty" target="_blank">here</a>.
	</div>
</div>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47685499-1', 'crush.riceapps.org');
  ga('send', 'pageview');
</script>
