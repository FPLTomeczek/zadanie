<?php

function secureRoute(){
     if(!isset($_SESSION['id'])){
        header("Location: index.php");
        die();
    }
}

function setMessage($message)
{
    $_SESSION['message'] = $message;
}

function getMessage()
{
    if (isset($_SESSION['message'])) {
        echo '<p class="text-success text-center display-6">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }
}

function validateUser($first_name, $second_name, $date, $username, $password){
    $validateQuery = true;

    if(strlen($first_name) == 0){
        $validateQuery = false;
        $_SESSION['e_first_name'] = "No value entered for first name.";
    }

    if(strlen($second_name) == 0){
        $validateQuery = false;
        $_SESSION['e_second_name'] = "No value entered for second name.";
    }

    if(!$date){
        $validateQuery = false;
        $_SESSION['e_dob'] = "Date is not provided.";
    }

    if(strlen($username)<6 || strlen($username)>30){
        $validateQuery = false;
        $_SESSION['e_username'] = "Username should be 6-20 characters long.";
    }

    if(strlen($password) < 6){
        $validateQuery = false;
        $_SESSION['e_password'] = "Password should be minimum 6 characters long.";
    }

    return $validateQuery;
}

function validateGroup($name){
    $validateQuery = true;

    if(strlen($name) < 3){
        $validateQuery = false;
        $_SESSION['e_name'] = "Name of the group should be minimum 3 characters long";
    }

    return $validateQuery;
}

?>