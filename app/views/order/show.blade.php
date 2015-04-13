@extends('order.layout')

@section('content')
	
	@foreach($missions as $mission)
		<div class='col-md-8 col-md-offset-2 btn btn-primary' onclick="util.toUrl('{{ URL::to("order/$mission->id") }}')">
			<strong>{{{ $mission->name }}}</strong>
			<strong>({{{ $mission->store->name }}})</strong>
	    	<span>主揪 : {{{ $mission->user->name }}}</span>
			<small>{{{ $mission->created_at }}}</small>
		</div>
	
	@endforeach
@stop        
