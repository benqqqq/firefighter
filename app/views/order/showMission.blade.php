@extends('order.layout')

@section('head')
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.0/isotope.pkgd.js"></script>
	<script>
		$(function () {
			$('.pop').popover().click(function () {
				setTimeout(function () {
					$('.pop').popover('hide');
				}, 500);
			});
		});
		$(document).ready(function() {
			$('.menu').isotope({
				itemSelector : '.menu-item',
				layoutMode : 'fitRows'
			});
		});
	</script>
@stop

@section('content')
	<span ng-init="url = '{{ URL::to("") }}'"></span>
	<span ng-init="missionId = {{ $mission->id }}"></span>
	<span ng-init='initStore({{ $mission->store->items }}  , {{ $mission->store->combos }})'></span>	
	
	<div class="page-header">
		<h2>{{{ $mission->name }}} ({{{ $mission->store->name }}}) <small>主揪 : {{{ $mission->user->serial }}}</small>
			<a class="btn btn-danger pull" ng-show="{{ $mission->user->id }} == user.id" 
				href='{{ URL::to("order/deleteMission/" . $mission->id) }}'>刪除</a>
			<small class="pull-right">{{{ $mission->created_at }}}</small>
		</h2>	
		
	</div>

	<div>
		<p><span class='glyphicon glyphicon-phone-alt' aria-hidden="true"></span> <strong>電話 :</strong> {{{ $mission->store->phone }}}</p>
		<p><span class='glyphicon glyphicon-home' aria-hidden="true"></span> <strong>地址 :</strong> {{{ $mission->store->address }}}</p>
		<p><span class='glyphicon glyphicon-info-sign' aria-hidden="true"></span> <strong>備註 :</strong>
			<pre>{{{ $mission->store->detail }}}</pre>
		</p>
	</div>
	
	<div>
		<div class="row">
		@foreach ($mission->store->photos as $photo)
			<div class="col-md-4 btn" data-toggle="modal" data-target="#photoModal" ng-click="modalSrc = '{{{ asset($photo->src) }}}'">
				<img src="{{{ asset($photo->src) }}}" alt="storePhoto" class="img-rounded img-responsive">
			</div>
		@endforeach	
		</div>
	</div>

	
	<div class="row">
		<div class="col-md-8 col-sm-6">			
			<h2>菜單</h2>
			<p>點擊來加入訂單</p>
			
			<ul class="list-group menu">
			@foreach ($mission->store->categories as $category)
				<li class="list-group-item col-md-6 col-sm-12 col-xs-12 menu-item">
					<h4 class="list-group-item-heading btn btn-block btn-lg" ng-click="categoryToggle({{ $category->id }})" 
						ng-init="categoryIsShow[{{ $category->id }}] = false">{{ $category->name }}						
						<small><span class="glyphicon glyphicon-triangle-bottom pull-right" 
							ng-show="!categoryIsShow[{{ $category->id }}]"></span></small>
						<small><span class="glyphicon glyphicon-triangle-top pull-right" 
							ng-show="categoryIsShow[{{ $category->id }}]"></span></small>
					</h4>
					<div class="menu-item-content menu-item-content-{{ $category->id }}">
						{{ View::make('order.itemMenu', ['items' => $category->items]) }}										
					</div>
				</li>				
			@endforeach
			
				<li class="list-group-item col-md-6 col-sm-12 col-xs-12 menu-item">
					{{ View::make('order.itemMenu', ['items' => $mission->store->unCategoryItems]) }}
				</li>
				
			
				<li class="list-group-item col-md-12 col-sm-12 col-xs-12  menu-item">
					<h4 class="list-group-item-heading btn btn-block btn-lg" ng-click="categoryToggle('c')"
						ng-init="categoryIsShow['c'] = false">套餐
						<small><span class="glyphicon glyphicon-triangle-bottom pull-right" 
							ng-show="!categoryIsShow['c']"></span></small>
						<small><span class="glyphicon glyphicon-triangle-top pull-right" 
							ng-show="categoryIsShow['c']"></span></small>
					</h4>
					<div class="menu-item-content menu-item-content-c">
					@foreach ($mission->store->combos as $combo)			
						<p>
							<span class="btn btn-warning pop" ng-click="orderCombo({{ $combo->id }})" data-content="+1">
								<span>{{{ $combo->name }}}</span>
							</span>
							(
							@foreach ($combo->items as $item)
								@if (count($item->opts) > 0)									
									<a href=""><span class="glyphicon glyphicon-cog" 
										data-toggle="modal" data-target="#myModal{{ $combo->id }}-{{ $item->id }}"></span></a>
								@endif
		
								<span>{{{ $item->name }}}</span>
								
								@foreach ($item->opts as $opt)
									<span ng-show='comboItemOpt[{{ $combo->id }}][{{ $item->id }}][{{ $opt->id }}]' 
										class='badge'>{{{ $opt->name }}}</span>
								@endforeach
							@endforeach
							)
							
							<span class='label label-primary '><span ng-bind='cPrice[{{ $combo->id }}]'></span>$</span>
						</p>
						@foreach ($combo->items as $item)
							<div class="modal fade optModal" id="myModal{{$combo->id}}-{{ $item->id }}" tabindex="-1" 
								role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-sm">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"
												><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="myModalLabel"
												>{{{ $combo->name }}} - {{{ $item->name }}} <span class='label label-primary '
												>{{{ $item->price }}}$</span></h4>
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
																	<input type='checkbox' 
																		ng-model='comboItemOpt[{{ $combo->id }}][{{ $item->id }}][{{ $opt->id }}]' 
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
												<span ng-show='comboItemOpt[{{ $combo->id }}][{{ $item->id }}][{{ $opt->id }}]' 
													class='badge'>{{{ $opt->name }}}</span>
											@endforeach			
											套餐總共 : <span class='label label-primary '><span ng-bind='cPrice[{{ $combo->id }}]'></span>$</span>
											<button type="button" class="btn btn-default" data-dismiss="modal">確定</button>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					@endforeach
					</div>
				</li>
			</ul>		
		</div>
		<div ng-init='orders = {{ $orders }}'></div>
		<div ng-init='refreshOrders()'></div>
		<div class="col-md-4 col-sm-6">	
			<h2>我的訂單</h2>
			<p>點擊來移出訂單</p>
			<div ng-repeat='order in myOrder'>
				{{ View::make('order.userOrder', ['isMe' => true]) }}
			</div>
		</div>		
		<div class="col-md-4 col-sm-6">	
			<h2>其他訂單</h2>
			<div ng-repeat='order in otherOrders'>
				{{ View::make('order.userOrder', ['isMe' => false]) }}
			</div>
		</div>
	</div>
	
	<div ng-init='statistic = {{ $statistic }}'>
		<h2>統計</h2>
		<p ng-repeat='item in statistic.item'>
			<span ng-bind='item.name'></span>
			
			<span ng-bind='item.optStr' ng-show='item.optStr != " "'  class="badge"></span>
			 * 
			<span ng-bind='item.quantity'></span>
		</p>
		<p ng-repeat='combo in statistic.combo'>
			<span ng-bind='combo.name'></span>
			(
			<span ng-repeat='item in combo.items'>
				<span ng-bind='item.name'></span>
				<span ng-bind='item.optStr' ng-show='item.optStr != " "'  class="badge"></span>
			</span>
			) *
			<span ng-bind='combo.quantity'></span>
		</p>
		<hr/>
		<p>
			總共 : <span class="label label-primary"><span ng-bind='statistic.price.total'></span>$</span>
			已付 : <span class="label label-success"><span ng-bind='statistic.price.paid'></span>$</span>
			<span ng-show='statistic.price.total - statistic.price.paid > 0'
				class="label label-danger">少<span ng-bind='statistic.price.total - statistic.price.paid'></span>$</span>
			<span ng-show='statistic.price.total - statistic.price.paid < 0'
				class="label label-warning">退<span ng-bind='statistic.price.paid - statistic.price.total'></span>$</span>
		</p>
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"
						><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">{{{ $mission->store->name }}}</h4>
				</div>
				<div class="modal-body">					
					<img ng-src="{[{ modalSrc }]}" class="img-rounded img-responsive">
				</div>
			</div>
		</div>
	</div>
@stop        
