<html>
    <head>
    	<title>預設值調整 - 勤務分配表</title>
    	
    	{{ HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
    	
		<!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		{{ HTML::script('lib/bootstrap-confirmation.js') }}
    </head>
    
    <body>
    	<div class="container">
	    	<h1>預設值調整</h1>
	    	<form action="/dayWork/default/edit" method="post">
	    		<div class="text-right form-inline">
	    			@if ($errors->any())
	    				<span class="text-danger">{{ $errors->first() }}</span>
	    			@endif
	    			
	    			<div class="form-group">
	    				<label>密碼 : </label>
		    			<input type='password' name="password" class="form-control">
	    			</div>
		    		<input type="submit" value="儲存" class="btn btn-success">
	    		</div>
	    		<hr>
		    	<table class="table table-striped">
		    		<tr>
			    		<th class="col-md-4">名稱</th><th class="col-md-8">內容</th>
		    		</tr>
					@foreach($defaults as $default)
						<tr>
							<td>
								{{ $default->name }}
							<td>
								<textarea name="{{ $default->code }}"
									class="form-control" rows="{{ $default->rows }}"
									>{{ $default->content }}</textarea>
							</td>
						</tr>
					@endforeach
		    	</table>
	    	</form>
    	</div>
    </body>
    
</html>