@foreach ($items as $item)
<p>
	@if (count($item->opts) > 0)		
		<a href=""><span class="glyphicon glyphicon-cog" data-toggle="modal" data-target="#myModal{{ $item->id }}" 
			ng-click=''</span></a>
	@endif
	<span class="btn btn-warning pop-i-{{ $item->id }}" 
		ng-click="orderItem({{ $item->id }}, '.pop-i-{{ $item->id }}');" title="我的訂單" data-html="true">
		<span>{{{ $item->name }}}</span>

		@foreach ($item->opts as $opt)
			<span ng-show='itemOpt[{{ $item->id }}][{{ $opt->id }}]' class='badge'>{{{ $opt->name }}}</span>
		@endforeach
		
	</span>				
	<span class='label label-primary'><span ng-bind='iPrice[{{ $item->id }}]'></span>$</span>
	
</p>

<div class="modal fade optModal" id="myModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"
					><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">{{{ $item->name }}} 
					<span class='label label-primary '>{{{ $item->price }}}$</span></h4>
			</div>
			<div class="modal-body">
				<table class='table table-striped'>
					<tr>
						<th>名稱</th><th>加價</th>
					</tr>
					@foreach ($item->opts as $opt)
						<tr>
							<td>
								<span class="checkbox">
									<label>
										<input type='checkbox' ng-model='itemOpt[{{ $item->id }}][{{ $opt->id }}]' 
											ng-change='changeItemPrice({{ $item->id }}, {{ $opt->id }}, {{ $opt->price }})'>
										{{{ $opt->name }}}
									</label>
								</span>
							</td>										
							<td><span class='label label-primary '>+{{{ $opt->price }}}$</span></td>
						</tr>
					@endforeach	
				</table>
				
			</div>
			<div class="modal-footer">
				{{{ $item->name }}} 
				@foreach ($item->opts as $opt)
					<span ng-show='itemOpt[{{ $item->id }}][{{ $opt->id }}]' class='badge'>{{{ $opt->name }}}</span>
				@endforeach			
				總共 : <span class='label label-primary '><span ng-bind='iPrice[{{ $item->id }}]'></span>$</span>
				<button type="button" class="btn btn-default" data-dismiss="modal">確定</button>
			</div>
		</div>
	</div>
</div>	
@endforeach