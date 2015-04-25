@if (!$isMe)
	<h4><span class="glyphicon glyphicon-user"></span> <span ng-bind='order.user.serial'></span></h4>
@endif
<p ng-repeat='item in order.items'>
	@if ($isMe)
	<span class="btn btn-default btn-wrap" ng-click="decrementItem(order.id, item.id, item.pivot.optStr)">
	@else
	<span class="">
	@endif
		<span ng-bind='item.name'></span>
		<span ng-bind='item.pivot.optStr' ng-show='item.pivot.optStr != " "' class="badge"></span>
	</span>
		<span> * </span>
		<span ng-bind='item.pivot.quantity'></span> = 
		<span class="label label-primary "><span ng-bind='(item.price + item.pivot.optPrice) * item.pivot.quantity'></span>$</span>
</p>
<p ng-repeat='orderCombo in order.order_combos'>
	@if ($isMe)
	<span class="btn btn-default btn-wrap" ng-click="decrementCombo(order.id, orderCombo.id)">
	@else
	<span class="">
	@endif
		<span ng-bind='orderCombo.combo.name'></span>	
		(
		<span ng-repeat='item in orderCombo.items'>
			<span ng-bind='item.name'></span>
			<span ng-bind='item.pivot.optStr' ng-show='item.pivot.optStr != " "' class="badge"></span>
		</span>
		)
	</span>
	<span> * </span> 
	<span ng-bind='orderCombo.quantity'></span> = 
	<span class="label label-primary "
		><span ng-bind='(orderCombo.combo.basePrice + orderCombo.combo.price + orderCombo.optPrice) * orderCombo.quantity'></span>$</span>
</p>
<hr/>

<p>	總共 : <span class="label label-primary "><span ng-bind='getOrderPrice(order)'></span>$</span></p>
	
	
<p class="form-inline">
	<span>已付 : <span class="label label-success" ng-show="order.user.id != user.id">{[{ order.paid }]}$</span></span>

	<span ng-show='order.user.id == user.id' class="form-group col-md-1">
		<input type='number' ng-model='paid[order.id]' ng-init='paid[order.id] = order.paid' 
			class="form-control input-sm">
	</span>
	<span ng-show='order.user.id == user.id' class="form-group">			
		<button ng-click='editPaid(order.id)' class="btn btn-success btn-xs">送出</button>
	</span>
	
	<span ng-show='getOrderPrice(order) - order.paid > 0' class="label label-danger"
		>欠 {[{ getOrderPrice(order) - order.paid }]} $</span>
	<span ng-show='getOrderPrice(order) - order.paid < 0' class="label label-warning"
		>退 {[{ order.paid - getOrderPrice(order)'></span>$</span>	
</p>
	
	
<div>
	備註 : <span ng-bind='order.remark'></span>

	<button class="btn btn-success btn-xs" ng-show='order.user.id == user.id && !showRemark' ng-click='showRemark = !showRemark' 
		><span class="glyphicon glyphicon-pencil"></span> 修改
	</button>

	<div ng-show='order.user.id == user.id' class="form-group">		
		<textarea ng-model='remark[order.id]' ng-show='showRemark' ng-init='showRemark = false; remark[order.id] = order.remark' class="form-control">
		</textarea>		
	</div>
	<div ng-show='order.user.id == user.id' class="form-group">
		<button ng-click='editRemark(order.id)' ng-show='showRemark' class="btn btn-success btn-xs">送出</button>
	</div>

</div>