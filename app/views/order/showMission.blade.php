@extends('order.layout')

@section('head')

@stop

@section('content')
	<span ng-init="url = '{{ URL::to("") }}'"></span>
	<span ng-init="missionId = {{ $mission->id }}"></span>
	<span ng-init='initOpts({{ $mission->store->combos }})'></span>
	
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
			<span>
				@if (count($item->opts) > 0)
					- 
				@endif
				
				@foreach ($item->opts as $opt)
					<input type='checkbox' ng-model='itemOpt[{{ $item->id }}][{{ $opt->id }}]'>
					<span>{{{ $opt->name }}}</span> 
				@endforeach				
				
				<span ng-click="orderItem({{ $item->id }})">訂</span>
			</span>
			<br>
		@endforeach
				
		
		@foreach ($mission->store->combos as $combo)			
			<div>
				<span>{{{ $combo->name }}}</span>
				(
				@foreach ($combo->comboItems as $comboItem)
					<span>{{{ $comboItem->item->name }}}</span>
					<span>
						@if (count($comboItem->item->opts) > 0)
							-
						@endif
						
						@foreach ($comboItem->item->opts as $opt)
							<input type='checkbox' ng-model='comboItemOpt[{{ $combo->id }}][{{ $comboItem->id }}][{{ $opt->id }}]'>
							<span>{{{ $opt->name }}}</span> 
						@endforeach
					</span>
				@endforeach
				)				
				<span ng-click="orderCombo({{ $combo->id }})">訂</span>
			</div>
		@endforeach
	</div>
	
	<div class='order' ng-init='orders = {{ $mission->orders }}'>
		<h2>訂單</h2>
		<div ng-repeat='order in orders'>
			<p ng-bind='order.user.serial'></p>
			<p ng-repeat='orderItem in order.order_items'>
				<span ng-bind='orderItem.item.name'></span>
				(<span ng-bind='orderItem.optStr'></span>)
				<span> * </span>
				<span ng-bind='orderItem.quantity'></span>
				<span ng-click="decreaseOrderItem(orderItem.id)">刪</span>
			</p>
			<p ng-repeat='orderCombo in order.order_combos'>
				<span ng-bind='orderCombo.combo.name'></span>
				
				[
				<span ng-repeat='orderComboItem in orderCombo.order_combo_items'>
					<span ng-bind='orderComboItem.item.name'></span>
					(<span ng-bind='orderComboItem.optStr'></span>)
				</span>
				] * <span ng-bind='orderCombo.quantity'></span>
				<span ng-click="decreaseOrderCombo(orderCombo.id)">刪</span>
			</p>			
		</div>
	</div>
	
@stop        
