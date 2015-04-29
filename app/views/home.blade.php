<!doctype html>
<html>
    <head>
    	<!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        
    	<title>基隆消防信二分隊</title>
    	
    </head>
    <body>
    	<div class="jumbotron">
    		<div class="container">
		    	<h1>基隆消防信二分隊</h1>	
		    	<hr>			
				<a class="btn btn-warning btn-lg" href={{ URL::to('order') }}>訂餐</a>
				<hr>
				<a class="btn btn-primary btn-lg" href={{ URL::to('storableDayWork') }}>勤務分配表</a>
				<h5>僅供參考，實際依值班台為準</h5>
    		</div>
    	</div>
    </body>
    
</html>