<!doctype html>


<html>
    <head>
    	<title>訂餐 - 基隆消防信二分隊</title>
    	
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
		{{ HTML::script('lib/bootstrap-confirmation.js') }}
		
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.0/isotope.pkgd.js"></script>
		
		{{ HTML::script('lib/mobileCheck.js') }}

    	{{ HTML::style('css/order.css') }}
    	

    	
    	
    	<script>
    		$(document).ready(function() {
	    		
	    		@if (Session::has('message'))
    				$('#messageModal').modal();
				@endif
	
				$(".modal").on("shown.bs.modal", function()  { // any time a modal is shown
					var urlReplace = "#" + $(this).attr('id'); // make the hash the id of the modal shown
					history.pushState(null, null, urlReplace); // push state that hash into the url
				});
					
				// If a pushstate has previously happened and the back button is clicked, hide any modals.
				$(window).on('popstate', function() { 
					$(".modal").modal('hide');
				});
				
				$('[data-toggle="confirmation"]').confirmation({
					btnOkLabel : '確定',
					btnCancelLabel : '取消',
					popout : true
				});
				$
				
			});

			

    	</script>    	
    	
    	@yield('head')
    </head>
    <body ng-app='orderApp' class='orderBody' ng-controller='orderCtrl'>
    	<span ng-init='users = {{ $users }}'></span>
    	<nav class='navbar navbar-default navbar-fixed-top'>
    		<div class='container'>
    			<div class='navbar-header pull-left'>	          			
					<a class="navbar-brand" href="{{ URL::to('/') }}">信二</a>
    			</div>
    			
    			<div class="navbar-header pull-right">
    				<ul class="nav pull-left">
	    				<li class="navbar-text pull-left">番號 : </li>
	    				<li class="dropdown pull-right"
	    					id="nav-serial" title="選擇後即可訂餐、新增" data-toggle="tooltip" data-placement="bottom"> 
	    					<a href="" data-toggle="dropdown" class="dropdown-toggle">
		    					<span class="glyphicon glyphicon-user"></span> 
		    						<span ng-bind="user.serial"></span>
		    						<span ng-show="user.serial == null">尚未選擇</span>
		    					<b class="caret"></b>
							</a>
							<ul class="dropdown-menu serial-dropdown" ng-init="user = loadUser();">
								<li ng-repeat="user in users | orderBy:mySortFunc"
									><a href="" ng-click="editUser(user)">{[{ user.serial }]}</a>
								</li>
							</ul>
	    				</li>	    				
    				</ul>

    				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" 
						aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

                <div class="clearfix hidden-lg hidden-md"></div>
                <div class="collapse navbar-collapse pull-right" id="navbar">
                        <ul class="nav navbar-nav top-nav">
                            <li id="nav-order" title="訂購已新增的餐點" data-toggle="tooltip" data-placement="bottom">
                            	<a href="{{ URL::to('order') }}">訂餐</a>
                            </li>
                            <li id="nav-new" title="選擇你想訂的店家" data-toggle="tooltip" data-placement="bottom">
                            	<a href="{{ URL::to('order/selectStore') }}">新增</a>
                            </li>
                        </ul>
                    </div>
    

    			</div>
                

    		</div>
    	</nav>
    	<div class='container'>
	    	@yield('content')	    	
    	</div>
    	
    	<footer class="footer">
    		<div class="container">
    		
	    		<p>© 2015</p>
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

