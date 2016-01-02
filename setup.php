<?php

require_once 'common.php';

if (($user = Util::User()) === false) {
	header('Location: index.php');
}

if ($user['secret'] !== null) {
	header('Location: home.php');	
}

$error = null;
$secret = null;
$success = null;

if ($_POST['postback'] == 1) {
	$secret = $_POST['secret'];
	$otp = Util::GenerateOTP($secret);
	if ($_POST['code'] == $otp) {
		$db->update('users', ['secret' => $secret], ['username' => $user['username']]);
		$success = "2-Step validation done";
	} else {
		$error = "Invalid code";
	}
} else {
	$secret = Util::GenerateRandomSecret();
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
			<h1>Setup 2-step Validation</h1>
			<?php if ($success !== null): ?>
			<div class="alert alert-success" role="alert"><?= $success ?></div>
			<?php else: ?>
			<p>Scan this QR with your phone, and enter the generated code on the text input below:</p>
			<p><img class="img-responsive" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=200x200&chld=M|0&cht=qr&chl=otpauth://totp/user@host.com%3Fsecret%3D<?= $secret ?>"></p>
			<p>
			<form method="post" action="setup.php">
				<div class="form-group">
					<input class="form-control" type="number" name="code" placeholder="Six digits code" />
				</div>
				<?php if ($error): ?>
				<div class="form-group">
					<div class="alert alert-danger" role="alert"><?= $error ?></div>
				</div>
				<?php endif; ?>
				<input type="hidden" name="secret" value="<?= $secret ?>" />
				<input type="hidden" name="postback" value="1" />
				<input class="btn btn-primary" type="submit" value="Confirm" />
			</form>
			</p>
			<?php endif; ?>
			<p><a class="btn btn-link" href="home.php">Home</a></p>
		</div>
	</body>
</html>