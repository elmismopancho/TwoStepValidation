<?php

require_once 'common.php';

if ($_SESSION['twostep_username'] == null) {
	header('Location: index.php');
}

$secret = $db->get('users', 'secret', ['username' => $_SESSION['twostep_username']]);
if ($secret === false) {
	header('Location: index.php');
}
if ($secret === null) {
	header('Location: home.php');	
}

$error = null;
if ($_POST['postback'] == 1) {
	$otp = Util::GenerateOTP($secret);
	if ($otp == $_POST['code']) {
		$_SESSION['username'] = $_SESSION['twostep_username'];
		header('Location: home.php');
	} else {
		$error = "Invalid code";
	}
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
		<h1>Enter the generated code</h1>
		<form method="post" action="code.php">
			<div class="form-group">
				<input class="form-control" type="number" name="code" placeholder="Six digits code" />
			</div>
			<?php if ($error): ?>
			<div class="form-group">
				<div class="alert alert-danger" role="alert"><?= $error ?></div>
			</div>
			<?php endif; ?>
			<input type="hidden" name="postback" value="1" />
			<input class="btn btn-primary" type="submit" value="Confirm" />
		</form>
	</body>
</html>