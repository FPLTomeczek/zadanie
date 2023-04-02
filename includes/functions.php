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
        echo '<p>' . $_SESSION['message'] . '</p><hr/>';
        unset($_SESSION['message']);
    }
}

?>