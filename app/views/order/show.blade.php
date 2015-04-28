@extends('order.layout')

@section('content')
	<div>
<!--
		<div class='col-md-2'>
			<h2>開始訂餐</h2>	
			<p class='stores-p'>選擇想訂的店家 <a href="{{ URL::to('order/createStore') }}" class="btn btn-primary btn-xs" ><span class="glyphicon glyphicon-plus"></span> 新增店家</a></p>			
			<div class='list-group'>
			@foreach($stores as $store)
				<a class='list-group-item' href='{{ URL::to("order/createMission/" . $store->id) }}'> {{{ $store->name }}}</a>
			@endforeach
			</div>
		</div>
-->
		<div>
			<h2>訂餐中</h2>
			<p>正在進行的訂購</p>
			<div class='list-group'>						
				@foreach($missions as $mission)
				<a href='#' class='list-group-item' onclick="util.toUrl('{{ URL::to("order/$mission->id") }}')">
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
								<small>主揪 : {{{ $mission->user->serial }}}</small>
							</h4>
							<h5>{{{ $mission->created_at }}}</h5>					
						</div>
					</div>
				</a>					
				@endforeach
			</div>
		</div>
		<div>
			<h2>歷史訂單</h2>
			<p>以前的訂購資訊</p>
			<div class='list-group'>						
				@foreach($historyMissions as $mission)
				<a href='#' class='list-group-item' onclick="util.toUrl('{{ URL::to("order/$mission->id") }}')">
					<strong>{{{ $mission->name }}}</strong>
					<strong>({{{ $mission->store->name }}})</strong>			    	
					<small>主揪 : {{{ $mission->user->serial }}}</small>
					<small class="pull-right">{{{ $mission->created_at }}}</small>					
				</a>					
				@endforeach
			</div>
		</div>
	</div>
@stop        
