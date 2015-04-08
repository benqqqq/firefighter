@extends('order.layout')

@section('head')

@stop

@section('content')
	<span ng-init="url = '{{ URL::to("") }}'"></span>
	<span ng-init="missionId = {{ $mission->id }}"></span>
	<span ng-init='initStore({{ $mission->store->items }}  , {{ $mission->store->combos }})'></span>
	
	<div class='block'>
		<span class='block-word'>{{{ $mission->name }}}</span>
		<span class='block-word'>{{{ $mission->store->name }}}</span>
    	<span class='block-word'>主揪 : {{{ $mission->user->name }}}</span>
		<time class='block-word'>{{{ $mission->created_at }}}</time>
	</div>
	
	<div class='store'>
		<h1>{{{ $mission->store->name }}}</h1>
		<span>{{{ $mission->store->phone }}}</span>
		<span>{{{ $mission->store->address }}}</span>
		<div>{{{ $mission->store->detail }}}</div>
		
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
	<div class='order' ng-init='orders = {{ $mission->orders }}'>	
		<h2>訂單</h2>
		<div ng-repeat='order in orders'>
			<p ng-bind='order.user.serial'></p>
			<p ng-repeat='item in order.items'>
				<span ng-bind='item.name'></span>
				(<span ng-bind='item.pivot.optStr'></span>)
				<span> * </span>
				<span ng-bind='item.pivot.quantity'></span>
				<span ng-bind='item.price + item.pivot.optPrice'></span>$
				<span ng-click="decreaseItem(order.id, item.id, item.pivot.optStr)">刪</span>
			</p>
			<p ng-repeat='orderCombo in order.order_combos'>
				<span ng-bind='orderCombo.combo.name'></span>
				
				[
				<span ng-repeat='item in orderCombo.items'>
					<span ng-bind='item.name'></span>
					(<span ng-bind='item.pivot.optStr'></span>)
				</span>
				] * <span ng-bind='orderCombo.quantity'></span>
				<span ng-click="decreaseOrderCombo(orderCombo.id)">刪</span>
			</p>			
		</div>
	</div>
	
	<div class='statistic'>
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
	</div>
	
@stop        
