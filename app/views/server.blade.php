<!doctype html>
<html>
    <head>
        {{ HTML::style('css/main.css') }}        
        {{ HTML::script('lib/jquery-1.11.1.min.js') }}
        
        <script>
        	var callback = function(res) {
				$('.result').html(res);
			}
        
			function ajax(url, data, callback) {
				data = data || {};
				$.ajax({
					url : url,
					dataType : 'html',
					data: data,
					async : true,
					type: "get",
					success : callback,
				});			
			}

        </script>
        
    </head>
</html>

<body>	
	<btn class='btn bck-red' onclick="ajax('deploy', null ,callback)">deploy</btn>
	<btn class='btn bck-green' onclick="ajax('db/refresh', null ,callback)">DB Refresh</btn>
	<btn class='btn bck-green' onclick="ajax('db/reset', null ,callback)">DB Reset</btn>
	<btn class='btn bck-green' onclick="ajax('db/migrate', null ,callback)">DB Migrate</btn>
	<btn class='btn bck-green' onclick="ajax('db/seed', null ,callback)">DB Seed</btn>
	
	<div class='result'></div>
</body>