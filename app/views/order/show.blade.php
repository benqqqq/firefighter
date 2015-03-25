<!doctype html>


<html>
    <head>
    	<title>訂餐 - 基隆消防信二分隊</title>
    	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
    	
    	
    	<script src="http://localhost:3000/socket.io/socket.io.js"></script>
    	<script src="lib/socket.js"></script>
    	
    	
    	<script src="js/orderCtrl.js"></script>
    	
    </head>
    <body ng-app='orderApp' ng-controller='orderCtrl'>
    
        <span ng-bind="orders"></span>
        
		<form method='post' ng-submit="">
			<button type=submit>update</button>
		</form>
		
    </body>
    
</html>