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

		<div class="row">		
		@foreach ($store->photos as $photo)
			<div class="col-md-4 btn" data-toggle="modal" data-target="#photoModal" ng-click="modalSrc = '{{{ asset($photo->src) }}}'">
				<img src="{{{ asset($photo->src) }}}" alt="storePhoto" class="img-rounded img-responsive">
			</div>
		@endforeach		
		</div>
		
		
		<div ng-init='items = {{ $items }}'></div>
		<div ng-init='combos = {{ $combos }}'></div>
			<h3>品項</h3>
			<!--  單點  -->
			<table class="table table-striped">
				<caption>單點</caption>
				<tr>
					<th>名稱</th><th>預設選項</th><th>所有選項</th>
				</tr>
				<tr ng-repeat="item in items">				
					<td>
						<span ng-bind="item.name"></span>
						<span class="label label-primary"><span ng-bind='item.price'></span>$</span>
					</td>
					<td>
						<span class="badge" ng-bind="item.optStr"></span>
						<span class="badge" ng-bind="item.optPrice" ng-show="debug"></span>
					</td>
					<td>
						<span ng-repeat="opt in item.opts">
							<span ng-bind="opt.name" class="badge"></span>
							<span class="label label-primary">+<span ng-bind="opt.price"></span>$</span>
						</span>
					</td>
				</tr>
			</table>
			
			<p></p>
			
			<!-- 套餐  -->
			<table class="table table-striped">
				<caption>套餐</caption>
				<tr>
					<th>名稱</th><th>組合</th>
					<tr ng-repeat="combo in combos">
						<td>
							<span ng-bind="combo.name"></span>
							<span class="label label-primary"><span ng-bind="combo.basePrice + combo.price + combo.baseOptPrice"></span>$</span>
							<span class="badge" ng-show="debug">baseP <span ng-bind="combo.basePrice"></span>$</span>
							<span class="badge" ng-show="debug">P <span ng-bind="combo.price"></span>$</span>
							<span class="badge" ng-show="debug">baseOp <span ng-bind="combo.baseOptPrice"></span>$</span>
						</td>
						<td>
							<span ng-repeat="item in combo.items">
								<span ng-bind="item.name"></span>							
								<span ng-bind="item.pivot.optStr" class="badge"></span>
								<span ng-bind="item.pivot.optPrice" class="badge" ng-show="debug"></span>
							</span>								
						</td>
					</tr>
				</tr>
			</table>
		
	</div>
	
	<!-- Modal -->
		<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"
						><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">{{{ $store->name }}}</h4>
				</div>
				<div class="modal-body">					
					<img ng-src="{[{ modalSrc }]}" class="img-rounded img-responsive">
				</div>
			</div>
		</div>
	</div>
@stop        
