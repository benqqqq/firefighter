@extends('order.layout')

@section('head')
	<script>
		function flashTooltip(target, opt) {
			if (opt) {
				$(target).tooltip(opt);
			}
			$(target).tooltip('show');			
			setTimeout(function() {
				$(target).tooltip('hide');	
			}, 3000);
		}
		function initTooltip() {
			$('[data-toggle="tooltip"]').tooltip();
			
			var $scope = angular.element($('.orderBody')).scope();
			if (!$scope.user) {
				flashTooltip('#nav-serial');	
			} else if ({{ count($missions) > 0 ? 1 : 0}}) {
				if ($('.navbar-collapse').css('display') == 'none') {
					flashTooltip('.navbar-toggle', {
						placement : 'bottom',
						title : '訂購已新增的餐點'
					});					
				}				
				flashTooltip('#nav-order');
			} else {
				if ($('.navbar-collapse').css('display') == 'none') {
					flashTooltip('.navbar-toggle', {
						placement : 'bottom',
						title : '選擇你想訂的店家'
					});					
				}
				flashTooltip('#nav-new');
			}
		}
		
		$(document).ready(function() {
			$('.top-nav li:nth-child(1)').addClass('active');
			
			initTooltip();
			
		});
	</script>
@stop

@section('content')
	<div>

		<div>
			@if (count($missions) > 0)
				<h2>訂餐中</h2>
				<p>正在進行的訂購</p>
				<div class='list-group'>						
					@foreach($missions as $mission)
					<a class='list-group-item' href='{{ URL::to("order/$mission->id") }}'>
						<div class="row">
							<div class="col-md-2 col-sm-3 col-xs-4">
							@if (isset($mission->store->photos[0]))
								<div class="img-rounded img-responsive center-cropped" 
									style="background-image: url('{{ asset($mission->store->photos[0]->src) }}')"></div>
							@endif
							</div>
							<div class="col-md-10 col-sm-9 col-xs-8">
								<h4>
									<strong>{{{ $mission->name }}}</strong>
									<strong>({{{ $mission->store->name }}})</strong>			    						
									<small class="nowrap">主揪 : {{{ $mission->user->serial }}}</small>
								</h4>
								<h5>{{{ $mission->created_at }}}</h5>					
							</div>
						</div>
					</a>					
					@endforeach
				</div>
			@else
				<div class="jumbotron">
					<h2>目前沒有進行中的訂購</h2>
					<p></p>
					<p><a class="btn btn-primary btn-lg" href="{{ URL::to('order/selectStore') }}">新增訂購</a></p>
				</div>
			@endif
		</div>
		<div>
			<h2>歷史訂單</h2>
			<p>以前的訂購資訊</p>
			<div class='list-group'>						
				@foreach($historyMissions as $mission)
				<a class='list-group-item' href='{{ URL::to("order/$mission->id") }}'>
					<div class="row">
						<div class="col-md-2 col-sm-2 col-xs-4">
						@if (isset($mission->store->photos[0]))
							<div class="img-rounded img-responsive center-cropped" 
								style="background-image: url('{{ asset($mission->store->photos[0]->src) }}')"></div>
						@endif
						</div>
						<div class="col-md-10 col-sm-10 col-xs-8">
							<h4>
								<strong>{{{ $mission->name }}}</strong>
								<strong>({{{ $mission->store->name }}})</strong>			    						
								<small class="nowrap">主揪 : {{{ $mission->user->serial }}}</small>
							</h4>
							<h5>{{{ $mission->created_at }}}</h5>					
						</div>
					</div>
				</a>					
				@endforeach
			</div>
		</div>
	</div>
@stop        
