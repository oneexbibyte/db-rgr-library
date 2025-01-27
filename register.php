<?php include("includes/header.php"); ?>

<div class="container mregister">
	<div id="login">
		<h1>Реєстрація</h1>
		<form action="register.php" id="registerform" method="post"name="registerform">
			<p><input class="input" id="full_name" name="full_name"size="32" placeholder = "Логін" type="text" value=""></label></p>
			<p><input class="input" id="email" name="email" size="32"type="email" placeholder = "E-mail (не обов'язково)" value=""></p>
			<p><input class="input" id="first_name" name="first_name" placeholder = "Ім'я (не обов'язково)"size="32" type="text" value=""></p>
			<p><input class="input" id="last_name" name="last_name" placeholder = "Прізвище (не обов'язково)" size="32" type="text" value=""></p>
			<p><input class="input" id="phone_number" placeholder = "Номер телефону (не обов'язково)" name="phone_number"size="32" type="text" value="+380"></p>
			<p><input class="input" id="password" name="password"size="32" placeholder = "Пароль" type="password" value=""></p>
			<p class="submit"><input class="button" id="register" name= "register" type="submit" value="Зареєструватися"></p>
			<p class="regtext">Вже зареєстровані? <a class="link" href= "login.php">Введіть логін</a>!</p>
			<a class="link" href= "index.php">Перейти на головну</a>
		</form>
	</div>
</div>

<?php

if(isset($_POST["register"])){

	if(!empty($_POST['full_name']) && !empty($_POST['password'])) {
		$full_name = htmlspecialchars($_POST['full_name']);
		$email = htmlspecialchars($_POST['email']);			
		$first_name = htmlspecialchars($_POST['first_name']);
		$last_name = htmlspecialchars($_POST['last_name']);
		$phone_number = htmlspecialchars($_POST['phone_number']);
		$password = htmlspecialchars($_POST['password']);
		$query = mysqli_query($con, "SELECT * FROM users WHERE login='".$full_name."'");
		$numrows = mysqli_num_rows($query);
		var_dump(strlen($phone_number));
		if($numrows==0)
		{
			$sql="INSERT INTO users
			(login, password, first_name, last_name, access_id, subscription_id, email, phone_number)
			VALUES('$full_name','$password', " . ($first_name === "" ? "NULL" : "'$first_name'") . "," . ($last_name === "" ? "NULL" : "'$last_name'") . ", 1, 9, " . ($email === "" ? "NULL" : "'$email'") . ", " . (strlen($phone_number) <5 ? "NULL" : "'$phone_number'") . ")";
			$result=mysqli_query($con, $sql);
			if($result){
				$message = "Обліковий запис успішно створено!";
			} else {
				$message = "Не вдалося вставити дані!";
			}
		} else {
			$message = "Це ім'я користувача вже зайтяно! Будь ласка, спробуйте інше!";
		}
	} else {
		$message = "Поля 'Логін' та 'Пароль' мають бути заповнені!";
	}
}
?>

<?php if (!empty($message)) {echo "<p class='error'>" . "ПОВІДОМЛЕННЯ: ". $message . "</p>";} ?>


<?php include("includes/footer.php"); ?>