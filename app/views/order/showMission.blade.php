@extends('order.layout')

@section('head')

@stop

@section('content')
	<span ng-init="url = '{{ URL::to("") }}'"></span>
	<span ng-init="missionId = {{ $mission->id }}"></span>
	<span ng-init='initStore({{ $mission->store->items }}  , {{ $mission->store->combos }})'></span>
	

	<h1>{{{ $mission->name }}} ({{{ $mission->store->name }}}) <small>主揪 : {{{ $mission->user->name }}} {{{ $mission->created_at }}}</small></h1>	

	
	<div class='row'>
		<span class='col-md-2 col-md-offset-1'>電話 : {{{ $mission->store->phone }}}</span>
		<span class='col-md-4'>地址 : {{{ $mission->store->address }}}</span>
	</div>
	<div class='row'>
		<p class='col-md-10 col-md-offset-1'>備註 : {{{ $mission->store->detail }}}</p>
	</div>
	
	<div class='col-md-12'>			
		<h2>菜單</h2>
		@foreach ($mission->store->items as $item)
			<span>{{{ $item->name }}}</span>
			<span>{{{ $item->price }}}$</span>
			@if (count($item->opts) > 0)
				- 
			@endif
			
			@foreach ($item->opts as $opt)
				<input type='checkbox' ng-model='itemOpt[{{ $item->id }}][{{ $opt->id }}]' 
					ng-change='changeItemPrice({{ $item->id }}, {{ $opt->id }}, {{ $opt->price }})'>
				<span>{{{ $opt->name }}}</span> 
				<span>+{{{ $opt->price }}}$</span>
			@endforeach				
			> <span ng-bind='iPrice[{{ $item->id }}]'></span>$
			<span ng-click="orderItem({{ $item->id }})">訂</span>
			<br>
		@endforeach
		
		@foreach ($mission->store->combos as $combo)			
			<div>
				<span>{{{ $combo->name }}}</span>
				(
				@foreach ($combo->items as $item)
					<span>{{{ $item->name }}}</span>
					<span>{{{ $item->price }}}$</span>
					<span>
						@if (count($item->opts) > 0)
							-
						@endif
						
						@foreach ($item->opts as $opt)
							<input type='checkbox' ng-model='comboItemOpt[{{ $combo->id }}][{{ $item->id }}][{{ $opt->id }}]'
								ng-change='changeComboPrice({{ $combo->id }}, {{ $item->id }}, {{ $opt->id }}, {{ $opt->price }})'>
							<span>{{{ $opt->name }}}</span> 
							<span>+{{{ $opt->price }}}$</span>
						@endforeach
					</span>
				@endforeach
				)				
				> <span ng-bind='cPrice[{{ $combo->id }}]'></span>$
				<span ng-click="orderCombo({{ $combo->id }})">訂</span>
			</div>
		@endforeach
	</div>
	
	<div class='col-md-12' ng-init='myOrder = {{ $myOrder }}'>	
		<h2>我的訂單</h2>
		<div ng-repeat='order in myOrder'>
			{{ View::make('order.userOrder') }}
		</div>
	</div>

	<div class='col-md-12' ng-init='otherOrders = {{ $otherOrders }}'>	
		<h2>其他訂單</h2>
		<div ng-repeat='order in otherOrders'>
			{{ View::make('order.userOrder') }}
		</div>
	</div>
	
	<div class='col-md-12' ng-init='statistic = {{ $statistic }}'>
		<h2>統計</h2>
		<p ng-repeat='item in statistic.item'>
			<span ng-bind='item.name'></span>
			(
			<span ng-bind='item.optStr'></span>
			) * 
			<span ng-bind='item.quantity'></span>
		</p>
		<p ng-repeat='combo in statistic.combo'>
			<span ng-bind='combo.name'></span>
			[
			<span ng-repeat='item in combo.items'>
				<span ng-bind='item.name'></span>
				(<span ng-bind='item.optStr'></span>)
			</span>
			] *
			<span ng-bind='combo.quantity'></span>
		</p>
		<p>
			總共 : <span ng-bind='statistic.price.total'></span>$
			已付 : <span ng-bind='statistic.price.paid'></span>$
			<span ng-show='statistic.price.total - statistic.price.paid > 0'
				>- 少<span ng-bind='statistic.price.total - statistic.price.paid'></span>$</span>
			<span ng-show='statistic.price.total - statistic.price.paid < 0'
				>- 退<span ng-bind='statistic.price.paid - statistic.price.total'></span>$</span>
		</p>
	</div>
	
@stop        
