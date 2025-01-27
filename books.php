<?php include("includes/header.php"); ?>

<?php 
if(!isset($_SESSION["session_username"])){
	echo "<script>
	alert('Сталася помилка');
	window.location.href = 'index.php?search=';
	</script>";
	exit();
}
$book_id = "";
$title = "";
$author_id = "";
$writing_year = "";
$publisher = "";
$publish_year = "";
$genre_id = "";
$pages = "";
$language_id = "";
$page_content = "";

function getPosts() {
	$posts = array();

	$posts[0] = isset($_POST['book_id']) ? filter_var($_POST['book_id'], FILTER_SANITIZE_NUMBER_INT) : null;
	if (!empty($posts[0]) && !filter_var($posts[0], FILTER_VALIDATE_INT)) {
		echo "Неправильний формат book_id!";
	}

	$posts[1] = isset($_POST['title']) ? filter_var($_POST['title'], FILTER_SANITIZE_STRING) : null;
	if (empty($posts[1]) && !isset($_POST['search']) && !isset($_POST['delete']) && !isset($_GET['id'])) {
		echo "Введіть назву!";
		return false;        
	}

	$posts[2] = isset($_POST['author_id']) ? filter_var($_POST['author_id'], FILTER_SANITIZE_NUMBER_INT) : null;
	if (empty($posts[2]) && !isset($_POST['search']) && !isset($_POST['delete']) && !isset($_GET['id']) && !filter_var($posts[2], FILTER_VALIDATE_INT)) {
		echo "Введіть айді автора!";
		return false;
	}

	$posts[3] = isset($_POST['writing_year']) ? filter_var($_POST['writing_year'], FILTER_SANITIZE_NUMBER_INT) : null;
	if (empty($posts[3]) && !isset($_POST['search']) && !isset($_GET['id']) && !isset($_POST['delete'])&& !filter_var($posts[3], FILTER_VALIDATE_INT)) {
		echo "Введіть рік написання!";
		return false;
	}

	$posts[4] = isset($_POST['publisher']) ? filter_var($_POST['publisher'], FILTER_SANITIZE_STRING) : null;  

	$posts[5] = isset($_POST['publish_year']) ? filter_var($_POST['publish_year'], FILTER_SANITIZE_NUMBER_INT) : null;
	if (!empty($posts[5]) && !filter_var($posts[5], FILTER_VALIDATE_INT)) {
		echo "Неправильний формат publish_year!";
		return false;
	}

	$posts[6] = isset($_POST['genre_id']) ? filter_var($_POST['genre_id'], FILTER_SANITIZE_NUMBER_INT) : null;
	if (!empty($posts[6]) && !filter_var($posts[6], FILTER_VALIDATE_INT)) {
		echo "Неправильний формат genre_id!";
		return false;
	}

	$posts[7] = isset($_POST['pages']) ? filter_var($_POST['pages'], FILTER_SANITIZE_NUMBER_INT) : null;
	if (empty($posts[7]) && !filter_var($posts[7], FILTER_VALIDATE_INT) && !isset($_POST['search']) && !isset($_GET['id']) && !isset($_POST['delete'])) {
		echo "Введіть кількість сторінок!";
		return false;
	}

	$posts[8] = isset($_POST['language_id']) ? filter_var($_POST['language_id'], FILTER_SANITIZE_NUMBER_INT) : null;
	if (empty($posts[8]) && !filter_var($posts[8], FILTER_VALIDATE_INT) && !isset($_POST['search']) && !isset($_GET['id']) && !isset($_POST['delete'])) {
		echo "Неправильний формат language_id!";
		return false;
	}

	$posts[9] = isset($_POST['page_content']) ? filter_var($_POST['page_content'], FILTER_SANITIZE_STRING) : null;
	if (empty($posts[9]) && !isset($_POST['search']) && !isset($_POST['delete']) && !isset($_GET['id'])) {
		echo "Неправильний формат page_content!";
		return false;        
	}

	return $posts;
}

