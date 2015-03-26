@extends('order.layout')

@section('content')
	@foreach($missions as $mission)
		<div class='block' onclick="util.toUrl('{{ URL::to("order/$mission->id") }}')">
			<span class='block-word'>{{{ $mission->name }}}</span>
			<span class='block-word'>{{{ $mission->store->name }}}</span>
	    	<span class='block-word'>主揪 : {{{ $mission->user->name }}}</span>
			<time class='block-word'>{{{ $mission->created_at }}}</time>
		</div>
	
	@endforeach
@stop        
