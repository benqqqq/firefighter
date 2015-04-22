@if (!$skipName)
	<h4><span class="glyphicon glyphicon-user"></span> <span ng-bind='order.user.serial'></span></h4>
@endif
<p ng-repeat='item in order.items'>
	<span ng-bind='item.name'></span>
	<span ng-bind='item.pivot.optStr' ng-show='item.pivot.optStr != " "' class="badge"></span>
	<span> * </span>
	<span ng-bind='item.pivot.quantity'></span> = 
	<span class="label label-primary "><span ng-bind='(item.price + item.pivot.optPrice) * item.pivot.quantity'></span>$</span>
	<a ng-click="decrementItem(order.id, item.id, item.pivot.optStr)" href="" ng-show="order.user.id == user.id"><span class="glyphicon glyphicon-minus text-danger"></span></a>
</p>
<p ng-repeat='orderCombo in order.order_combos'>
	<span ng-bind='orderCombo.combo.name'></span>
	
	(
	<span ng-repeat='item in orderCombo.items'>
		<span ng-bind='item.name'></span>
		<span ng-bind='item.pivot.optStr' ng-show='item.pivot.optStr != " "' class="badge"></span>
	</span>
	) * <span ng-bind='orderCombo.quantity'></span> = 
	<span class="label label-primary "
		><span ng-bind='(orderCombo.combo.basePrice + orderCombo.combo.price + orderCombo.optPrice) * orderCombo.quantity'></span>$</span>
	<a ng-click="decrementCombo(order.id, orderCombo.id)" href="" ng-show="order.user.id == user.id"><span class="glyphicon glyphicon-minus text-danger"></span></a>
</p>
<p>
	總共 : <span class="label label-primary "><span ng-bind='getOrderPrice(order)'></span>$</span>
	已付 : <span class="label label-success "><span ng-bind='order.paid'></span>$</span>
	
	<span class="form-inline">
		<span ng-show='order.user.id == user.id' class="form-group">
			<input type='number' ng-model='paid[order.id]' ng-show='showPaid' ng-init='showPaid = false; paid[order.id] = order.paid' class="form-control input-sm">
			<a href=""><span ng-click='showPaid = !showPaid' ng-show='!showPaid' class="glyphicon glyphicon-pencil text-success"></span></a>
		</span>
		<span ng-show='order.user.id == user.id' class="form-group">			
			<button ng-click='editPaid(order.id)' ng-show='showPaid' class="btn btn-success btn-xs">送出</button>
		</span>
	</span>
	
	
	<span ng-show='getOrderPrice(order) - order.paid > 0' class="label label-danger ">欠<span ng-bind='getOrderPrice(order) - order.paid'></span>$</span>
	<span ng-show='getOrderPrice(order) - order.paid < 0' class="label label-warning">退<span ng-bind='order.paid - getOrderPrice(order)'></span>$</span>
	
</p>
<div>
	備註 : <span ng-bind='order.remark'></span>

	<a ng-show='order.user.id == user.id' href=""><span ng-click='showRemark = !showRemark' ng-show='!showRemark' class="glyphicon glyphicon-pencil text-success"></span></a>

	<div ng-show='order.user.id == user.id' class="form-group">		
		<textarea ng-model='remark[order.id]' ng-show='showRemark' ng-init='showRemark = false; remark[order.id] = order.remark' class="form-control">
		</textarea>		
	</div>
	<div ng-show='order.user.id == user.id' class="form-group">
		<button ng-click='editRemark(order.id)' ng-show='showRemark' class="btn btn-success btn-xs">送出</button>
	</div>

</div>