<?php include("includes/header.php"); ?>

<div class="container mlogin">
	<div id="login">
		<h1>Вхід</h1>
		<form action="" id="loginform" method="post"name="loginform">
			<p><input placeholder = "Логін"class="input" id="username" name="username"size="20"
				type="text" value=""></p>
				<p><input placeholder = "Пароль" class="input" id="password" name="password"size="20"
					type="password" value=""></p> 
					<p class="submit"><input class="button" name="login"type= "submit" value="Увійти"></p>
					<p class="regtext">Ще не зареєстровані?<a class="link" href= "register.php">Реєстрація</a>!</p>
					<a class="link" href= "index.php">Перейти на головну</a>
				</form>
			</div>
		</div>

		<?php

		if(isset($_POST["login"])){
			$username = isset($_POST['username']) ?  filter_var($_POST['username'], FILTER_SANITIZE_STRING) : null; 
			if (empty($username)) {
				echo "Неправильний формат username!";
				$username = null;
			}
			$password = isset($_POST['password']) ?  filter_var($_POST['password'], FILTER_SANITIZE_STRING) : null; 
			if (empty($password)) {
				echo "Неправильний формат password!";
				$password = null;
			}
			if(!empty($_POST['username']) && !empty($_POST['password'])) {

				$query =mysqli_query($con, "SELECT * FROM users WHERE login='".$username."' AND password='".$password."'");
				$numrows=mysqli_num_rows($query);
				if($numrows!=0)
				{
					while($row=mysqli_fetch_assoc($query))
					{
						$dbusername=$row['login'];
						$dbpassword=$row['password'];
					}
					if($username == $dbusername && $password == $dbpassword)
					{
						$_SESSION['session_username']=$username;	
						$_SESSION['status'] = "aboba";
						header("Location: index.php?search=");
						exit();
					}
				} else {
					$message = "Недійсне ім'я користувача або пароль!";
				}
			} else {
				$message = "Усі поля є обов’язковими для заповнення!";
			}
		}
		?>
		<?php if (!empty($message)) {echo "<p class='error'>" . "ПОВІДОМЛЕННЯ: ". $message . "</p>";} ?>

		<?php include("includes/footer.php"); ?>