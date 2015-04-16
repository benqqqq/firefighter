@extends('order.layout')

@section('content')
	<div class='row'>
		<div class='col-md-2'>
			<h2 class='stores-h2'>開始訂餐</h2>	
			<p class='stores-p'>選擇想訂的店家</p>
			<div class='list-group'>
			@foreach($stores as $store)
				<a class='list-group-item' href='#'>{{{ $store->name }}}</a>
			@endforeach
			</div>
		</div>
		<div class='col-md-8'>
			<h2 class='order-h2'>訂餐中</h2>
			<p class='order-p'>正在進行的訂購</p>
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
