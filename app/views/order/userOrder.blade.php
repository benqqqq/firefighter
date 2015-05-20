@if (!$isMe)	
	<h4 ng-show="order.user.id == user.id" class="text-info" 
		><span class="glyphicon glyphicon-user"></span> <span ng-bind='order.user.serial'></span></h4>
	<h4 ng-show="order.user.id != user.id"><span class="glyphicon glyphicon-user"></span> <span ng-bind='order.user.serial'></span></h4>
@else
	
@endif
<p ng-repeat='item in order.items'>
	@if ($isMe)
	<span class="btn btn-default btn-wrap" ng-click="decrementItem(order.id, item.id, item.pivot.optStr)">
	@else
	<span class="">
	@endif
		<span ng-bind='item.name'></span>
		<span ng-bind='item.pivot.optStr' ng-show='item.pivot.optStr != " "' class="badge"></span>
		
		* <span ng-bind='item.pivot.quantity'></span> = 
		<span class="label label-primary "><span ng-bind='(item.price + item.pivot.optPrice) * item.pivot.quantity'></span>$</span>
	</span>
		 
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
		* <span ng-bind='orderCombo.quantity'></span> = 
		<span class="label label-primary "
			><span ng-bind='(orderCombo.combo.basePrice + orderCombo.combo.price + orderCombo.optPrice) * orderCombo.quantity'></span>$</span>
	</span>
	 
</p>

<p><strong>總共 : </strong><span class="label label-primary border-light"><span ng-bind='getOrderPrice(order)'></span>$</span></p>
	
	
<p class="form-inline">
	<span class="form-group"><strong>已付 : </strong>
		@if ($isMe)
			<input type='number' ng-model='order.paid'
				class="form-control input-sm pop-input-paid" data-content="已儲存" data-placement="bottom" ng-blur="editPaid(order)"
				onclick="$(this).select()">		
		@else
			<span class="label label-success">{[{ order.paid }]}$</span>
		@endif
	</span>
		
	<span class="form-group">
		<span ng-show='getOrderPrice(order) - order.paid > 0' class="label label-danger"
			>少 {[{ getOrderPrice(order) - order.paid }]} $</span>
		<span ng-show='getOrderPrice(order) - order.paid < 0' class="label label-warning"
			>退 {[{ order.paid - getOrderPrice(order) }]} $</span>	
	</span>
</p>

<p>
	<div>
		<div class="inline-block pull-left">
			<strong>備註 : </strong>
		</div>
		@if ($isMe)
			<br/>
			<small>自行打上菜單沒有的品項或特殊需求</small>
			<div class="form-group">		
				<textarea ng-model='order.remark'
				class="form-control pop-input-remark" data-content="已儲存" data-placement="bottom" 
				ng-blur="editRemark(order)" placeholder="培根牛肉堡要加辣">
				</textarea>		
			</div>
			<div class="form-group">
				<label>調整總價格 : </label>
				<br/>
				<small>備註中品項的價錢</small>
				<span class="input-group">
					<span class="input-group-addon input-sm">±</span>
					<input class="form-control input-sm pop-input-deviation" type="number" ng-model='order.deviation' 
						data-content="已儲存" data-placement="bottom" ng-blur="editDeviation(order)" onclick="$(this).select()">
					<span class="input-group-addon input-sm">$</span>
				</span>
			</div>
		@else
			<div ng-bind='order.remark' class="inline-block pre remark-word"></div>
			<br>
			<span class="label label-primary" ng-show="order.deviation != 0">調整總價格 : 
				<span ng-show="order.deviation > 0">+</span>{[{ order.deviation }]} $</span>
		@endif		
	</div>

	

</p>
<hr/>