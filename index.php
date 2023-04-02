<?php

include('includes/database.php');
include('includes/config.php');
include('includes/header.php');


if(isset($_SESSION['id'])){
    header("Location: dashboard.php");
    die();
}

if(isset($_POST['username'])){
    if($stm = $connect->prepare("SELECT * FROM users WHERE username = ? AND password = ?")){
        $hashed = SHA1($_POST['password']);
        $stm->bind_param('ss', $_POST['username'], $hashed);
        $stm->execute();
        $result = $stm->get_result();
        $user = $result->fetch_assoc();

        if($user){
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            die();
        }
        $stm->close();
    }
    else{
        echo 'Coud not prepare statement';
    }
}

?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
    <form method="post">

        <div class="form-outline mb-4">
            <input type="text" id="username" name="username" class="form-control"/>
            <label for="email" class="form-label">Username </label>
        </div>      

        <div class="form-outline mb-4">
            <input type="password" id="password" name="password" class="form-control"/>
            <label for="password" class="form-label">Password </label>
        </div>      

        <button type="submit" class="btn btn-primary btn-block">Log In</button>

    </form>
</div>
</div>
</div>

<?php
include('includes/footer.php');