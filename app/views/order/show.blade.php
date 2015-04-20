@extends('order.layout')

@section('content')
	<div class='row'>
		<div class='col-md-2'>
			<h2>開始訂餐</h2>	
			<p class='stores-p'>選擇想訂的店家 <a href="{{ URL::to('order/createStore') }}" class="btn btn-primary btn-xs" ><span class="glyphicon glyphicon-plus"></span> 新增店家</a></p>			
			<div class='list-group'>
			@foreach($stores as $store)
				<a class='list-group-item' href='{{ URL::to("order/createMission/" . $store->id) }}'> {{{ $store->name }}}</a>
			@endforeach
			</div>
		</div>
		<div class='col-md-8'>
			<h2>訂餐中</h2>
			<p>正在進行的訂購</p>
			<div class='list-group'>						
				@foreach($missions as $mission)
				<a href='#' class='list-group-item' onclick="util.toUrl('{{ URL::to("order/$mission->id") }}')">
					<strong>{{{ $mission->name }}}</strong>
					<strong>({{{ $mission->store->name }}})</strong>
			    	<span>主揪 : {{{ $mission->user->name }}}</span>
					<small>{{{ $mission->created_at }}}</small>
				</a>					
				@endforeach
			</div>
		</div>
	</div>
@stop        
