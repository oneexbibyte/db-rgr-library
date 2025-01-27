<?php include("includes/header.php"); ?>
<?php
if(isset($_GET['id'])){
   $id = $_GET['id'];
} else{
   echo "<script>
   alert('Сталася помилка');
   window.location.href = 'index.php?search=';
   </script>";
   exit();
}
$book_id = "SELECT page_id, COUNT(*) AS count FROM pages WHERE book_id = $id";
$find_result = mysqli_query($con, $book_id);
if ($find_result) {
    $row = mysqli_fetch_assoc($find_result);

    $count = $row['count'];
    if($count > 0){
        $page = $row['page_id'];
    }else {
        "<script>
        alert('Сторінки не існує');
        window.location.href = 'index.php?search=';
        </script>";
        exit();
    }
}
else echo "Error: " . mysqli_error($con);
$sql = "SELECT title, page_content, first_name, last_name, language, pages, writing_year, publisher, publish_year, name FROM pages 
JOIN books ON pages.book_id = books.book_id
JOIN authors ON books.author_id = authors.author_id
JOIN genres ON books.genre_id = genres.genre_id
JOIN languages ON books.language_id = languages.language_id
WHERE page_id = $page";
$result = mysqli_query($con, $sql);
if($result){
    $page_data = mysqli_fetch_assoc($result);
}
else {
    echo "<script>
    alert('Сталася помилка');
    window.location.href = 'index.php?search=';
    </script>";
    exit();
}
if(isset($_SESSION["session_username"])){
    $user_login = $_SESSION["session_username"];

    $sql = "SELECT login, subscription_id, access_id FROM users WHERE login = '$user_login'";
    $result = mysqli_query($con, $sql);
    if($result){
        $user = $result->fetch_assoc();
    }
    else {
        echo "Error: " . mysqli_error($con);
        $user = "No user";
    }
} else $user = "No user";

?> 
<div class="page-body">
    <div class="page-content">
        <div class="page-title">
            <p><?php echo $page_data['title']; ?></p>
        </div>
        <div class="description">
            <p><?php echo "Автор: " . $page_data['first_name'] . " " . $page_data['last_name']; ?></p>
            <p><?php echo "Рік написання: " . $page_data['writing_year'] . " р."; ?></p>
            <p><?php echo "Видавець: " . $page_data['publisher']; ?></p>
            <p><?php echo "Рік видання: " . $page_data['publish_year']. " p."; ?></p>
            <p><?php echo "Жанр: " . $page_data['name']; ?></p>
            <p><?php echo "Мова: " . $page_data['language']; ?></p>
            <p><?php echo "Кількість сторінок: " . $page_data['pages']; ?></p>
        </div>
        <?php
        $href = "files/Test.pdf";
        if($account_id['access_id']>1)echo '<div><button class="button" onclick="window.location.href = \'' . $href . '\'">Читати</button></div>';
        ?>
    </div>
    <div class="text-description">
        <h2>Про книгу</h2>
        <p><?php echo $page_data['page_content']; ?></p>
    </div>
</div>
<?php include("includes/footer.php"); ?>
