<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<?php $input = Session::get('input'); ?>
		<h2>New feedback to <a href="<?php echo $input['url']; ?>"><?php echo echo $input['url']; ?></a></h2>
		<div style="font-color: peru; font-size: 14px;">
			User: <strong><?php User::find($input['user_id'])->username; ?><br></strong>
			Browser: <strong><?php $input['client']; ?><br></strong>
			URL: <strong><?php $input['user_id']; ?><br><br></strong>
			Feedback: <strong><?php $input['user_id']; ?><br></strong>
		</div>
	</body>
</html>