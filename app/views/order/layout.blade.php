<!doctype html>


<html>
    <head>
    	<!-- <title>訂餐 - 基隆消防信二分隊</title> -->
    	
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	
    	<script>
            var hostSplit = '{{ URL::to("/") }}'.split(':');
            var host = hostSplit[0] + ':' + hostSplit[1];
        </script>
		{{ HTML::script('http://ajax.googleapis.com/ajax/libs/angularjs/1.3.0/angular.min.js') }}		
		{{ HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
    	{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js') }}
    	
		{{ HTML::script('lib/socket.io.js') }}
    	{{ HTML::script('lib/socket.js') }}
    	
    	
    	{{ HTML::script('js/orderCtrl.js') }}
    	{{ HTML::script('js/util.js') }}
         
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    	{{ HTML::style('css/order.css') }}
    	

    	
    	
    	<script>
    		$(document).ready(function() {
	    		
	    		@if (Session::has('message'))
    				$('#messageModal').modal();
				@endif
			});	
    	</script>    	
    	
    	@yield('head')
    </head>
    <body ng-app='orderApp' class='orderBody' ng-controller='orderCtrl'>
    	<span ng-init='users = {{ $users }}'></span>
    
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
    				<ul class="nav navbar-nav navbar-right">
    					<li class="navbar-text">番號 : </li>
    					<li class="navbar-text">
	    					<select class="form-control userSelect" ng-model="user" ng-options="user.serial for user in users"
	    						ng-init="user=loadUser()">
								<option value="">-- 請選擇 --</option>
							</select>
    					</li>    					
    				</ul>
<!--
    				<ul class='nav navbar-nav navbar-right'>
					@if (!Auth::check())
						<li><a href='{{ URL::to("login") }}'>登入</a></li>
					@else
						<li><a href='#'>番號 : {{ Auth::user()->serial }}</a></li>
						<li><a href='{{ URL::to("logout") }}'>登出</a></li>
					@endif
    				</ul>
-->
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
    	
    	<!-- Modal -->
		<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"
							><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">注意 !</h4>
					</div>
					<div class="modal-body">					
						<p id="message">{{{ Session::get('message') }}}</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">確定</button>
					</div>
				</div>
			</div>
		</div>
    </body>
    
</html>

