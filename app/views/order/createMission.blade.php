@extends('order.layout')


@section('head')
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.0/isotope.pkgd.js"></script>
	<script>		

		$(document).ready(function() {
			$('.menu').isotope({
				itemSelector : '.menu-item',
				layoutMode : 'masonry'
			});
		});
	</script>
@stop


@section('content')
	
	<div class="page-header">
		<h1>新增訂購</h1>
	</div>
	<form method="post" action="{{ URL::to('order/createMission/' . $store->id) }}" enctype="multipart/form-data">			
		<div class="row">
			<div class="col-md-6 col-sm-8 col-xs-9">
				<input type="text" name="name" class="form-control" value="訂早餐囉~~">
			</div>
			<div class="col-md-1 col-sm-1 col-xs-1">
				<input class="btn btn-success" type="submit" value="確定">
			</div>
		</div>
		<input name="userId" ng-value="user.id" type="hidden">
	</form>
	<h4><small>* 取一個這次訂餐的名字，就可以開始訂囉 !</small></h4>
	
	<hr>
	<div>
		<h3>{{{ $store->name }}} 
			@if (User::isManager())
				<a href="{{ URL::to('order/deleteStore/' . $store->id) }}" class="btn btn-danger pull-right"
					data-toggle="confirmation" title="確定要刪除嗎?">刪除</a>
				<a class="btn btn-primary pull-right" 
					href="{{ URL::to('order/editStore/' . $store->id) }}"><span class="glyphicon glyphicon-edit"></span> 修改</a>
			@endif
				
		</h3>	
		
		<div>
			<p><span class='glyphicon glyphicon-phone-alt' aria-hidden="true"></span> <strong>電話 :</strong> {{{ $store->phone }}}</p>
			<p><span class='glyphicon glyphicon-home' aria-hidden="true"></span> <strong>地址 :</strong> {{{ $store->address }}}</p>
			<p><span class='glyphicon glyphicon-info-sign' aria-hidden="true"></span> <strong>備註 :</strong> 
				<p class="pre">{{{ $store->detail }}}</p>
			</p>
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
		<div ng-init='categories = {{ $categories }}'></div>
		<div ng-init='unCategoryItems = {{ $unCategoryItems }}'></div>
		<div ng-init='initStore(items, combos)'></div>
		
		<ul class="list-group menu">		
			<li class="list-group-item col-md-6 col-sm-12 col-xs-12 menu-item" ng-repeat="category in categories">
				<h4 class="list-group-item-heading btn btn-block btn-lg" >{[{ category.name }]}</h4>
				<div>
					<p ng-repeat="item in category.items">
						<span class="btn btn-warning">
							<span>{[{ item.name }]}</span>					
							<span ng-repeat="opt in item.opts" 
								ng-show='itemOpt[item.id][opt.id]' class='badge'>{[{ opt.name }]}</span>								
						</span>				
						<span class='label label-primary'>{[{ item.price }]}$</span>						
						<small ng-show="item.remark != ''" class="remark">({[{ item.remark }]})</small>
					</p>
				</div>
			</li>				
			
			<li class="list-group-item col-md-6 col-sm-12 col-xs-12 menu-item">
				<p ng-repeat="item in unCategoryItems">
					<span class="btn btn-warning">
						<span>{[{ item.name }]}</span>					
						<span ng-repeat="opt in item.opts" 
							ng-show='itemOpt[item.id][opt.id]' class='badge'>{[{ opt.name }]}</span>								
					</span>				
					<span class='label label-primary'>{[{ item.price }]}$</span>						
					<small ng-show="item.remark != ''" class="remark">({[{ item.remark }]})</small>
				</p>
			</li>
			
			<li class="list-group-item col-md-12 col-sm-12 col-xs-12  menu-item">
					<h4 class="list-group-item-heading btn btn-block btn-lg">套餐</h4>
					<div>
						<p ng-repeat="combo in combos">
							<span class="btn btn-warning">
								<span>{[{ combo.name }]}</span>
							</span>
							(
							<span ng-repeat="item in combo.items">		
								<span>{[{ item.name }]}</span>
								<span ng-repeat="opt in item.opts"
									ng-show='comboItemOpt[combo.id][item.id][opt.id]' 
									class='badge'>{[{ opt.name }]}</span>
							</span>
							)
							
							<span class='label label-primary '><span ng-bind='cPrice[combo.id]'></span>$</span>
							<small ng-show="combo.remark != ''" class="remark">({[{ combo.remark }]})</small>
						</p>
					</div>
			</li>
			
		</ul>
		
<!-- 		<h3>品項</h3> -->
		<!--  單點  -->
		<!--
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
-->
		
		<!-- 套餐  -->
		<!--
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
-->
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
