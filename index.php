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

        var_dump($user);

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

<div>
    <form method="post">

        <div class="form-element">
            <label for="email">Username: </label>
            <input type="text" id="username" name="username"/>
        </div>      

        <div class="form-element">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"/>
        </div>      

        <button type="submit">Log In</button>

    </form>
</div>