?>
<div class="edit-page">
	<div class="help-information">
		<?php
		echo "<h2>Допоміжна інформація</h2>";

		$sql_authors_help = "SELECT * FROM authors";

		if (!$result_authors_help = mysqli_query($con, $sql_authors_help)) {
			echo "Вибачте, виникла проблема у роботі сайту.";
			exit;
		}

		echo "<table>\n";
		echo "<thead><tr><th colspan = '3'>Автори</tr></th></thead>\n";
		if(mysqli_affected_rows($con) > 0){
			while ($tabl_authors_help = $result_authors_help->fetch_assoc()) {
				echo "<tr>\n";
				echo "<td>" . $tabl_authors_help['author_id'] . "</td><td>". $tabl_authors_help['first_name'] . "</td><td>" . $tabl_authors_help['last_name'] . "</td>" ;
				echo "</tr>";

			}
		}else echo "<tr>\n <td>Відсутні</td> </tr>";


		echo "</table>\n";

		$sql_genres_help = "SELECT * FROM genres";

		if (!$result_genres_help = mysqli_query($con, $sql_genres_help)) {
			echo "Вибачте, виникла проблема у роботі сайту.";
			exit;
		}
		echo "<table>\n";
		echo "<thead><tr><th colspan = '3'>Жанри</tr></th></thead>\n";
		if(mysqli_affected_rows($con) > 0){
			while ($tabl_genres_help = $result_genres_help->fetch_assoc()) {
				echo "<tr>\n";
				echo "<td>" . $tabl_genres_help['genre_id'] . "</td><td>". $tabl_genres_help['name'] . "</td><td>" . $tabl_genres_help['description'] . "</td>" ;
				echo "</tr>";
			}
		}else echo "<tr>\n <td>Відсутні</td> </tr>";


		echo "</table>\n";

		$sql_languages_help = "SELECT * FROM languages";

		if (!$result_languages_help = mysqli_query($con, $sql_languages_help)) {
			echo "Вибачте, виникла проблема у роботі сайту.";
			exit;
		}
		echo "<table>\n";
		echo "<thead><tr><th colspan = '3'>Мови</tr></th></thead>\n";
		if(mysqli_affected_rows($con) > 0){
			while ($tabl_languages_help = $result_languages_help->fetch_assoc()) {
				echo "<tr>\n";
				echo "<td>" . $tabl_languages_help['language_id'] . "</td><td>". $tabl_languages_help['language'] . "</td><td>" . $tabl_languages_help['country'] . "</td>" ;
				echo "</tr>";
			}
		}else echo "<tr>\n <td>Відсутні</td> </tr>";


		echo "</table>\n";
		
		?>
	</div>
	<?php
	if(isset($_POST['search']) || isset($_GET['id']))
	{	
		$data = getPosts();

		if(isset($_GET['id'])){
			$data[0] = $_GET['id'];
		}
		
		$search_Query = "SELECT * FROM books JOIN pages ON books.book_id = pages.book_id WHERE books.book_id = '$data[0]'";
		
		$search_Result = mysqli_query($con, $search_Query);
		
		if($search_Result)
		{
			if(mysqli_num_rows($search_Result))
			{
				while($row = mysqli_fetch_array($search_Result))
				{
					$book_id = $row['book_id'];
					$title = $row['title'];
					$author_id = $row['author_id'];
					$writing_year = $row['writing_year'];
					$publisher = $row['publisher'];
					$publish_year = $row['publish_year'];
					$genre_id = $row['genre_id'];
					$pages = $row['pages'];
					$language_id = $row['language_id'];
					$page_content = $row['page_content'];
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
		$insert_Query = "INSERT INTO `books`(`title`, `author_id`, `writing_year`, `publisher`, `publish_year`, `genre_id`, `pages`, `language_id`) VALUES ('$data[1]', '$data[2]','$data[3]', " . ($data[4] === "" ? "NULL" : "'$data[4]'") . ", " . ($data[5] === "" ? "NULL" : "'$data[5]'") . ", " . ($data[6] === "" ? "NULL" : "'$data[6]'") . ", '$data[7]', '$data[8]')";

		try{
			$insert_Result = mysqli_query($con, $insert_Query);
			
			if($insert_Result)
			{
				if(mysqli_affected_rows($con) > 0)
				{
					$search_added_book = "SELECT book_id FROM books WHERE title = '$data[1]'";

					$result_search = mysqli_query($con, $search_added_book);

					if (!$result_search) {
						echo "Вибачте, виникла проблема у роботі сайту.";
						exit;
					}
					$s_book_id = mysqli_fetch_array($result_search);

					$book = $s_book_id['book_id'];

					$ac_id = $account_id['user_id'];

					$insert_page_content = "INSERT INTO `pages` (`book_id`, `create_date`, `edit_date`, `user_id`, `page_content`) VALUES ('$book', NOW(), NULL, '$ac_id', '$data[9]')";
					try{
						$insert_Result_Page = mysqli_query($con, $insert_page_content);
						if(mysqli_affected_rows($con) > 0){
							echo 'Дані додано';
						}else echo 'Дані не додано';
					}catch (Exception $ex) {
						echo 'Помилка вставки '.$ex->getMessage();
					}
				} else echo 'Дані не додано';
			}
		}catch (Exception $ex) {
			echo 'Помилка вставки '.$ex->getMessage();
		}
	}
	if(isset($_POST['delete']))
	{
		$data = getPosts();
		$delete_Query = "DELETE FROM `books`, 'pages' WHERE `book_id` = $data[0]";
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
		$update_Query = "UPDATE `books` SET `title`='$data[1]', `author_id`='$data[2]', `writing_year`='$data[3]', `publisher` = " . ($data[4] === "" ? "NULL" : "'$data[4]'") . ", `publish_year` = " . ($data[5] === "" ? "NULL" : "'$data[5]'") . ", `genre_id` = " . ($data[6] === "" ? "NULL" : "'$data[6]'") . ", `pages` = '$data[7]', `language_id` = '$data[8]' WHERE `book_id` = $data[0]";

		try{
			$update_Result = mysqli_query($con, $update_Query);

			if($update_Result)
			{
				if(mysqli_affected_rows($con) > 0)
				{
					$ac_id = $account_id['user_id'];
					$update_Query_Page = "UPDATE `pages` SET `edit_date` = NOW(), `page_content` = '$data[9]', `page_content` = '$ac_id' WHERE  `book_id` = $data[0]";
					try{
						$update_Result_Page = mysqli_query($con, $update_Query_Page);
						if(mysqli_affected_rows($con) > 0){
							echo 'Дані оновлено';
						}else echo 'Дані не оновлено';
					}catch (Exception $ex) {
						echo 'Помилка оновлення '.$ex->getMessage();
					}
				} else echo 'Дані не оновлено';
			}
		} catch (Exception $ex) {
			echo 'Помилка оновлення '.$ex->getMessage();
		}
	}

	if(isset($_POST['crear']))
	{
		$book_id = null;
		$title = null;
		$author_id = null;
		$writing_year = null;
		$publisher = null;
		$publish_year = null;
		$genre_id = null;
		$pages = null;
		$language_id = null;
		$page_content = null;
	}

	?>
	<div class="edit-input">
		<form action="books.php" method="post" class="container" id="container"><br><br>
			<input type="number" name = "book_id" placeholder = "ID" value="<?php echo $book_id;?>"><br><br>
			<input type="text" name = "title" placeholder = "Назва" value="<?php echo $title;?>"><br><br>
			<input type="number" name = "author_id" placeholder = "Автор" value="<?php echo $author_id;?>"><br><br>
			<input type="number" name = "writing_year" placeholder = "Рік написання" value="<?php echo $writing_year;?>"><br><br>
			<input type="text" name = "publisher" placeholder = "Видавець" value="<?php echo $publisher;?>"><br><br>
			<input type="number" name = "publish_year" placeholder = "Рік видання" value="<?php echo $publish_year;?>"><br><br>
			<input type="number" name = "genre_id" placeholder = "Жанр" value="<?php echo $genre_id;?>"><br><br>
			<input type="number" name = "pages" placeholder = "Кількість сторінок" value="<?php echo $pages;?>"><br><br>
			<input type="number" name = "language_id" placeholder = "Мова" value="<?php echo $language_id;?>"><br><br>
			<textarea name="page_content" placeholder="Опис книги"><?php echo $page_content;?></textarea>

			<div id="buttons">
				<input class = "button"type="submit" name = "insert" value="Add">
				<input class = "button"type="submit" name = "update" value="Update">
				<input class = "button"type="submit" name = "delete" value="Delete">
				<input class = "button"type="submit" name = "search" value="Search">
				<input class = "button"type="submit" name = "clear" value="Clear">
			</div>
		</form>
	</div>
</div>
<?php include("includes/footer.php"); ?>