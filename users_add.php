<?php

include('includes/database.php');
include('includes/config.php');
include('includes/header.php');
include('includes/functions.php');

secureRoute();

if(isset($_POST['username'])){
    if($stm = $connect->prepare("INSERT INTO users (username, password, first_name, second_name, dob) VALUES (?, ?, ?, ?, ?)")){
        $hashed = SHA1($_POST['password']);
        $stm->bind_param('sssss', $_POST['username'], $hashed, $_POST['first_name'], $_POST['second_name'], $_POST['dob']);
        $stm->execute();
        $stm->close();
        header("Location: users.php");
        setMessage("User ".$_POST['username']." added.");
        die();
    }
    else{
        echo 'Could not prepare statement';
    }
}

?>

<div>
    <form method="post">

        <div class="form-element">
            <label for="first_name">First Name: </label>
            <input type="text" id="first_name" name="first_name"/>
        </div>      

        <div class="form-element">
            <label for="second_name">Second Name: </label>
            <input type="text" id="second_name" name="second_name"/>
        </div>      

        <div class="form-element">
            <label for="dob">Date of Birth: </label>
            <input type="date" id="dob" name="dob"/>
        </div>      

        <div class="form-element">
            <label for="email">Username: </label>
            <input type="text" id="username" name="username"/>
        </div>      

        <div class="form-element">
            <label for="password">Password: </label>
            <input type="password" id="password" name="password"/>
        </div>      

        <button type="submit">Add User</button>

    </form>
</div>