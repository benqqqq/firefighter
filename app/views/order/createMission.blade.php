@extends('order.layout')

@section('content')
	
	<div class="page-header">
		<h1>新增訂購</h1>
	</div>
	<div>
		<h3>訂購資訊</h3>		
		
	</div>
	<div>
		<h3>{{{ $store->name }}} <small><a href="{{ URL::to('order/editStore/' . $store->id) }}"><span class="glyphicon glyphicon-pencil"></span> 修改</a></small></h3>	
		
		<div>
			<p><span class='glyphicon glyphicon-phone-alt' aria-hidden="true"></span> <strong>電話 :</strong> {{{ $store->phone }}}</p>
			<p><span class='glyphicon glyphicon-home' aria-hidden="true"></span> <strong>地址 :</strong> {{{ $store->address }}}</p>
			<p><span class='glyphicon glyphicon-info-sign' aria-hidden="true"></span> <strong>備註 :</strong> {{{ $store->detail }}}</p>
		</div>

	</div>
@stop        
