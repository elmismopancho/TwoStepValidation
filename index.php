<?php
require_once 'common.php';

$error = null;
if ($_POST['postback'] == 1) {
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

	if ($_POST['method'] == 'Sign Up') {
		try {
			Util::ValidUsername($username);
			Util::CreateUser($username, $password);
			$_SESSION['username'] = $username;
			header('Location: home.php');
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	} else if ($_POST['method'] == 'Login') {
		try {
			$hasTwoSteps = Util::Login($username, $password);
			if (!$hasTwoSteps) {
				$_SESSION['username'] = $username;
				header('Location: home.php');
			} else {
				$_SESSION['twostep_username'] = $username;
				header('Location: code.php');
			}
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
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
			<h1>Two step validation demo</h1>
			<form method="post" action="index.php" autocomplete="off">
				<div class="form-group">
					<label>Username:</label>
					<input class="form-control" type="text" name="username" />
				</div>
				<div class="form-group">
					<label>Password:</label>
					<input class="form-control" type="password" name="password"/>
				</div>
				<?php if ($error): ?>
				<div class="form-group">
					<div class="alert alert-danger" role="alert"><?= $error ?></div>
				</div>
				<?php endif; ?>
				<input class="btn btn-primary" type="submit" name="method" value="Login" />
				<input class="btn btn-default" type="submit" name="method" value="Sign Up" />
				<input type="hidden" name="postback" value="1" />
			</form>
		</div>
	</body>
</html>