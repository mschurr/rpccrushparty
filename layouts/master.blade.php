
@style(URL::asset('css/master.css'))
@style(URL::asset('css/layout.css'))
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