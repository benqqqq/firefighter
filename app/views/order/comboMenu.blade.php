@foreach ($combos as $combo)			
	<p class="menuRow">
		<span class="btn pop-c-{{ $combo->id }}" 
			ng-click="orderCombo({{ $combo->id }}, '.pop-c-{{ $combo->id }}')" 
			 data-html="true">
			<span class="{{ $combo->hotColor() }}">{{{ $combo->name }}}</span>
		</span>
		
		(
		@foreach ($combo->items as $item)
			<span class="nowrap">
			@if (count($item->opts) > 0)									
				<a href=""><span class="glyphicon glyphicon-cog" 
					data-toggle="modal" data-target="#myModal{{ $combo->id }}-{{ $item->id }}"></span></a>
			@endif

			<span class='{{ count($item->opts) > 0 ? "order-btn" : "" }}' data-item-id={{ $item->id }}
				>{{{ $item->name }}}</span>
			
			@foreach ($item->opts as $opt)
				<span ng-show='comboItemOpt[{{ $combo->id }}][{{ $item->id }}][{{ $opt->id }}]' 
					class='badge'>{{{ $opt->name }}}</span>
			@endforeach
			</span>
		@endforeach
		)
		
		<span class='label label-primary '><span ng-bind='cPrice[{{ $combo->id }}]'></span>$</span>
		@if ($combo->remark != '')
			<small class="remark nowrap">({{ $combo->remark }})</small>
		@endif
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
							>{{{ $item->price }}}$</span>
							@if ($combo->remark != '')
								<small class="remark">({{ $combo->remark }})</small>
							@endif
						</h4>
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
									<td><span class='label label-primary '>{{{ $opt->price }}}$</span></td>
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