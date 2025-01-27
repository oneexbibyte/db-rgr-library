<?php include("includes/header.php"); ?>

<?php 
$user_id = "";
$login = "";
$password = "";
$email = "";
$first_name = "";
$last_name = "";
$access_id = "";
$subscription_id = "";
$phone_number = "";



function getPosts() {
	$posts = array();

	$posts[0] = isset($_POST['user_id']) ? filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT) : null;
	if (!empty($posts[0]) && !filter_var($posts[0], FILTER_VALIDATE_INT)) {
		echo "Неправильний формат user_id!";

	}

	$posts[1] = isset($_POST['login']) ? filter_var($_POST['login'], FILTER_SANITIZE_STRING) : null;
	if (empty($posts[1]) && !isset($_POST['search']) && !isset($_POST['delete']) && !isset($_GET['id'])) {
		echo "Введіть логін!";
		return false;        
	}

	$posts[2] = isset($_POST['password']) ?  filter_var($_POST['password'], FILTER_SANITIZE_STRING) : null; 
	if (empty($posts[2]) && !isset($_POST['search']) && !isset($_POST['delete']) && !isset($_GET['id'])) {
		echo "Введіть пароль!";
		return false;
	}

	$posts[3] = isset($_POST['first_name']) ? filter_var($_POST['first_name'], FILTER_SANITIZE_STRING) : null;

	$posts[4] = isset($_POST['last_name']) ? filter_var($_POST['last_name'], FILTER_SANITIZE_STRING) : null;


	$posts[5] = isset($_POST['access_id']) ? filter_var($_POST['access_id'], FILTER_SANITIZE_NUMBER_INT) : null;
	if (empty($posts[5]) && !filter_var($posts[5], FILTER_VALIDATE_INT) && !isset($_POST['search']) && !isset($_POST['delete']) && !isset($_GET['id'])) {
		echo "Неправильний формат access_id!";
		return false;
	}

	$posts[6] = isset($_POST['subscription_id']) ? filter_var($_POST['subscription_id'], FILTER_SANITIZE_NUMBER_INT) : null;
	if (empty($posts[6]) && !filter_var($posts[6], FILTER_VALIDATE_INT)&& !isset($_POST['search']) && !isset($_POST['delete']) && !isset($_GET['id'])) {
		echo "Неправильний формат subscription_id!";
		return false;
	}

	$posts[7] = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : null;
	if (!empty($posts[7]) && !filter_var($posts[7], FILTER_VALIDATE_EMAIL)) {
		echo "Неправильний формат email!";
		return false;
	}

	$posts[8] = isset($_POST['phone_number']) ? filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT) : null;
	if (!empty($posts[8]) && !filter_var($posts[8], FILTER_VALIDATE_INT)) {
		echo "Неправильний формат phone_number!";
		return false;
	}

	return $posts;
}

?>

