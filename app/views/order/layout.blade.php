<!doctype html>


<html>
    <head>
    	<title>訂餐 - 基隆消防信二分隊</title>
    	
    	<script>
            var hostSplit = '{{ URL::to("/") }}'.split(':');
            var host = hostSplit[0] + ':' + hostSplit[1];
        </script>
		{{ HTML::script('http://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js') }}
		{{ HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
    	
		{{ HTML::script('lib/socket.io.js') }}
    	{{ HTML::script('lib/socket.js') }}
    	
    	{{ HTML::script('js/orderCtrl.js') }}
    	{{ HTML::script('js/util.js') }}
    	
    	{{ HTML::style('css/order.css') }}        
    	
    	@yield('head')
    </head>
    <body ng-app='orderApp' class='orderBody' ng-controller='orderCtrl'>
    	<header>
			@if (!Auth::check())
				<a class='btn bck-blue' href='{{ URL::to("login") }}'>登入</a>
			@else
				<span>{{ Auth::user()->serial }}</span>
				<a class='btn bck-blue' href='{{ URL::to("logout") }}'>登出</a>
			@endif
    	</header>
    	<div>
	    	@yield('content')
    	</div>
    </body>
    
</html>
