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
					data-toggle="modal" data-target="#comboOptModal" id="cog-{{ $item->id }}-{{ $combo->id }}"
					ng-click="setOptModal({{ $item->id }}); setComboOptModal({{ $combo->id }})"></span></a>
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
@endforeach