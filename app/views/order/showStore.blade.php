@extends('order.layout')

@section('head')
	<script>
		$(document).ready(function() {
			$('.top-nav li:nth-child(2)').addClass('active');
		});
	</script>
@stop

@section('content')

	<div class=''>
		<h2>開始訂餐</h2>	
		<p class='stores-p'>選擇想訂的店家 <a href="{{ URL::to('order/createStore') }}" class="btn btn-primary btn-xs" ><span class="glyphicon glyphicon-plus"></span> 新增店家</a></p>			
		<div class='list-group'>
		@foreach($stores as $store)			


			<a class='list-group-item' href='{{ URL::to("order/createMission/" . $store->id) }}'>
				<div class="row">
					<div class="col-md-2 col-sm-2 col-xs-4">
					@if (isset($store->photos[0]))
						<div class="img-rounded img-responsive center-cropped" 
							style="background-image: url('{{ asset($store->photos[0]->src) }}')"></div>
					@endif
					</div>
					<div class="col-md-10 col-sm-10 col-xs-8">
						<h4>{{{ $store->name }}}</h4>
						<p class="pre">{{{ $store->detail }}}</p>
					</div>
				</div>
			</a>					
		@endforeach
		</div>
	</div>

		
@stop        
