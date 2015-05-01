@if (!$isMe)
	<h4><span class="glyphicon glyphicon-user"></span> <span ng-bind='order.user.serial'></span></h4>
@else
	<h2>我的訂單 <small><span class="glyphicon glyphicon-user"></span> <span ng-bind='order.user.serial'></span></small></h2>
	<p>點擊來移出訂單</p>
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

<p><strong>總共 : </strong><span class="label label-primary "><span ng-bind='getOrderPrice(order)'></span>$</span></p>
	
	
<p class="form-inline">
	<span class="form-group"><strong>已付 : </strong><span class="label label-success" ng-show="order.user.id != user.id">{[{ order.paid }]}$</span></span>

	<span ng-show='order.user.id == user.id' class="form-group">
		<input type='number' ng-model='paid[order.id]' ng-init='paid[order.id] = order.paid' 
			class="form-control input-sm pop-input-paid" data-content="已儲存" data-placement="bottom" ng-blur="editPaid(order.id)">
	</span>
	
	<span class="form-group">
		<span ng-show='getOrderPrice(order) - order.paid > 0' class="label label-danger"
			>少 {[{ getOrderPrice(order) - order.paid }]} $</span>
		<span ng-show='getOrderPrice(order) - order.paid < 0' class="label label-warning"
			>退 {[{ order.paid - getOrderPrice(order) }]} $</span>	
	</span>
</p>

<p>
	<span><strong>備註 : </strong>
		<small ng-show='order.user.id == user.id'>自行打上菜單沒有的品項或特殊需求</small>
		<span ng-show='order.user.id != user.id' ng-bind='order.remark' class="pre"></span>
	</span>

	<div ng-show='order.user.id == user.id' class="form-group">		
		<textarea ng-model='remark[order.id]' ng-init='showRemark = false; remark[order.id] = order.remark' class="form-control pop-input-remark" data-content="已儲存" data-placement="bottom" ng-blur="editRemark(order.id)" placeholder="培根牛肉堡要加辣">
		</textarea>		
	</div>

</p>
<hr/>