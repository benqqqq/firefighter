@extends('order.layout')

@section('head')	
	<script>
		$(document).ready(function() {
			$('.top-nav li:nth-child(2)').addClass('active');
			$('.store-list').isotope({
				itemSelector : '.store-list-frame',
				layoutMode : 'masonry'
			});
	
		});
	</script>
@stop

@section('content')

	<div class=''>
		<h2>開始訂餐</h2>	
		<p>選擇想訂的店家 
			<a href="{{ URL::to('order/createStore') }}" class="btn btn-primary btn-xs" >
			<span class="glyphicon glyphicon-plus"></span> 新增店家</a></p>					
		<div class='list-group store-list'>			
		@foreach($stores as $store)			
			<a class='list-group-item store-list-frame col-md-4 col-sm-6 col-xs-12' href='{{ URL::to("order/createMission/" . $store->id) }}'>
				<div class="clearfix store-list-item">
					<div class="col-md-5 col-sm-5 col-xs-5">
					@if (isset($store->photos[0]))
						<div class="img-rounded img-responsive center-cropped" 
							style="background-image: url('{{ asset($store->photos[0]->src) }}')"></div>
					@endif
					</div>
					<div class="col-md-7 col-sm-7 col-xs-7">
						<h4>{{{ $store->name }}}</h4>
						<p class="pre">{{{ $store->detail }}}</p>
					</div>
				</div>
			</a>					
		@endforeach
		</div>
	</div>

		
@stop        
