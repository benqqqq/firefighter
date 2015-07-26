@extends('order.layout')

@section('head')
	
	<script src="/lib/bootstrap-switch.min.js"></script>
	<link rel="stylesheet" href="/lib/bootstrap-switch.min.css">

	<script>		

		$(document).ready(function() {
			$('.menu').isotope({
				itemSelector : '.menu-item',
				layoutMode : 'masonry'
			});
			$('.switchInput').bootstrapSwitch({
				onSwitchChange: function() {
					var $scope = angular.element($('.orderBody')).scope();				
					$scope.changeMissionStatus(this);
				}
			});
			
			$('#oldOrder').on('mouseenter', function() {
				$('#currentOrder').slideUp();
			});
			$('.right-nav').on('mouseleave', function() {
				var isShown = false
				$('.optModal').each(function(i, v) {
					if ($(v).data('bs.modal') && $(v).data('bs.modal').isShown == true) {						
						isShown = true;
					}
				});
				if (!isShown) {
					$('#currentOrder').slideDown();		
				}				
			});
		});
	</script>
@stop

@section('content')
<div class="col-md-10 col-sm-9 col-xs-12 showUntilReady">
	<span ng-init="url = '{{ URL::to("") }}'"></span>
	<span ng-init="missionId = {{ $mission->id }}"></span>
	<span ng-init='initStore({{ $mission->store->items }}  , {{ $mission->store->combos }})'></span>	
	
	<div class="page-header">
		<h2>{{{ $mission->name }}} ({{{ $mission->store->name }}}) 
			<small class="nowrap">主揪 : 
				<span class="glyphicon glyphicon-user"></span> {{{ $mission->user->serial }}}
			</small>
			@if (!$mission->isDelete)
				<a class="btn btn-danger pull-right" data-toggle="confirmation" title="確定要刪除嗎?"
					ng-show="{{ $mission->user->id }} == user.id" 
					href='{{ URL::to("order/deleteMission/" . $mission->id) }}'>刪除</a>
			@else
				<a class="btn btn-success pull-right" href='{{ URL::to("order/recoverMission/" . $mission->id) }}'>復原</a>
			@endif
		</h2>	
		<h4><small>{{{ $mission->created_at }}}</small></h4>		
		<input type="checkbox" name="isOpen" class="switchInput" data-size="mini" data-off-text="訂購完成" data-on-text="訂購中"
				@if(!$mission->isEnding) checked @endif>
		
		<p></p>
		<div class="form-inline" ng-show="user.id == {{ $mission->user->id }}">
			<div class="input-group">
				<span class="input-group-addon">通知</span>
				<input type="number" name="deadline" class="form-control" ng-model="endMissionTime" ng-init="endMissionTime = 1">
				<span class="input-group-addon">分鐘後</span>
				<span class="input-group-btn">
					<button class="btn btn-default" ng-click="endMission()">結束訂購</button>
				</span>
			</div>
		</div>
		
	</div>

	<address>
		<p><span class='glyphicon glyphicon-phone-alt' aria-hidden="true"></span> <strong>電話 :</strong> {{{ $mission->store->phone }}}</p>
		<p><span class='glyphicon glyphicon-home' aria-hidden="true"></span> <strong>地址 :</strong> {{{ $mission->store->address }}}</p>
		<p><span class='glyphicon glyphicon-info-sign' aria-hidden="true"></span> <strong>備註 :</strong>
			<p class="pre">{{{ $mission->store->detail }}}</p>
		</p>
	</address>
	
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
		<div class="col-md-12 col-sm-12">			
			<h2>菜單</h2>
			<p>點擊來加入訂單</p>
			
			<ul class="list-group menu">
			@foreach ($mission->store->categories as $category)
				<li class="list-group-item col-md-4 col-sm-6 col-xs-12 menu-item">
					<h4 class="list-group-item-heading btn btn-block btn-lg text-danger" ng-click="categoryToggle({{ $category->id }})" 
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

				<li class="list-group-item col-md-4 col-sm-6 col-xs-12 menu-item">
					{{ View::make('order.itemMenu', ['items' => $mission->store->unCategoryItems]) }}
				</li>
				
				@if (count($mission->store->combos) > 0)
				<li class="list-group-item col-md-8 col-sm-12 col-xs-12  menu-item">
					<h4 class="list-group-item-heading btn btn-block btn-lg text-danger" ng-click="categoryToggle('c')"
						ng-init="categoryIsShow['c'] = false">套餐
						<small><span class="glyphicon glyphicon-triangle-bottom pull-right" 
							ng-show="!categoryIsShow['c']"></span></small>
						<small><span class="glyphicon glyphicon-triangle-top pull-right" 
							ng-show="categoryIsShow['c']"></span></small>
					</h4>
					<div class="menu-item-content menu-item-content-c">
						{{ View::make('order.comboMenu', ['combos' => $mission->store->combos]) }}
					</div>
				</li>
				@endif
			</ul>		
		</div>
	</div>
	
	<div ng-init='orders = {{ $orders }}'></div>
	<div ng-init='refreshOrders()'></div>
	
	<div class="visible-xs-block" id="myOrder">
		<h2>我的訂單 <small><span class="glyphicon glyphicon-user"></span> <span ng-bind='user.serial'></span></small></h2>
		<p ng-show="user == null">請先選擇番號</p>
		<hr/>	
		<div ng-repeat='order in myOrder'>				
			<p class="text-danger">點擊來移出訂單</p>
			{{ View::make('order.userOrder', ['isMe' => true]) }}		
		</div>
		
		<div>
			<h3>之前點過 ...</h3>
			@foreach ($recentOrders as $serial => $order)
				<div ng-show="user.serial == '{{ $serial }}'">
					{{ View::make('order.itemMenu', ['items' => $order['items']]) }}
					{{ View::make('order.comboMenu', ['combos' => $order['combos']]) }}
				</div>
			@endforeach	
		</div>
	</div>
	
	<div ng-repeat='order in orders' class="col-md-4 col-sm-6 col-xs-12" 
		ng-show="order.items.length > 0 || order.order_combos.length > 0">
		{{ View::make('order.userOrder', ['isMe' => false]) }}
	</div>
			
	<div ng-init='statistic = {{ $statistic }}' class="col-md-12 col-sm-12 col-xs-12">
		<h2>統計</h2>
		<p><span class='glyphicon glyphicon-phone-alt' aria-hidden="true"></span> <strong>電話 :</strong> {{{ $mission->store->phone }}}</p>
		<p ng-repeat='item in statistic.item'>
			<span ng-bind='item.name'></span>
			
			<span ng-bind='item.optStr' ng-show='item.optStr != " "'  class="badge"></span>
			 * 
			<span ng-bind='item.quantity'></span>
			=
			<span class="label label-primary">{[{ item.totalPrice }]}$</span>

			<span ng-repeat="user in item.users">
				<span class="glyphicon glyphicon-user"></span> {[{ user.serial }]}
			</span>
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
			=
			<span class="label label-primary">{[{ combo.totalPrice }]}$</span>
			
			<span ng-repeat="user in combo.users">
				<span class="glyphicon glyphicon-user"></span> {[{ user.serial }]}
			</span>
		</p>
		<p>
			<span ng-repeat="order in orders">
				<span ng-show="order.remark != '' || order.deviation != 0">
					<span class="glyphicon glyphicon-user"></span> {[{ order.user.serial }]}
				</span>
				<span class="label label-info" ng-show="order.remark != ''">{[{ order.remark }]}</span>
				<span class="label label-primary" ng-show="order.deviation != 0"
					><span ng-show="order.deviation > 0">+</span>{[{ order.deviation }]}$</span>
			</span>			
		</p>
		<hr/>
		<p>
			總共 : <span class="label label-primary border-light"><span ng-bind='statistic.price.total'></span>$</span>
			已付 : <span class="label label-success"><span ng-bind='statistic.price.paid'></span>$</span>
			<span ng-show='statistic.price.total - statistic.price.paid > 0'
				class="label label-danger">少<span ng-bind='statistic.price.total - statistic.price.paid'></span>$</span>
			<span ng-show='statistic.price.total - statistic.price.paid < 0'
				class="label label-warning">退<span ng-bind='statistic.price.paid - statistic.price.total'></span>$</span>			
		</p>
		<hr>
		<p>
			<span ng-repeat="order in orders" ng-show="(order.items.length > 0 || order.order_combos.length > 0) && getOrderPrice(order) - order.paid != 0">
				<span class="glyphicon glyphicon-user"></span> {[{ order.user.serial }]}

				<span ng-show='getOrderPrice(order) - order.paid > 0' class="label label-danger"
					>少 {[{ getOrderPrice(order) - order.paid }]} $</span>
				<span ng-show='getOrderPrice(order) - order.paid < 0' class="label label-warning"
					>退 {[{ order.paid - getOrderPrice(order) }]} $</span>	
			</span>
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
	
	
	<div class="modal fade optModal" id="optModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"
						><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">{[{ optItem.name }]} 
						<span class='label label-primary '>{[{ optItem.price }]}$</span>
						<small class="remark" ng-show="optItem.remark != ''">({[{ optItem.remark }]})</small>
					</h4>
				</div>
				<div class="modal-body">
					<table class='table table-striped'>
						<tr>
							<th>名稱</th><th>加價</th>
						</tr>
						<tr ng-repeat="opt in optItem.opts">
							<td>
								<span class="checkbox">
									<label>
										<input type='checkbox' ng-model='itemOpt[optItem.id][opt.id]' 
											ng-change='changeItemPrice(optItem.id, opt.id, opt.price)'>
										{[{ opt.name }]}
									</label>
								</span>
							</td>										
							<td><span class='label label-primary '>{[{ opt.price }]}$</span></td>
						</tr>
					</table>
					
				</div>
				<div class="modal-footer">
					{[{ optItem.name }]} 
					<span ng-repeat="opt in optItem.opts" 
						ng-show='itemOpt[optItem.id][opt.id]' class='badge'>{[{ opt.name }]}</span>
						
					總共 : <span class='label label-primary '><span ng-bind='iPrice[optItem.id]'></span>$</span>
					<button type="button" class="btn btn-default" data-dismiss="modal">確定</button>
				</div>
			</div>
		</div>
	</div>	
	
	<div class="modal fade optModal" id="comboOptModal" tabindex="-1" 
		role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"
						><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"
						>{[{ optCombo.name  + optItem.name }]} <span class='label label-primary '
						>{[{ optItem.price }]}$</span>
						<small class="remark" ng-show="optCombo.remark != ''">({[{ optCombo.remark }]})</small>
					</h4>
				</div>
				<div class="modal-body">
					<table class='table table-striped'>
						<tr>
							<th>名稱</th><th>加價</th>
						</tr>
						<tr ng-repeat="opt in optItem.opts">
							<td>
								<div class="checkbox">
									<label>
										<input type='checkbox' 
											ng-model='comboItemOpt[optCombo.id][optItem.id][opt.id]' 
											ng-change='changeComboPrice(optCombo.id, optItem.id , opt.id, opt.price)'>
										{[{ opt.name }]}
									</label>
								</div>
							</td>
							<td><span class='label label-primary '>{[{ opt.price }]}$</span></td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					{[{ optItem.name }]} 
					<span ng-repeat="opt in optItem.opts" ng-show='comboItemOpt[optCombo.id][optItem.id][opt.id]' 
						class='badge'>{[{ opt.name }]}</span>
					套餐總共 : <span class='label label-primary '><span ng-bind='cPrice[optCombo.id]'></span>$</span>
					<button type="button" class="btn btn-default" data-dismiss="modal">確定</button>
				</div>
			</div>
		</div>
	</div>
	
</div>

<div class="navbar navbar-default right-nav col-md-2 col-sm-3 hidden-xs showUntilReady">
	<h2>我的訂單 <small><span class="glyphicon glyphicon-user"></span> <span ng-bind='user.serial'></span></small></h2>
	<p ng-show="user == null">請先選擇番號</p>
	<hr/>	
	
	<div id="currentOrder">
		<div ng-repeat='order in myOrder'>				
			<p class="text-danger">點擊來移出訂單</p>
			{{ View::make('order.userOrder', ['isMe' => true]) }}		
		</div>
	</div>
	
	<div id="oldOrder">
		<h3>之前點過 ...</h3>
		@foreach ($recentOrders as $serial => $order)
			<div ng-show="user.serial == '{{ $serial }}'">
				{{ View::make('order.itemMenu', ['items' => $order['items']]) }}
				{{ View::make('order.comboMenu', ['combos' => $order['combos']]) }}
			</div>
		@endforeach	
	</div>
</div>

@stop     

@section('nav-header')
	<li class="pull-left visible-xs-block" ng-show="user != null">
		<a href="" onclick="util.moveTo('#myOrder')"><span class="glyphicon glyphicon-shopping-cart"></span></a>		
	</li>
@stop