<div class="edit-page">
	<div class="help-information">
		<?php
		if(!isset($_GET['id'])){
			echo "<h2>Допоміжна інформація</h2>";
			$sql_subscription_help = "SELECT * FROM subscriptions";

			if (!$result_subscription_help = mysqli_query($con, $sql_subscription_help)) {
				echo "Вибачте, виникла проблема у роботі сайту.";
				exit;
			}

			echo "<table>\n";
			echo "<thead><tr><th colspan = '4'>Підписки</tr></th></thead>\n";
			if(mysqli_affected_rows($con) > 0){
				while ($tabl_subscription_help = $result_subscription_help->fetch_assoc()) {
					echo "<tr>\n";
					echo "<td>" . $tabl_subscription_help['subscription_id'] . "</td><td>". $tabl_subscription_help['subscription_level'] . "</td><td>" . $tabl_subscription_help['number_of_days'] . "</td><td>" . $tabl_subscription_help['price'] . "</td>" ;
					echo "</tr>";

				}
			}else echo "<tr>\n <td>Відсутні</td> </tr>";


			echo "</table>\n";

			$sql_access_help = "SELECT * FROM access";

			if (!$result_access_help = mysqli_query($con, $sql_access_help)) {
				echo "Вибачте, виникла проблема у роботі сайту.";
				exit;
			}
			echo "<table>\n";
			echo "<thead><tr><th colspan = '3'>Рівні доступу</tr></th></thead>\n";
			if(mysqli_affected_rows($con) > 0){
				while ($tabl_access_help = $result_access_help->fetch_assoc()) {
					echo "<tr>\n";
					echo "<td>" . $tabl_access_help['access_id'] . "</td><td>". $tabl_access_help['access_level'] . "</td><td>" . $tabl_access_help['description'] . "</td>" ;
					echo "</tr>";
				}
			}else echo "<tr>\n <td>Нема</td> </tr>";


			echo "</table>\n";
		}
		?>
	</div>
	<?php
	if(isset($_POST['search']) || isset($_GET['id']))
	{
		$data = getPosts();

		if(isset($_GET['id'])){
			$data[0] = $_GET['id'];
		}
		
		$search_Query = "SELECT * FROM users WHERE user_id = '$data[0]'";
		
		$search_Result = mysqli_query($con, $search_Query);
		
		if($search_Result)
		{
			if(mysqli_num_rows($search_Result))
			{
				while($row = mysqli_fetch_array($search_Result))
				{
					$user_id = $row['user_id'];
					$login = $row['login'];
					$password = $row['password'];
					$first_name = $row['first_name'];
					$last_name = $row['last_name'];
					$access_id = $row['access_id'];
					$subscription_id = $row['subscription_id'];
					$email = $row['email'];
					$phone_number = $row['phone_number'];
				}
			}else{
				echo 'Немає даних для цього ідентифікатора';
			}
		} else{
			echo 'Результат: Помилка';
		}
	}

	if(isset($_POST['insert']))
	{
		$data = getPosts();

		$insert_Query = "INSERT INTO `users`(`login`, `password`, `first_name`, `last_name`, `access_id`, `subscription_id`, `email`, `phone_number`) VALUES ('$data[1]', '$data[2]', " . ($data[3] === "" ? "NULL" : "'$data[3]'") . ", " . ($data[4] === "" ? "NULL" : "'$data[4]'") . ", '$data[5]', '$data[6]', " . ($data[7] === "" ? "NULL" : "'$data[7]'") . ", " . ($data[8] === "" ? "NULL" : "'$phone_number'") . ")";
		try{
			$insert_Result = mysqli_query($con, $insert_Query);
			
			if($insert_Result)
			{
				if(mysqli_affected_rows($con) > 0)
				{
					echo 'Дані додано';
					header("Refresh: 0");
				}else{
					echo 'Дані не додано';
				}
			}
		} catch (Exception $ex) {
			echo 'Помилка вставки '.$ex->getMessage();
		}
	}

	if(isset($_POST['delete']))
	{
		$data = getPosts();
		$delete_Query = "DELETE FROM `users` WHERE `user_id` = $data[0]";
		try{
			$delete_Result = mysqli_query($con, $delete_Query);
			
			if($delete_Result)
			{
				if(mysqli_affected_rows($con) > 0)
				{
					echo 'Дані видалено';
					header("Refresh: 0");
				}else{
					echo 'Дані не видалено';
				}
			}
		} catch (Exception $ex) {
			echo 'Помилка видалення '.$ex->getMessage();
		}
	}


	if(isset($_POST['update']))
	{
		$data = getPosts();
		$update_Query = "UPDATE `users` SET `login`='$data[1]', `password`='$data[2]', `first_name`=" . ($data[3] === "" ? "NULL" : "'$data[3]'") . ", `last_name` = " . ($data[4] === "" ? "NULL" : "'$data[4]'") . ", `access_id` = '$data[5]', `subscription_id` = '$data[6]', `email` = " . ($data[7] === "" ? "NULL" : "'$data[7]'") . ", `phone_number` = " . ($data[8] === "" ? "NULL" : "'$data[8]'") . " WHERE `user_id` = $data[0]";

		try{
			$update_Result = mysqli_query($con, $update_Query);
			
			if($update_Result)
			{
				if(mysqli_affected_rows($con) > 0)
				{
					echo 'Дані оновлено';
					header("Refresh: 0");
				}else{
					echo 'Дані не оновлено';
				}
			}
		} catch (Exception $ex) {
			echo 'Помилка оновлення '.$ex->getMessage();
		}
	}

	if(isset($_POST['crear']))
	{
		$user_id = null;
		$login = null;
		$password = null;
		$first_name = null;
		$last_name = null;
		$access_id = null;
		$subscription_id = null;
		$email = null;
		$phone_number = null;
	}

	?>

	<div class="edit-input">
		
		

		<form action="users.php" method="post" class="container" id="container"><br><br>

			<input type="number" name="user_id" placeholder = "ID" value="<?php echo $user_id;?>"><br><br>
			<input type="text" name = "login" placeholder = "Логін" value="<?php echo $login;?>"><br><br>
			<input type="password" name = "password" placeholder = "Пароль" value="<?php echo $password;?>"><br><br>
			<input type="text" name = "first_name" placeholder = "Ім'я" value="<?php echo $first_name;?>"><br><br>
			<input type="text" name = "last_name" placeholder = "Прізвище" value="<?php echo $last_name;?>"><br><br>
			<input type="number" name = "access_id" placeholder = "Рівень доступу" value="<?php echo $access_id;?>"><br><br>
			<input type="number" name = "subscription_id" placeholder = "Підписка" value="<?php echo $subscription_id;?>"><br><br>
			<input type="text" name = "email" placeholder = "Мейл" value="<?php echo $email;?>"><br><br>
			<input type="number" name = "phone_number" placeholder = "Номер телефону" value="<?php echo $phone_number;?>"><br><br>

			<div id="buttons">
				<input class = "button"type="submit" name = "insert" value="Add">
				<input class = "button"type="submit" name = "delete" value="Delete">
				<input class = "button"type="submit" name = "search" value="Search">
				<input class = "button"type="submit" name = "clear" value="Clear">
				<input class = "button"type="submit" name = "update" value="Update">
			</div>
			<?php
			if (isset($_GET['id'])) {
				echo '<script>
				document.getElementsByName("user_id")[0].setAttribute("hidden", true);
				const brElements = document.querySelectorAll("br");

				if (brElements.length >= 4) {
					brElements[1].remove();
					brElements[2].remove(); 
					brElements[3].remove(); 
				}

				brElements.array = 0;
				
				document.getElementsByName("login")[0].setAttribute("readonly", true);
				document.getElementsByName("access_id")[0].setAttribute("readonly", true);
				document.getElementsByName("subscription_id")[0].setAttribute("readonly", true);
				document.getElementsByName("insert")[0].remove();
				document.getElementsByName("delete")[0].remove();
				document.getElementsByName("search")[0].remove();
				document.getElementsByName("clear")[0].remove();

				</script>';
			}
			?>
		</form>
	</div>
</div>
<?php include("includes/footer.php"); ?>