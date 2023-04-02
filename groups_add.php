<?php

include('includes/database.php');
include('includes/config.php');
include('includes/header.php');
include('includes/functions.php');

secureRoute();

if(isset($_POST['name'])){
    if(validateGroup($_POST['name'])){
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
}

?>

 <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
    <form method="post">

        <div class="form-outline <?php echo (isset($_SESSION['e_name']) ? "mb-1":"form-outline mb-4") ?>">
            <input type="text" id="name" name="name" class="form-control"/>
            <label for="name" class="form-label">Group Name: </label>
        </div>   
        
         <?php 
			if(isset($_SESSION['e_name'])){
				echo '<span class="text-danger">'.$_SESSION['e_name'].'</span>';
				unset($_SESSION['e_name']);
			}
		?>    
        <br/>
        <button type="submit" class="btn btn-primary">Add Group</button>

    </form>
</div>
    </div>
</div>


<?php
include('includes/footer.php');