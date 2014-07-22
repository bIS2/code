<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<?php $input = Session::get('input'); ?>
		<h2>New feedback to <a href="<?php echo $input['url']; ?>"><?php echo $input['url']; ?></a></h2>
		<div style="font-color: peru; font-size: 14px;">
			User: <strong><?php echo User::find($input['user_id'])->username; ?><br></strong>
			Browser: <strong><?php echo $input['client']; ?><br></strong>
			URL: <strong><?php echo $input['url']; ?><br><br></strong>
			Feedback: <strong><?php echo $input['content']; ?><br></strong>
		</div>
	</body>
</html>