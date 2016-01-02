<?php

require_once 'common.php';

if (($user = Util::User()) === false) {
	header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Two Step Validation</title>

        <!-- Bootstrap -->
        <link href="bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
		<div class="container">
			<h1>Home</h1>
			<?php if ($user['secret'] === NULL): ?>
			<p><a class="btn btn-link" href="setup.php">Setup 2-steps validation</a></p>
			<?php endif; ?>
			<p><a class="btn btn-link" href="logout.php">Logout</a></p>
		</div>
	</body>
</html>