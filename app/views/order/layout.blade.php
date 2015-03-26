<!doctype html>


<html>
    <head>
    	<title>訂餐 - 基隆消防信二分隊</title>
    	
    	
		{{ HTML::script('http://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js') }}
    	
    	{{ HTML::script('http://localhost:3000/socket.io/socket.io.js') }}
    	{{ HTML::script('lib/socket.js') }}
    	
    	{{ HTML::script('js/orderCtrl.js') }}
    	{{ HTML::script('js/util.js') }}
    	
    	{{ HTML::style('css/order.css') }}        
    </head>
    <body ng-app='orderApp' ng-controller='orderCtrl'>
    	
    	@yield('content')
    	
    </body>
    
</html>