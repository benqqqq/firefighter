@extends('order.layout')

@section('head')

@stop

@section('content')
	<div>
		<div>
			<h2>刪除的訂單</h2>
			<div class='list-group'>						
				@foreach($missions as $mission)
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
