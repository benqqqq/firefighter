@extends('order.layout')

@section('content')
	<div>
		<section>
			{{ Form::open(array('url' => 'login')) }}
				<h1>登入</h1>
				
				<p>
					{{ $errors->first('serial') }}
					{{ $errors->first('password') }}
					{{ $errors->first('credentials') }}								
				</p>
		
				<p>
					{{ Form::text('serial', Input::old('serial'), 
						['placeholder' => '番號']) }}
				</p>
		
				<p>
					{{ Form::password('password', ['placeholder' => '密碼']) }}
				</p>
				
				<p>{{ Form::submit('確定', ['class' => 'btn bck-yellow']) }}</p>
				
				<p>
					{{ Form::checkbox('remember') }}
					<span>記住我</span>					
				</p>
				
			{{ Form::close() }}
		</section>
	</div>
@stop        
