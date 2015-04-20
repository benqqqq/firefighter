<!doctype html>


<html>
    <head>
    	<!-- <title>訂餐 - 基隆消防信二分隊</title> -->
    	
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	
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

    	{{ HTML::style('css/order.css') }}
    	
    	@yield('head')
    </head>
    <body ng-app='orderApp' class='orderBody' ng-controller='orderCtrl'>
    	<nav class='navbar navbar-default navbar-fixed-top'>
    		<div class='container'>
    			<div class='navbar-header'>	
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" 
						aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
          			
					<a class="navbar-brand" href="{{ URL::to('/order') }}">訂餐</a>
    			</div>
    			<div id='navbar' class='collapse navbar-collapse'>
    				<ul class='nav navbar-nav navbar-right'>
					@if (!Auth::check())
						<li><a href='{{ URL::to("login") }}'>登入</a></li>
					@else
						<li><a href='#'>番號 : {{ Auth::user()->serial }}</a></li>
						<li><a href='{{ URL::to("logout") }}'>登出</a></li>
					@endif
    				</ul>
    			</div>
    		</div>
    	</nav>
    	<div class='container'>
	    	@yield('content')	    	
    	</div>
    	
    	<footer class="footer">
    		<div class="container">
	    		<p>© 2015 Benqqqq</p>
    		</div>
    	</footer>
    </body>
    
</html>

