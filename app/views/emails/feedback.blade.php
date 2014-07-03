<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>New feedback in <a href="<?php echo Session::get('url'); ?>"><?php echo Session::get('url'); ?></a></h2>

		<div>
			<?php var_dump(Session::get('input')); ?>
		</div>
	</body>
</html>