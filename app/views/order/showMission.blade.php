@extends('order.layout')

@section('head')

@stop

@section('content')
	<span ng-init="url = '{{ URL::to("") }}'"></span>
	<span ng-init="missionId = {{ $mission->id }}"></span>
	<span ng-init='initStore({{ $mission->store->items }}  , {{ $mission->store->combos }})'></span>	

	<h2>{{{ $mission->name }}} ({{{ $mission->store->name }}}) <small>主揪 : {{{ $mission->user->name }}} {{{ $mission->created_at }}}</small></h2>	

	<div class='store-info'>
		<p><span class='glyphicon glyphicon-phone-alt' aria-hidden="true"></span> <strong>電話 :</strong> {{{ $mission->store->phone }}}</p>
		<p><span class='glyphicon glyphicon-home' aria-hidden="true"></span> <strong>地址 :</strong> {{{ $mission->store->address }}}</p>
		<p><span class='glyphicon glyphicon-info-sign' aria-hidden="true"></span> <strong>備註 :</strong> {{{ $mission->store->detail }}}</p>
	</div>
	
	<div>			
		<h2>菜單</h2>
		@foreach ($mission->store->items as $item)
			<p>
				@if (count($item->opts) > 0)		
					<a href="#"><span class="glyphicon glyphicon-cog" data-toggle="modal" data-target="#myModal{{ $item->id }}" 
						ng-click=''</span></a>
				@endif
				
				{{{ $item->name }}} 

				@foreach ($item->opts as $opt)
					<span ng-show='itemOpt[{{ $item->id }}][{{ $opt->id }}]' class='badge'>{{{ $opt->name }}}</span>
				@endforeach

				<span class='label label-primary'><span ng-bind='iPrice[{{ $item->id }}]'></span>$</span>
				<a href="#"><span ng-click="orderItem({{ $item->id }})" class="glyphicon glyphicon-plus"></span></a>
			</p>
			
			<div class="modal fade optModal" id="myModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"
								><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">{{{ $item->name }}} <span class='label label-primary '>{{{ $item->price }}}$</span></h4>
						</div>
						<div class="modal-body">
							<table class='table table-striped'>
								<tr>
									<th>名稱</th><th>加價</th>
								</tr>
								@foreach ($item->opts as $opt)
									<tr>
										<td>
											<span class="checkbox">
												<label>
													<input type='checkbox' ng-model='itemOpt[{{ $item->id }}][{{ $opt->id }}]' 
														ng-change='changeItemPrice({{ $item->id }}, {{ $opt->id }}, {{ $opt->price }})'>
													{{{ $opt->name }}}
												</label>
											</span>
										</td>										
										<td><span class='label label-primary '>+{{{ $opt->price }}}$</span></td>
									</tr>
								@endforeach	
							</table>
							
						</div>
						<div class="modal-footer">
							{{{ $item->name }}} 
							@foreach ($item->opts as $opt)
								<span ng-show='itemOpt[{{ $item->id }}][{{ $opt->id }}]' class='badge'>{{{ $opt->name }}}</span>
							@endforeach			
							總共 : <span class='label label-primary '><span ng-bind='iPrice[{{ $item->id }}]'></span>$</span>
							<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
						</div>
					</div>
				</div>
			</div>

		@endforeach
		
		@foreach ($mission->store->combos as $combo)			
			<p>
				<span>{{{ $combo->name }}}</span>
				(
				@foreach ($combo->items as $item)
					@if (count($item->opts) > 0)		
						<a href="#"><span class="glyphicon glyphicon-cog" data-toggle="modal" data-target="#myModal{{ $combo->id }}-{{ $item->id }}" 
							ng-click=''</span></a>
					@endif

					<span>{{{ $item->name }}}</span>
					
					@foreach ($item->opts as $opt)
						<span ng-show='comboItemOpt[{{ $combo->id }}][{{ $item->id }}][{{ $opt->id }}]' class='badge'>{{{ $opt->name }}}</span>
					@endforeach
				@endforeach
				)
				<span class='label label-primary '><span ng-bind='cPrice[{{ $combo->id }}]'></span>$</span>
				<a href="#"><span ng-click="orderCombo({{ $combo->id }})" class="glyphicon glyphicon-plus"></span></a>
			</p>
			@foreach ($combo->items as $item)
				<div class="modal fade optModal" id="myModal{{$combo->id}}-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"
									><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel">{{{ $combo->name }}} - {{{ $item->name }}} <span class='label label-primary '>{{{ $item->price }}}$</span></h4>
							</div>
							<div class="modal-body">
								<table class='table table-striped'>
									<tr>
										<th>名稱</th><th>加價</th>
									</tr>
									@foreach ($item->opts as $opt)
										<tr>
											<td>
												<div class="checkbox">
													<label>
														<input type='checkbox' ng-model='comboItemOpt[{{ $combo->id }}][{{ $item->id }}][{{ $opt->id }}]' 
															ng-change='changeComboPrice({{ $combo->id }}, {{ $item->id }}, {{ $opt->id }}, {{ $opt->price }})'>
														{{{ $opt->name }}}
													</label>
												</div>
											</td>
											<td><span class='label label-primary '>+{{{ $opt->price }}}$</span></td>
										</tr>
									@endforeach	
								</table>
							</div>
							<div class="modal-footer">
								{{{ $item->name }}} 
								@foreach ($item->opts as $opt)
									<span ng-show='comboItemOpt[{{ $combo->id }}][{{ $item->id }}][{{ $opt->id }}]' class='badge'>{{{ $opt->name }}}</span>
								@endforeach			
								套餐總共 : <span class='label label-primary '><span ng-bind='cPrice[{{ $combo->id }}]'></span>$</span>
								<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
							</div>
						</div>
					</div>
				</div>
			@endforeach

		@endforeach
	</div>
	
	<div ng-init='myOrder = {{ $myOrder }}'>	
		<h2>我的訂單</h2>
		<div ng-repeat='order in myOrder'>
			{{ View::make('order.userOrder', ['skipName' => true]) }}
		</div>
	</div>

	<div ng-init='otherOrders = {{ $otherOrders }}'>	
		<h2>其他訂單</h2>
		<div ng-repeat='order in otherOrders'>
			{{ View::make('order.userOrder', ['skipName' => false]) }}
		</div>
	</div>
	
	<div ng-init='statistic = {{ $statistic }}'>
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
