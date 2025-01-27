<?php require_once("includes/connection.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"> 
	<link href="css/style.css" media="screen" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800'rel='stylesheet' type='text/css'>
	<title>Бібліотека</title>
</head>
<body>
	<div class="header">
		<div class="title">
			<a href="index.php">Бібліотека</a>
		</div>
		<?php 
		session_start();
		if(isset($_SESSION["session_username"])){
			$login = $_SESSION["session_username"];
			$sql_id = "SELECT user_id, access_id FROM users WHERE login = '$login'";

			if (!$result_id = mysqli_query($con, $sql_id)) {
				echo "Вибачте, виникла проблема у роботі сайту.";
				exit;
			}else $account_id = mysqli_fetch_assoc($result_id);
			?>
			<div class="account-header">
				<a href="account.php?id=<?php echo $account_id['user_id'] ?>"><?php echo $login;?></a>
				<a href="logout.php">Вийти</a>
			</div>
			<?php
		}else{?>
			<div class="account-header">
				<div><a href="register.php">Зареєструватися</a></div>
				<div><a href="login.php">Вхід</a></div>
				<?php $account_id['access_id'] = 0; ?>
			</div>
		<?php } ?>
	</div>