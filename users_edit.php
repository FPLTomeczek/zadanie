<?php

include('includes/database.php');
include('includes/config.php');
include('includes/header.php');
include('includes/functions.php');

secureRoute();

if(isset($_GET['id'])){
    if($stm = $connect->prepare("SELECT * FROM users WHERE id = ?")){
        $stm->bind_param('i',$_GET['id']);
        $stm->execute();
        $result = $stm->get_result();
        $stm->close();
        $user = $result->fetch_assoc();
    }
    else{
        echo 'Could not prepare statement';
    }

    if(isset($_POST['first_name'])){
        if($stm = $connect->prepare("UPDATE users SET username=?, first_name=?, second_name=?, dob=?, password=? WHERE id = ?")){
        $hashed = SHA1($_POST['password']);
        $stm->bind_param('sssssi',$_POST['username'], $_POST['first_name'],$_POST['second_name'],$_POST['dob'],$hashed, $_GET['id']);
        $stm->execute();
        $stm->close();
        setMessage("User: ".$_POST['first_name']." edited.");
     }
     else{
        echo 'Could not prepare statement';
     }
     header("Location: users.php");
    }
}

?>

<div>
    <form method="post">

        <div class="form-element">
            <label for="first_name">First Name: </label>
            <input type="text" id="first_name" name="first_name" value=<?php echo $user['first_name']?>>
        </div>      

        <div class="form-element">
            <label for="second_name">Second Name: </label>
            <input type="text" id="second_name" name="second_name" value=<?php echo $user['second_name']?>>
        </div>      

        <div class="form-element">
            <label for="dob">Date of Birth: </label>
            <input type="date" id="dob" name="dob" value=<?php echo $user['dob']?>>
        </div>      

        <div class="form-element">
            <label for="email">Username: </label>
            <input type="text" id="username" name="username" value=<?php echo $user['username']?>>
        </div>      

        <div class="form-element">
            <label for="password">Password: </label>
            <input type="password" id="password" name="password">
        </div>      

        <button type="submit">Edit User</button>

    </form>
</div>