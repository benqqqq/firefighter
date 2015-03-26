@extends('order.layout')

@section('content')
	
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
			<br>
		@endforeach
				
		
		@foreach ($mission->store->combos as $combo)			
			<span>{{{ $combo->name }}}</span>
			(
			@foreach ($combo->comboItems as $comboItem)
				<span>{{{ $comboItem->item->name }}}</span>
			@endforeach
			)
			<br>
		@endforeach
	</div>
	
	<div class='order'>
		<h2>訂單</h2>
		@foreach ($mission->orders as $order)
			<span>{{{ $order->user->name }}}</span>
			<br>
			@foreach ($order->orderItems as $orderItem)
				<span>{{{ $orderItem->item->name }}} * {{{ $orderItem->quantity }}}</span>
			@endforeach
			<br>
			@foreach ($order->orderCombos as $orderCombo)
				<span>{{{ $orderCombo->combo->name }}}</span>
				(
				@foreach ($orderCombo->combo->comboItems as $comboItem)
					<span>{{{ $comboItem->item->name }}}</span>
				@endforeach
				) * {{{ $orderCombo->quantity }}}
			@endforeach
			
		@endforeach
	</div>
	
@stop        
