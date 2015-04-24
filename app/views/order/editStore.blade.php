@extends('order.layout')

@section('content')
	<form method='post' id="dataForm" ng-submit="submitForm('{{ URL::to('order/editStore/' . $store->id) }}')" enctype="multipart/form-data">
		<div class="page-header">
			<h1>{{{ $store->name }}} 
				<input type="submit" class="btn btn-success pull-right" value="儲存">				
			</h1>
		</div>
		<div>
			<h3>基本資訊</h3>		
				
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
		
			<div>
				<h4>照片</h4>
				<div class="row">
				@foreach ($store->photos as $photo)
					<div class="col-md-4">
						<img src="{{{ asset($photo->src) }}}" alt="storePhoto" class="img-rounded img-responsive">
					</div>
				@endforeach
				</div>
			</div>
			
			<div class="form-group">
				<label for="photos">上傳</label>
				<input type="file" name="photos[]" id="photos" multiple>
			</div>
			
		</div>
		
		
		<div ng-init='categories = {{ $categories }}'>
			<h3>分類</h3>
			<p>
				(建議)品項過多時，加入分類來方便瀏覽
			</p>
			<table class="table table-striped">
				<tr>
					<th class="col-md-2">名稱</th>
					<th class="col-md-9">品項</th>
					<th class="col-md-1">修改/刪除</th>
				</tr>
				<tr ng-repeat="category in categories">
					<td>
						<input type="text" ng-model="category.name" class="form-control">
					</td>
					<td>
						<span ng-repeat="item in category.items" class="badge">
							{[{ item.name }]}
						</span>
					</td>
					
					<td>						 
						<span class="btn btn-primary btn-xs" data-toggle="modal" data-target="#categoryModal" 
							ng-click="setCategoryModal(category)">
							<span class="glyphicon glyphicon-pencil"></span>
						</span>

						<span class="btn btn-primary btn-xs" ng-click="remove(categories, category)">
							<span class="glyphicon glyphicon-trash"></span>
						</span>
					</td>
				</tr>
			</table>
			<input type="hidden" id="categories" name="categories">
			<! -- 新增分類  -->
			<div class="form-inline">
				<div class="form-group">
					<label>名稱</label>
					<input type="text" ng-model="newCategoryName" class="form-control" placeholder="飲料">	
				</div>
				<button class="btn btn-primary" ng-click="newCategory($event)">新增</button>	
			</div>
		</div>
		
		<div ng-init='items = {{ $items }}'>
			<h3>品項</h3>
			<!--  單點  -->
			<table class="table table-striped">
				<caption>單點</caption>
				<tr>
					<th class="col-md-3">名稱</th>
					<th class="col-md-1">預設選項</th>
					<th class="col-md-7">所有選項</th>
					<th class="col-md-1">修改/複製/刪除</th>
				</tr>
				<tr ng-repeat="item in items">				
					<td class="form-inline">
						<input type="text" ng-model="item.name" class="form-control input-sm col-md-8">											
						<span class="input-group col-md-4">
							<input type="number" ng-model="item.price" class="form-control input-sm">
							<span class="input-group-addon input-sm">$</span>
						</span>
					</td>
					<td>
						<span class="badge" ng-bind="item.optStr"></span>
						<span class="badge" ng-bind="item.optPrice" ng-show="debug"></span>
					</td>
					<td>
						<span ng-repeat="opt in item.opts">
							<span ng-bind="opt.name" class="badge"></span>
							<span class="label label-primary">+<span ng-bind="opt.price"></span>$</span>
						</span>
					</td>
					<td>						 
						<span class="btn btn-primary btn-xs" data-toggle="modal" data-target="#itemModal" 
							ng-click="setItemModal(item)">
							<span class="glyphicon glyphicon-pencil"></span>
						</span>

						<span class="btn btn-primary btn-xs" ng-click="copyItem(item)">
							<span class="glyphicon glyphicon-copy"></span>
						</span>

						<span class="btn btn-primary btn-xs" ng-click="remove(items, item); refreshCombos()">
							<span class="glyphicon glyphicon-trash"></span>
						</span>
					</td>
				</tr>
			</table>
			<input type="hidden" id="items" name="items">
			<! -- 新增單點  -->
			<div class="form-inline">
				<div class="form-group">
					<label>名稱</label>
					<input type="text" ng-model="newItemName" class="form-control" placeholder="奶茶">						
					
				</div>
				<div class="form-group">
					<label>價格</label>
					<div class="input-group">
						<input type="number" ng-model="newItemPrice" class="form-control" placeholder="20">
						<span class="input-group-addon">$</span>
					</div>
				</div>
				<button class="btn btn-primary" ng-click="newItem($event)">新增</button>				
			</div>
		</div>
		
		<p></p>
		
		<div ng-init='combos = {{ $combos }}'>
			<!-- 套餐  -->
			<table class="table table-striped">
				<caption>套餐</caption>
				<tr>
					<th class="col-md-3">名稱</th>
					<th class="col-md-8">組合</th>
					<th class="col-md-1">修改/複製/刪除</th>
				</tr>
				<tr ng-repeat="combo in combos">
					<td class="form-inline">
						<input type="text" ng-model="combo.name" class="form-control input-sm col-md-8">							
						<span class="input-group col-md-4">
							<input type="number" 
								ng-model="combo.editPrice"
							 	ng-change="refreshComboPrice(combo)" class="form-control input-sm">
							<span class="input-group-addon input-sm">$</span>
						</span>
						
						<span class="badge" ng-show="debug">baseP <span ng-bind="combo.basePrice"></span>$</span>
						<span class="badge" ng-show="debug">P <span ng-bind="combo.price"></span>$</span>
						<span class="badge" ng-show="debug">baseOp <span ng-bind="combo.baseOptPrice"></span>$</span>
					</td>
					<td>
						<span ng-repeat="item in combo.items">
							<span ng-bind="item.name"></span>							
							<span ng-bind="item.pivot.optStr" class="badge"></span>
							<span ng-bind="item.pivot.optPrice" class="badge" ng-show="debug"></span>
						</span>								
					</td>
					<td>
						<span class="btn btn-primary btn-xs" data-toggle="modal" data-target="#comboModal" 
							ng-click="setComboModal(combo)">
							<span class="glyphicon glyphicon-pencil"></span>
						</span>

						<span class="btn btn-primary btn-xs" ng-click="copyCombo(combo)">
							<span class="glyphicon glyphicon-copy"></span>
						</span>

						<span class="btn btn-primary btn-xs" ng-click="remove(combos, combo)">
							<span class="glyphicon glyphicon-trash"></span>
						</span>
					</td>
				</tr>
			</table>
			<!-- 新增套餐  -->
			<input type="hidden" id="combos" name="combos">
			<div class="form-inline">
				<div class="form-group">
					<label>名稱</label>
					<input type="text" ng-model="newComboName" class="form-control" placeholder="A套餐">						
					
				</div>
				<div class="form-group">
					<label>價格</label>
					<div class="input-group">
						<input type="number" ng-model="newComboPrice" class="form-control" placeholder="60">
						<span class="input-group-addon">$</span>
					</div>
				</div>
				<button class="btn btn-primary" ng-click="newCombo($event)">新增</button>				
			</div>			
		</div>
	</form>
	
	
	<!-- Modal -->
	<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"
						><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">修改</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>名稱</label>
						<input class="form-control" type="text" ng-model="editName">
					</div>
					<div class="form-group">
						<label>價格</label>
						<div class="input-group">
							<input class="form-control" type="number" ng-model="editPrice">
							<span class="input-group-addon">$</span>
						</div>
					</div>
					<table class="table table-striped">
						<tr>
							<th class="col-md-2">預設</th>
							<th class="col-md-4">名稱</th>
							<th class="col-md-4">價格</th>
							<th class="col-md-2">刪除</th>
						</tr>
						<tr ng-repeat="opt in editOpts">
							<td>
								<input ng-model="defaultOpt[$index]" ng-init="defaultOpt[$index] = isInStr(editOptStr, opt.name)"
								 type="checkbox">
							</td>
							<td>
								<input ng-model="opt.name" class="form-control" type="text">
							</td>
							<td>
								<div class="input-group">
									<span class="input-group-addon">+</span>
									<input ng-model="opt.price" class="form-control" type="number">
									<span class="input-group-addon">$</span>
								</div>
							</td>
							<td>
								<span class="btn btn-primary" ng-click="remove(editOpts, opt)">
									<span class="glyphicon glyphicon-trash"></span>
								</span>
							</td>
						</tr>					
						<tr>
							<td></td>
							<td>
								<input type="text" ng-model="newOptName" class="form-control" placeholder="加蛋">							
							</td>
							<td>
								<div class="input-group">
									<span class="input-group-addon">+</span>
									<input type="number" ng-model="newOptPrice" class="form-control" placeholder="5">
									<span class="input-group-addon">$</span>
								</div>
							</td>
							<td>
								<button class="btn btn-primary" ng-click="newOpt($event)">新增</button>				
							</td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" ng-click="doSetModal()">確定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="comboModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"
						><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">修改</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>名稱</label>
						<input class="form-control" type="text" ng-model="editName">
					</div>
					<div class="form-group">
						<label>價格</label>
						<div class="input-group">
							<input class="form-control" type="number" ng-model="editPrice">
							<span class="input-group-addon">$</span>
						</div>
					</div>
					<table class="table table-striped">
						<tr>
							<th class="col-md-4">名稱</th>
							<th class="col-md-6">選項(勾選預設)</th>
							<th class="col-md-2">刪除</th>
						</tr>
						<tr ng-repeat="item in editItems">
							<td>
								<span ng-bind="item.name"></span>
							</td>
							<td>
								<span ng-repeat="opt in item.opts">
									<span ng-bind="opt.name"></span>
									<input ng-model="defaultComboOpt[$parent.$index][$index]" 
										ng-init="defaultComboOpt[$parent.$index][$index] = isInStr(item.pivot.optStr, opt.name)" 
										type="checkbox">
								</span>
							</td>
							<td>
								<span class="btn btn-primary" ng-click="remove(editItems, item)">
									<span class="glyphicon glyphicon-trash"></span>
								</span>
							</td>
						</tr>					
						<tr>
							<td>
								<select class="form-control" ng-model="newComboItemObj" ng-options="item.name for item in items">
									<option value="">-- 請選擇 --</option>
								</select>
							</td>								
							<td>
								<button class="btn btn-primary" ng-click="newComboItem($event)">新增</button>				
							</td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" ng-click="doSetComboModal()">確定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"
						><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">修改</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>名稱</label>
						<input class="form-control" type="text" ng-model="editName">
					</div>
					<table class="table table-striped">
						<tr>
							<th class="col-md-4">品項名稱</th>
							<th class="col-md-2">刪除</th>
						</tr>
						<tr ng-repeat="item in editItems">
							<td>
								<span ng-bind="item.name"></span>
							</td>
							<td>
								<span class="btn btn-primary" ng-click="remove(editItems, item)">
									<span class="glyphicon glyphicon-trash"></span>
								</span>
							</td>
						</tr>					
						<tr>
							<td>
								<select class="form-control" ng-model="newComboItemObj" ng-options="item.name for item in items">
									<option value="">-- 請選擇 --</option>
								</select>
							</td>								
							<td>
								<button class="btn btn-primary" ng-click="newComboItem($event)">新增</button>				
							</td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" ng-click="doSetCategoryModal()">確定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
@stop        
