<!doctype html>


<html>
    <head>
    	<!-- <title>訂餐 - 基隆消防信二分隊</title> -->
    	
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
            	
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    	<!-- {{ HTML::style('css/order.css') }}         -->
    	
    	@yield('head')
    </head>
    <body ng-app='orderApp' class='container orderBody' ng-controller='orderCtrl'>
    	<header class='row'>
			@if (!Auth::check())
				<a class='col-md-1 btn btn-default' href='{{ URL::to("login") }}'>登入</a>
			@else
				<span class='col-md-1'>番號 : {{ Auth::user()->serial }}</span>
				<a class='col-md-1 btn btn-default' href='{{ URL::to("logout") }}'>登出</a>
			@endif
    	</header>
    	<div>
	    	@yield('content')
    	</div>
    </body>
    
</html>
