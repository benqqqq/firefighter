@foreach ($items as $item)
<p class='menuRow {{ count($item->opts) > 0 ? "order-btn" : "" }}' data-item-id={{ $item->id }}>
				
	@if (count($item->opts) > 0)		
		<a href=""><span class="glyphicon glyphicon-cog" data-toggle="modal" data-target="#optModal" id="cog-{{ $item->id }}"
			ng-click="setOptModal({{ $item->id }})"></span></a>
	@else
		<span class="glyphicon glyphicon-cog hidden-cog"></span>
	@endif
	
	<span class="btn pop-i-{{ $item->id }}"
		ng-click="orderItem({{ $item->id }}, '.pop-i-{{ $item->id }}');" data-html="true">
		<span class="{{ $item->hotColor() }}">{{{ $item->name }}}</span>
		
		@foreach ($item->opts as $opt)
			<span ng-show='itemOpt[{{ $item->id }}][{{ $opt->id }}]' class='badge'>{{{ $opt->name }}}</span>
		@endforeach			
	</span>			
	
	<span class='label label-primary'><span ng-bind='iPrice[{{ $item->id }}]'></span>$</span></span>
	
	
	@if ($item->remark != '')
		<small class="remark nowrap">({{ $item->remark }})</small>
	@endif
</p>


@endforeach