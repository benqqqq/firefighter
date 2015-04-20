@extends('order.layout')

@section('content')
	
	<div class="page-header">
		<h1>新增店家</h1>
	</div>
	<div>
		<h3>基本資訊</h3>
		{{ Form::open(['url' => 'order/createStore']) }}
			<div class="form-inline">
				<div class="form-group">
					<label for="name">名稱</label>
					<input type="text" class="form-control" id="name" name="name" placeholder="東方美" value="{{ Input::old('name') }}">
				</div>
				<div class="form-group">
					<label for="phone">電話</label>
					<input type="text" class="form-control" id="phone" name="phone" placeholder="2424-2424" value="{{ Input::old('phone') }}">
				</div>							
			</div>
			<p></p>
			<div class="alert alert-danger" ng-show="'{{ $errors->first('name') }}' != ''"
				><strong><span class="glyphicon glyphicon-exclamation-sign"></span> 注意 : </strong>{{ $errors->first('name') }}</div>
			
			<div class="form-group">
				<label for="address">地址</label>
				<input type="text" class="form-control" id="address" name="address" placeholder="基隆市信二路299號"  
					value="{{ Input::old('address') }}">
			</div>
			
			<div class="form-group">
				<label for="detail">備註</label>
				<textarea class="form-control" id="detail" name="detail" placeholder="送餐較慢" rows="4"
					>{{ Input::old('detail') }}</textarea>
			</div>		
			<input type="submit" class="form-control btn-primary" value="送出">
		{{ Form::close() }}
	</div>
@stop        
