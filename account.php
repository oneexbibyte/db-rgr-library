<?php include("includes/header.php"); ?>

<?php
if(isset($_SESSION["session_username"])){
    $user_login = $_SESSION["session_username"];

    $sql = "SELECT user_id, login, first_name, last_name, email, phone_number, access_level, subscription_level FROM users 
    JOIN access ON users.access_id = access.access_id 
    JOIN subscriptions ON users.subscription_id = subscriptions.subscription_id 
    WHERE login = '$user_login'";
    $result = mysqli_query($con, $sql);
    if($result){
        $user = $result->fetch_assoc();
    }
    else {
     echo "<script>
     alert('Сталася помилка');
     window.location.href = 'index.php?search=';
     </script>";
     exit();
 }
} else{
	header("Location: index.php?search=");
  exit();
}
?>
<div class="account-page">
    <div class="account-data">
        <h2>Ваші дані:</h2>
        <p><?php echo "Логін: " . $user['login'] ?></p>
        <div id="access-change">
            <p><?php echo "Підписка: " . $user['subscription_level'] ?></p>
            <?php if($account_id['access_id']<2){
                echo '<form action="" method="post"><input class = "button" type="submit" name = "subscription_change" value="Придбати підписку"></form>';
            }?>
        </div>
        <p><?php echo "Ім'я: " . $user['first_name'] ?></p>
        <p><?php echo "Прізвище: " . $user['last_name'] ?></p>
        <p><?php echo "Мейл: " . $user['email'] ?></p>
        <p><?php echo "Номер телефону: +" . $user['phone_number'] ?></p>
        <p><?php echo "Роль: " . $user['access_level'] ?></p>
    </div>
    <div id="button-change">
        <?php
        if($account_id['access_id']>3){
            echo '<a class="button" href="users.php">Змінити дані користувачів</a>';
        }else{ ?> 
            <a class="button" href="users.php?id=<?php echo $user['user_id']; ?>">Змінити дані</a>
        </div>
        <?php
    }
    if(isset($_POST['subscription_change'])){
        $user_id_change = $user['user_id'];
        $change_access = "UPDATE `users` SET `subscription_id` = 2, `access_id` = 2 WHERE `user_id` = $user_id_change";
        $update_access = mysqli_query($con, $change_access);
        if ($update_access) {
            header("Refresh: 0");
        }else echo 'Дані не оновлено';
    }
    ?>
</div>
</div>
<?php include("includes/footer.php"); ?>
