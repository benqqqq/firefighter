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
		<div class="jumbotron">
			<h2>訂餐步驟</h2>
			<button class="btn btn-primary btn-lg" data-toggle="collapse" data-target="#explain-image">點我觀看</button>
			<p></p>
			<div class="collapse" id="explain-image">
				<p>
					<h4>1. 進入目前正在進行的訂餐團</h4>
					<img src="{{{ asset('images/1.png') }}}" alt="explain-image" class="img-rounded img-responsive">
				</p>
				<p>
					<h4>2. 找到想訂的餐點 (黑底白字是預設選項，點藍色齒輪修改)</h4>
					<img src="{{{ asset('images/2.png') }}}" alt="explain-image" class="img-rounded img-responsive">
				</p>
				<p>
					<h4>3. 修改好選項</h4>
					<img src="{{{ asset('images/3.png') }}}" alt="explain-image" class="img-rounded img-responsive">
				</p>
				<p>
					<h4>4. 點選</h4>
					<img src="{{{ asset('images/4.png') }}}" alt="explain-image" class="img-rounded img-responsive">
				</p>
				<p>
					<h4>5. 完成囉 拉到最底下看看自己的訂單</h4>
					<img src="{{{ asset('images/5.png') }}}" alt="explain-image" class="img-rounded img-responsive">
				</p>
			</div>
		</div>

		<div>
			@if (count($missions) > 0)
				<h2>跟團</h2>
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
					<p><a class="btn btn-primary btn-lg" href="{{ URL::to('order/selectStore') }}">主揪 - 新增訂購</a></p>
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
		<div>
			<h5><a href="{{ URL::to('order/trash') }}"><span class="glyphicon glyphicon-trash"></span> 已刪除的訂單</a></h5>			
		</div>
	</div>
@stop        
