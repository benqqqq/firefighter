<p ng-bind='order.user.serial'></p>
<p ng-repeat='item in order.items'>
	<span ng-bind='item.name'></span>
	(<span ng-bind='item.pivot.optStr'></span>)
	<span> * </span>
	<span ng-bind='item.pivot.quantity'></span> = 
	<span ng-bind='(item.price + item.pivot.optPrice) * item.pivot.quantity'></span>$
	<span ng-click="decrementItem(order.id, item.id, item.pivot.optStr)">刪</span>
</p>
<p ng-repeat='orderCombo in order.order_combos'>
	<span ng-bind='orderCombo.combo.name'></span>
	
	[
	<span ng-repeat='item in orderCombo.items'>
		<span ng-bind='item.name'></span>
		(<span ng-bind='item.pivot.optStr'></span>)
	</span>
	] * <span ng-bind='orderCombo.quantity'></span> = 
	<span ng-bind='(orderCombo.combo.price + orderCombo.optPrice) * orderCombo.quantity'></span>$
	<span ng-click="decrementCombo(order.id, orderCombo.id)">刪</span>
</p>
<p>
	總共 : <span ng-bind='getOrderPrice(order)'></span>$				
	已付 : <span ng-bind='order.paid'></span>$
	@if (Auth::check())
	<div ng-show='order.user.id == {{ Auth::id() }}'>
		<input type='number' ng-model='paid[order.id]' ng-show='showPaid' ng-init='showPaid = false; paid[order.id] = order.paid'>
		<span ng-click='showPaid = !showPaid' ng-show='!showPaid'>修改</span>
		<span ng-click='editPaid(order.id)' ng-show='showPaid'>送出</span>
	</div>
	@endif
	<span ng-show='getOrderPrice(order) - order.paid > 0'>- 欠<span ng-bind='getOrderPrice(order) - order.paid'></span>$</span>
	<span ng-show='getOrderPrice(order) - order.paid < 0'>- 退<span ng-bind='order.paid - getOrderPrice(order)'></span>$</span>
</p>
<div>
	備註 : <span ng-bind='order.remark'></span>
	@if (Auth::check())
	<div ng-show='order.user.id == {{ Auth::id() }}'>
		<textarea ng-model='remark[order.id]' ng-show='showRemark' ng-init='showRemark = false; remark[order.id] = order.remark'>
		</textarea>
		<span ng-click='showRemark = !showRemark' ng-show='!showRemark'>修改</span>
		<span ng-click='editRemark(order.id)' ng-show='showRemark'>送出</span>
	</div>
	@endif
</div>