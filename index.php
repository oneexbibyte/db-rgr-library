<?php include("includes/header.php"); ?>
<div>
	<form action="">
		<div class="search-box">
			<input placeholder="Поле для пошуку" type="text" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];}?>">
			<button class="button" type="submit">Знайти</button>
			<?php if ($account_id['access_id'] > 2) { ?>
				<a class="button" href = 'books.php'>Додати книгу</a>
			<?php } ?>
		</div>
	</form>
	<div class="table">
		<table>
			<thead>
				<tr>
					<th>Назва книги</th>
					<th>Автор</th>
					<th>Рік написання</th>
					<th>Видаець</th>
					<th>Рік видання</th>
					<th>Жанр</th>
					<th>Мова</th>
					<th>Відкрити</th>
					<th>Редагувати/Видалити</th>
				</tr>
			</thead>
			<?php 

			if(isset($_GET['search'])){
				$filtervalue = $_GET['search'];
				$filterdata = "SELECT book_id, title, first_name, last_name, writing_year, publisher, publish_year, name, language FROM books 
				JOIN authors ON books.author_id = authors.author_id
				JOIN genres ON books.genre_id = genres.genre_id
				JOIN languages ON books.language_id = languages.language_id
				WHERE CONCAT(book_id, title, first_name, last_name, writing_year, publisher, name, language) LIKE '%$filtervalue%'";
				$filterdata_run = mysqli_query($con, $filterdata);
				if(mysqli_num_rows($filterdata_run)>0){
					?>
					<tbody>
						<?php
						foreach ($filterdata_run as $row) {
							?>
							<tr>
								<td><?php echo $row['title']?></td>
								<td><?php echo $row['first_name'] . " " . $row['last_name']?></td>	
								<td><?php echo $row['writing_year']?></td>
								<td><?php echo $row['publisher']?></td>
								<td><?php echo $row['publish_year']?></td>
								<td><?php echo $row['name']?></td>
								<td><?php echo $row['language']?></td>
								<td><button class="button" onclick="window.location.href = 'page.php?id=<?php echo $row['book_id'] ?>';">Відкрити</button></td>
								<td><?php if ($account_id['access_id'] > 2) { ?>
									<button class="button" onclick="window.location.href = 'books.php?id=<?php echo $row['book_id'] ?>';">Редагувати</button>
									<button class="button" onclick="window.location.href = 'books.php?id=<?php echo $row['book_id'] ?>';">Видалити</button>
									<?php }else echo "Недостатній рівень доступу"; ?></td>
								</tr>
								<?php
							}
						}else{
							?>
							<tr>
								<td colspan="9">Нічого не знайдено</td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>	
		<?php
	}
	else{
		$current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$new_url = $current_url . "?search="; 
		header("Location: $new_url");
		exit();
	}
	?>
	<?php include("includes/footer.php"); ?>