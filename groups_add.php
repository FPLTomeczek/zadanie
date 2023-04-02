<?php

include('includes/database.php');
include('includes/config.php');
include('includes/header.php');
include('includes/functions.php');

secureRoute();

if(isset($_POST['name'])){
    if($stm = $connect->prepare("INSERT INTO groups (name) VALUES (?)")){
        $stm->bind_param('s', $_POST['name']);
        $stm->execute();
        $stm->close();
        setMessage("Group ".$_POST['name']." added.");
        header("Location: groups.php");
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
            <label for="name">Group Name: </label>
            <input type="text" id="name" name="name"/>
        </div>      

        <button type="submit">Add Group</button>

    </form>
</div>