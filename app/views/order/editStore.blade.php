@extends('order.layout')

@section('content')
	<form method='post' id="dataForm" ng-submit="submitForm('{{ URL::to('order/editStore/' . $store->id) }}')">
		<div class="page-header">
			<h1>{{{ $store->name }}} <input type="submit" class="btn btn-success right" value="儲存"></h1>
		</div>
		<div>
			<h3>基本資訊</h3>		
			<div>
				
					<div class="form-inline">
						<div class="form-group">
							<label for="name">名稱</label>
							<input type="text" class="form-control" id="name" name="name" value="{{ $store->name }}">
						</div>
						<div class="form-group">
							<label for="phone">電話</label>
							<input type="text" class="form-control" id="phone" name="phone" value="{{ $store->phone }}">
						</div>							
					</div>
					<p></p>
					<div class="alert alert-danger" ng-show="'{{ $errors->first('name') }}' != ''"
						><strong><span class="glyphicon glyphicon-exclamation-sign"></span> 注意 : </strong>{{ $errors->first('name') }}</div>
					
					<div class="form-group">
						<label for="address">地址</label>
						<input type="text" class="form-control" id="address" name="address" value="{{ $store->address }}">
					</div>
					
					<div class="form-group">
						<label for="detail">備註</label>
						<textarea class="form-control" id="detail" name="detail" rows="4"
							>{{ $store->detail }}</textarea>
					</div>		
				
			</div>
		</div>
		<div ng-init='items = {{ $items }}'></div>
		<div ng-init='combos = {{ $combos }}'></div>
			<h3>品項</h3>
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
					</td>
					<td>
						<span ng-repeat="opt in item.opts">
							<span ng-bind="opt.name" class="badge"></span>
							<span class="label label-primary">+<span ng-bind="opt.price"></span>$</span>
						</span>
					</td>
				</tr>
			</table>
			<input type="hidden" id="items" name="items">
			
			<table class="table table-striped">
				<caption>套餐</caption>
				<tr>
					<th>名稱</th><th>組合</th>
					<tr ng-repeat="combo in combos">
						<td>
							<span ng-bind="combo.name"></span>
							<span class="label label-primary"><span ng-bind="combo.basePrice + combo.price"></span>$</span>
						</td>
						<td>
							<span ng-repeat="item in combo.items">
								<span ng-bind="item.name"></span>							
								<span ng-bind="item.pivot.optStr" class="badge"></span>
							</span>								
						</td>
					</tr>
				</tr>
			</table>
		</div>
	</form>
@stop        
